<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use App\Services\AbaPaywayService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class CheckoutController extends Controller
{
    protected TelegramService $telegram;
    protected AbaPaywayService $abaPayway;

    public function __construct(TelegramService $telegram, AbaPaywayService $abaPayway)
    {
        $this->telegram = $telegram;
        $this->abaPayway = $abaPayway;
    }
    private function getSizeStock(\App\Models\Product $product, string $size): ?int
    {
        if ($product->sizes && $product->sizes->count()) {
            $found = $product->sizes->firstWhere('size', $size);
            return $found ? (int) $found->quantity : null;
        }
        return $product->stock ?? null;
    }

    private function getTotals(array $cart, array $shipping = [], ?string $promoCode = null): array
    {
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $shippingPrice = match ($shipping['shipping_mode'] ?? 'standard') {
            'fast' => 5.99,
            default => 2.99,
        };

        $taxes = round($subtotal * 0.018, 2);

        $promotion = null;
        $discount = 0;

        if ($promoCode) {
            $promotion = Promotion::where('code', strtoupper($promoCode))->first();
            if ($promotion && $promotion->isValid() && $subtotal >= ($promotion->min_order ?? 0)) {
                if ($promotion->type === 'percentage') {
                    $discount = round($subtotal * ($promotion->value / 100), 2);
                } else {
                    $discount = min((float) $promotion->value, $subtotal);
                }
            }
        }

        $total = max(0, $subtotal + $shippingPrice + $taxes - $discount);

        return compact('subtotal', 'shippingPrice', 'taxes', 'discount', 'total', 'promotion');
    }

    public function shipping()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, [], $promoCode);

        $user = Auth::user();

        return view('shipping', array_merge(compact('cart', 'user', 'promoCode'), $totals));
    }

    public function storeShipping(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\'\.]+$/u'],
            'phone' => ['required', 'string', 'min:7', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'address_1' => ['required', 'string', 'min:5', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\s\-]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'shipping_mode' => ['required', 'string', 'in:standard,fast'],
        ]);

        session(['shipping' => $request->only([
            'full_name', 'phone', 'address_1', 'address_2', 'postal_code',
            'email', 'description', 'shipping_mode',
        ]) + [
            'shipping_cost' => $request->shipping_mode === 'fast' ? 5.99 : 2.99,
        ]]);

        return redirect()->route('payment');
    }

    public function payment()
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart')->with('error', 'Please complete shipping information first.');
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, $shipping, $promoCode);

        $khqrString = null;
        $bakongAccountId = config('services.bakong.account_id');

        if ($bakongAccountId && $bakongAccountId !== 'your_bakong_id@nbcq') {
            try {
                $individualInfo = IndividualInfo::withOptionalArray(
                    $bakongAccountId,
                    config('services.bakong.merchant_name', 'the DS'),
                    config('services.bakong.merchant_city', 'PHNOM PENH'),
                    [
                        'currency' => KHQRData::CURRENCY_USD,
                        'amount' => (float) $totals['total'],
                    ]
                );

                $response = BakongKHQR::generateIndividual($individualInfo);
                $khqrString = $response->data['qr'] ?? null;
            } catch (\Exception $e) {
                Log::warning('Bakong KHQR generation failed: ' . $e->getMessage());
            }
        }

        if (! $khqrString) {
            $khqrString = 'KHQR|theDS|' . number_format($totals['total'], 2) . '|USD';
        }

        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($khqrString);

        $paypalClientId = config('services.paypal.client_id');

        return view('payment', array_merge(compact('cart', 'shipping', 'promoCode', 'qrUrl', 'khqrString', 'paypalClientId'), $totals));
    }

    public function applyPromo(Request $request)
    {
        $code = strtoupper(trim((string) $request->input('promo_code', '')));
        if ($code === '') {
            return back();
        }

        $request->validate([
            'promo_code' => 'string|max:255',
        ]);
        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            session()->forget('promo_code');
            return back()->with('promo_error', 'Invalid promo code.');
        }

        if (!$promotion->isValid()) {
            session()->forget('promo_code');
            return back()->with('promo_error', 'This promo code is no longer valid.');
        }

        $cart = session('cart', []);
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        if ($promotion->min_order && $subtotal < $promotion->min_order) {
            session()->forget('promo_code');
            return back()->with('promo_error', 'Minimum order of $' . number_format($promotion->min_order, 2) . ' required.');
        }

        session(['promo_code' => $code]);
        return back()->with('promo_success', 'Promo code "' . $promotion->code . '" applied! You save ' . $promotion->discount_label . '.');
    }

    public function removePromo()
    {
        session()->forget('promo_code');
        return back()->with('promo_success', 'Promo code removed.');
    }

    public function processPayment(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart')->with('error', 'Invalid checkout state.');
        }

        foreach ($cart as $key => $item) {
            $product = \App\Models\Product::with('sizes')->where('slug', $item['slug'])->first();
            if (!$product) {
                return redirect()->route('cart')->with('error', 'Product not found: ' . $item['name']);
            }
            $stock = $this->getSizeStock($product, $item['size']);
            if ($stock !== null && $item['quantity'] > $stock) {
                return redirect()->route('cart')->with('error', 'Only ' . $stock . ' item(s) available for size ' . $item['size'] . ' of ' . $item['name'] . '. Please update your cart.');
            }
        }

        $cardNumber = trim((string) $request->input('card_number', ''));
        $cardExpiry = trim((string) $request->input('card_expiry', ''));
        $cardCvc = trim((string) $request->input('card_cvc', ''));
        $cardName = trim((string) $request->input('card_name', ''));

        $hasCardFields = $cardNumber !== '' || $cardExpiry !== '' || $cardCvc !== '' || $cardName !== '';
        $paymentMethod = $hasCardFields ? 'debit_card' : 'khqr';

        if ($paymentMethod === 'debit_card') {
            $request->validate([
                'card_number' => ['required', 'string', 'min:13', 'max:19', 'regex:/^[\d\s]+$/'],
                'card_expiry' => ['required', 'string', 'size:7', 'regex:/^(0[1-9]|1[0-2])\s\/\s\d{2}$/'],
                'card_cvc' => ['required', 'string', 'min:3', 'max:4', 'regex:/^\d+$/'],
                'card_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\'\.]+$/u'],
            ]);
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, $shipping, $promoCode);

        $address = ($shipping['address_1'] ?? '');
        if (!empty($shipping['address_2'])) {
            $address .= ', ' . $shipping['address_2'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'promotion_id' => $totals['promotion']?->id,
            'total' => $totals['total'],
            'discount' => $totals['discount'],
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'shipping_name' => $shipping['full_name'],
            'shipping_phone' => $shipping['phone'],
            'shipping_address' => $address,
            'shipping_postal' => $shipping['postal_code'],
            'shipping_email' => $shipping['email'],
            'shipping_mode' => $shipping['shipping_mode'],
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_brand' => $item['brand'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? 'One Size',
                'product_image' => $item['image'] ?? null,
            ]);
        }

        if ($totals['promotion']) {
            $totals['promotion']->increment('uses_count');
        }

        $order->load('items');
        $this->telegram->sendOrderNotification($order);

        session()->forget(['cart', 'shipping', 'promo_code']);

        return redirect()->route('profile', ['ordered' => 1]);
    }

    public function processPayPal(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return response()->json(['success' => false, 'message' => 'Invalid checkout state.'], 400);
        }

        foreach ($cart as $key => $item) {
            $product = \App\Models\Product::with('sizes')->where('slug', $item['slug'])->first();
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found: ' . $item['name']], 400);
            }
            $stock = $this->getSizeStock($product, $item['size']);
            if ($stock !== null && $item['quantity'] > $stock) {
                return response()->json(['success' => false, 'message' => 'Only ' . $stock . ' item(s) available for size ' . $item['size'] . ' of ' . $item['name'] . '.'], 400);
            }
        }

        $request->validate([
            'paypal_order_id' => ['required', 'string', 'max:255'],
        ]);

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, $shipping, $promoCode);

        $address = ($shipping['address_1'] ?? '');
        if (!empty($shipping['address_2'])) {
            $address .= ', ' . $shipping['address_2'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'promotion_id' => $totals['promotion']?->id,
            'total' => $totals['total'],
            'discount' => $totals['discount'],
            'status' => 'pending',
            'payment_method' => 'paypal',
            'shipping_name' => $shipping['full_name'],
            'shipping_phone' => $shipping['phone'],
            'shipping_address' => $address,
            'shipping_postal' => $shipping['postal_code'],
            'shipping_email' => $shipping['email'],
            'shipping_mode' => $shipping['shipping_mode'],
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_brand' => $item['brand'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? 'One Size',
                'product_image' => $item['image'] ?? null,
            ]);
        }

        if ($totals['promotion']) {
            $totals['promotion']->increment('uses_count');
        }

        $order->load('items');
        $this->telegram->sendOrderNotification($order);

        session()->forget(['cart', 'shipping', 'promo_code']);

        return response()->json(['success' => true, 'redirect' => route('profile', ['ordered' => 1])]);
    }

    public function processAbaPayway(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return response()->json(['success' => false, 'message' => 'Invalid checkout state.'], 400);
        }

        foreach ($cart as $key => $item) {
            $product = \App\Models\Product::with('sizes')->where('slug', $item['slug'])->first();
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found: ' . $item['name']], 400);
            }
            $stock = $this->getSizeStock($product, $item['size']);
            if ($stock !== null && $item['quantity'] > $stock) {
                return response()->json(['success' => false, 'message' => 'Only ' . $stock . ' item(s) available for size ' . $item['size'] . ' of ' . $item['name'] . '.'], 400);
            }
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, $shipping, $promoCode);

        $address = ($shipping['address_1'] ?? '');
        if (!empty($shipping['address_2'])) {
            $address .= ', ' . $shipping['address_2'];
        }

        $transactionId = substr('ABA' . time() . (Auth::id() ?? '0'), 0, 20);

        $result = $this->abaPayway->createPurchase(
            $transactionId,
            (float) $totals['total'],
            $shipping,
            $cart,
            route('payment.aba.return'),
            route('payment')
        );

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 500);
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'promotion_id' => $totals['promotion']?->id,
            'total' => $totals['total'],
            'discount' => $totals['discount'],
            'status' => 'pending',
            'payment_method' => 'aba_payway',
            'gateway_transaction_id' => $transactionId,
            'shipping_name' => $shipping['full_name'],
            'shipping_phone' => $shipping['phone'],
            'shipping_address' => $address,
            'shipping_postal' => $shipping['postal_code'],
            'shipping_email' => $shipping['email'],
            'shipping_mode' => $shipping['shipping_mode'],
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_brand' => $item['brand'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? 'One Size',
                'product_image' => $item['image'] ?? null,
            ]);
        }

        if ($totals['promotion']) {
            $totals['promotion']->increment('uses_count');
        }

        $order->load('items');
        $this->telegram->sendOrderNotification($order);

        session()->forget(['cart', 'shipping', 'promo_code']);

        return response()->json(['success' => true, 'redirect' => $result['payment_url']]);
    }

    public function abaReturn(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');

        $order = Order::where('gateway_transaction_id', $transactionId)->first();

        if (!$order) {
            return redirect()->route('profile')->with('error', 'Order not found.');
        }

        $order->update([
            'status' => $status === 'success' ? 'paid' : 'failed',
        ]);

        if ($status === 'success') {
            return redirect()->route('profile', ['ordered' => 1])->with('success', 'Payment completed successfully.');
        }

        return redirect()->route('profile')->with('error', 'Payment was cancelled or failed.');
    }
}
