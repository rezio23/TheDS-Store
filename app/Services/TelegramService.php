<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected ?string $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token', '');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendOrderNotification(\App\Models\Order $order): void
    {
        if (empty($this->token) || empty($this->chatId)) {
            return;
        }

        $items = $order->items()->get();
        $lines = [];

        $lines[] = '🛒 <b>New Order Received</b>';
        $lines[] = '';
        $lines[] = 'Order ID: <b>#' . $order->id . '</b>';
        $lines[] = 'Status: <code>' . ucfirst($order->status) . '</code>';
        $lines[] = '';
        $lines[] = '👤 <b>Customer</b>';
        $lines[] = 'Name: ' . e($order->shipping_name);
        $lines[] = 'Phone: ' . e($order->shipping_phone);
        $lines[] = 'Email: ' . e($order->shipping_email);
        $lines[] = '';
        $lines[] = '📦 <b>Shipping Address</b>';
        $lines[] = e($order->shipping_address);
        $lines[] = 'Postal: ' . e($order->shipping_postal);
        $lines[] = 'Mode: ' . ucfirst($order->shipping_mode);
        $lines[] = '';
        $lines[] = '🧾 <b>Items</b>';

        foreach ($items as $item) {
            $lines[] = '• ' . e($item->product_name) . ' (' . e($item->product_brand) . ') — Size: ' . e($item->size ?? 'One Size') . ' — Qty: ' . $item->quantity . ' x $' . number_format($item->product_price, 2);
        }

        $lines[] = '';
        if ($order->discount > 0) {
            $lines[] = 'Discount: -$' . number_format($order->discount, 2);
        }
        $lines[] = '<b>Total: $' . number_format($order->total, 2) . '</b>';

        $text = implode("\n", $lines);

        $this->sendMessage($text);
    }

    public function sendMessage(string $text): void
    {
        if (empty($this->token) || empty($this->chatId)) {
            return;
        }

        try {
            Http::withoutVerifying()
                ->timeout(15)
                ->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                    'chat_id' => $this->chatId,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram notification failed: ' . $e->getMessage());
        }
    }
}
