<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        // Handle POST actions
        if ($request->isMethod('post')) {
            if ($request->has('update_order_status')) {
                return $this->updateOrderStatus($request);
            }
            if ($request->has('add_product')) {
                return $this->addProduct($request);
            }
            if ($request->has('delete_product')) {
                return $this->deleteProduct($request);
            }
            if ($request->has('edit_product')) {
                return $this->editProduct($request);
            }
            if ($request->has('add_category')) {
                return $this->addCategory($request);
            }
            if ($request->has('delete_category')) {
                return $this->deleteCategory($request);
            }
            if ($request->has('update_request_status')) {
                return $this->updateRequestStatus($request);
            }
            if ($request->has('send_announcement')) {
                return $this->sendAnnouncement($request);
            }
            if ($request->has('add_promotion')) {
                return $this->addPromotion($request);
            }
            if ($request->has('delete_promotion')) {
                return $this->deletePromotion($request);
            }
            if ($request->has('toggle_promotion')) {
                return $this->togglePromotion($request);
            }
        }

        $activeTab = $request->get('tab', 'dashboard');

        // Stats
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total') ?? 0;

        // Monthly revenue for chart
        $monthlyRevenue = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COALESCE(SUM(total), 0) as revenue')
        )
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();

        // All categories
        $allCategories = Category::orderBy('name')->get();

        // Products
        $selectedCategory = $request->get('category', '');
        $selectedSort = $request->get('sort', 'number_desc');

        $productsQuery = Product::with('categoryModel');
        if ($selectedCategory !== '') {
            $productsQuery->where('category', $selectedCategory);
        }

        $orderBy = match ($selectedSort) {
            'number_asc' => 'id ASC',
            'number_desc' => 'id DESC',
            'name_asc' => 'name ASC',
            'name_desc' => 'name DESC',
            'price_asc' => 'price ASC',
            'price_desc' => 'price DESC',
            default => 'id DESC',
        };
        $productsQuery->orderByRaw($orderBy);
        $allProducts = $productsQuery->get();

        // All orders
        $allOrders = Order::with(['user', 'promotion'])->orderBy('created_at', 'desc')->get();

        $allRequests = UserRequest::with('user')->orderBy('created_at', 'desc')->get();

        // Reports data
        $reportRange = $request->get('range', '30d');
        $reportStartDate = null;
        $reportEndDate = now()->endOfDay();
        $reportRangeLabel = 'Last 30 Days';

        switch ($reportRange) {
            case 'today':
                $reportStartDate = now()->startOfDay();
                $reportRangeLabel = 'Today';
                break;
            case '7d':
                $reportStartDate = now()->subDays(7)->startOfDay();
                $reportRangeLabel = 'Last 7 Days';
                break;
            case 'this_month':
                $reportStartDate = now()->startOfMonth()->startOfDay();
                $reportRangeLabel = 'This Month';
                break;
            case 'last_month':
                $reportStartDate = now()->subMonth()->startOfMonth()->startOfDay();
                $reportEndDate = now()->subMonth()->endOfMonth()->endOfDay();
                $reportRangeLabel = 'Last Month';
                break;
            case 'this_year':
                $reportStartDate = now()->startOfYear()->startOfDay();
                $reportRangeLabel = 'This Year';
                break;
            case 'all':
                $reportStartDate = null;
                $reportRangeLabel = 'All Time';
                break;
            default:
                $reportRange = '30d';
                $reportStartDate = now()->subDays(30)->startOfDay();
                $reportRangeLabel = 'Last 30 Days';
        }

        $reportOrderQuery = Order::query();
        $reportItemQuery = DB::table('order_items')->join('orders', 'order_items.order_id', '=', 'orders.id');
        $reportUserQuery = User::query();
        $reportDailyQuery = Order::where('status', '!=', 'cancelled');

        if ($reportStartDate) {
            $reportOrderQuery->whereBetween('created_at', [$reportStartDate, $reportEndDate]);
            $reportItemQuery->whereBetween('orders.created_at', [$reportStartDate, $reportEndDate]);
            $reportUserQuery->whereBetween('created_at', [$reportStartDate, $reportEndDate]);
            $reportDailyQuery->whereBetween('created_at', [$reportStartDate, $reportEndDate]);
        }

        $reportTotalRevenue = (float) $reportOrderQuery->clone()->where('status', '!=', 'cancelled')->sum('total') ?? 0;
        $reportTotalOrders = (int) $reportOrderQuery->clone()->count();
        $reportAvgOrderValue = $reportTotalOrders > 0 ? $reportTotalRevenue / $reportTotalOrders : 0;
        $reportTotalDiscounts = (float) $reportOrderQuery->clone()->where('status', '!=', 'cancelled')->sum('discount') ?? 0;

        $orderStatusBreakdown = $reportOrderQuery->clone()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = $reportItemQuery
            ->select('order_items.product_name', 'order_items.product_brand', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'), DB::raw('COALESCE(SUM(order_items.product_price * order_items.quantity), 0) as revenue'))
            ->groupBy('order_items.product_name', 'order_items.product_brand')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $dailyRevenue = $reportDailyQuery
            ->select(
                DB::raw("DATE(created_at) as day"),
                DB::raw('COALESCE(SUM(total), 0) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $userGrowth = $reportUserQuery
            ->select(
                DB::raw("DATE(created_at) as day"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $sortLabels = [
            'number_desc' => 'Number',
            'number_asc' => 'Number',
            'name_asc' => 'Name (A-Z)',
            'name_desc' => 'Name (Z-A)',
            'price_asc' => 'Price (Low to High)',
            'price_desc' => 'Price (High to Low)',
        ];
        $currentSortLabel = $sortLabels[$selectedSort] ?? 'Number';

        // Promotions
        $allPromotions = Promotion::orderBy('created_at', 'desc')->get();
        $promotionChartData = Promotion::orderBy('uses_count', 'desc')->limit(10)->get(['code', 'uses_count']);

        $promoOrders = Order::whereNotNull('promotion_id');
        $promoRevenueEarned = (float) $promoOrders->clone()->sum('total');
        $promoRevenueLost = (float) $promoOrders->clone()->sum('discount');
        $promoTotalUses = (int) $promoOrders->clone()->count();

        $promotionOrderStats = Order::select(
            'promotion_id',
            DB::raw('COALESCE(SUM(total), 0) as revenue'),
            DB::raw('COALESCE(SUM(discount), 0) as discount'),
            DB::raw('COUNT(*) as uses')
        )
            ->whereNotNull('promotion_id')
            ->where('status', '!=', 'cancelled')
            ->groupBy('promotion_id')
            ->get()
            ->keyBy('promotion_id');

        // Announcements
        $announcementStatus = $request->get('status', '');
        $annPath = storage_path('app/announcements.json');
        $announcements = [];
        if (is_file($annPath)) {
            $content = file_get_contents($annPath);
            if ($content !== false) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $announcements = array_reverse($decoded);
                }
            }
        }

        return view('admin.dashboard', compact(
            'activeTab',
            'totalUsers',
            'totalOrders',
            'totalProducts',
            'totalRevenue',
            'monthlyRevenue',
            'recentOrders',
            'recentUsers',
            'allCategories',
            'allProducts',
            'allOrders',
            'allRequests',
            'selectedCategory',
            'selectedSort',
            'currentSortLabel',
            'announcementStatus',
            'announcements',
            'allPromotions',
            'promotionChartData',
            'promotionOrderStats',
            'promoRevenueEarned',
            'promoRevenueLost',
            'promoTotalUses',
            'reportTotalRevenue',
            'reportTotalOrders',
            'reportAvgOrderValue',
            'reportTotalDiscounts',
            'orderStatusBreakdown',
            'topProducts',
            'dailyRevenue',
            'userGrowth',
            'reportRange',
            'reportRangeLabel',
            'reportStartDate',
            'reportEndDate'
        ));
    }

    public function ordersData(): JsonResponse
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total') ?? 0;

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->full_name ?? 'Guest',
                    'total' => (float) $order->total,
                    'status' => $order->status,
                    'date' => $order->created_at->format('M d, Y'),
                ];
            });

        $allOrders = Order::with(['user', 'promotion'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->full_name ?? 'Guest',
                    'total' => (float) $order->total,
                    'discount' => (float) $order->discount,
                    'promo' => $order->promotion->code ?? null,
                    'status' => $order->status,
                    'shipping' => $order->shipping_mode ?? 'Standard',
                    'datetime' => $order->created_at->format('M d, Y H:i'),
                ];
            });

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalRevenue' => (float) $totalRevenue,
            'recentOrders' => $recentOrders,
            'allOrders' => $allOrders,
        ]);
    }

    private function updateOrderStatus(Request $request): RedirectResponse
    {
        $orderId = (int) $request->input('order_id', 0);
        $newStatus = $request->input('status');
        $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if ($orderId > 0 && in_array($newStatus, $allowedStatuses, true)) {
            Order::where('id', $orderId)->update(['status' => $newStatus]);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'orders']);
    }

    private function addProduct(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'rating' => 'nullable|string|max:10',
            'image' => 'required|url|max:2000',
            'gallery' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($data['name'])), '-');
        Product::create([
            'slug' => $slug,
            'name' => $data['name'],
            'brand' => $data['brand'],
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'tags' => $data['tags'] ?? '',
            'rating' => $data['rating'] ?? '',
            'badge' => $data['badge'] ?? '',
            'image' => $data['image'],
            'gallery' => $data['gallery'] ?? '',
            'category' => $data['category'] ?? '',
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'products']);
    }

    private function deleteProduct(Request $request): RedirectResponse
    {
        $productId = (int) $request->input('product_id', 0);
        if ($productId > 0) {
            Product::where('id', $productId)->delete();
        }
        return redirect()->route('admin.dashboard', ['tab' => 'products']);
    }

    private function editProduct(Request $request): RedirectResponse
    {
        $productId = (int) $request->input('product_id', 0);
        if ($productId <= 0) {
            return redirect()->route('admin.dashboard', ['tab' => 'products']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'rating' => 'nullable|string|max:10',
            'image' => 'required|url|max:2000',
            'gallery' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($data['name'])), '-');
        Product::where('id', $productId)->update([
            'slug' => $slug,
            'name' => $data['name'],
            'brand' => $data['brand'],
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category' => $data['category'] ?? '',
            'tags' => $data['tags'] ?? '',
            'badge' => $data['badge'] ?? '',
            'rating' => $data['rating'] ?? '',
            'image' => $data['image'],
            'gallery' => $data['gallery'] ?? '',
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'products']);
    }

    private function addCategory(Request $request): RedirectResponse
    {
        $catName = $request->input('category_name');
        if ($catName) {
            $catSlug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($catName)), '-');
            Category::updateOrCreate(
                ['slug' => $catSlug],
                ['name' => $catName]
            );
        }
        return redirect()->route('admin.dashboard', ['tab' => 'products']);
    }

    private function deleteCategory(Request $request): RedirectResponse
    {
        $catId = (int) $request->input('category_id', 0);
        if ($catId > 0) {
            Category::where('id', $catId)->delete();
        }
        return redirect()->route('admin.dashboard', ['tab' => 'products']);
    }

    private function updateRequestStatus(Request $request): RedirectResponse
    {
        $requestId = (int) $request->input('request_id', 0);
        $newStatus = $request->input('status');
        $allowedStatuses = ['pending', 'accepted', 'rejected'];
        if ($requestId > 0 && in_array($newStatus, $allowedStatuses, true)) {
            UserRequest::where('id', $requestId)->update(['status' => $newStatus]);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'requests']);
    }

    private function sendAnnouncement(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => 'required|string|max:120',
            'message' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
        ]);

        $subject = $data['subject'];
        $message = $data['message'];

        // Store attachment if present
        $imagePath = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $imagePath = $request->file('attachment')->store('announcements', 'public');
        }

        // Get all user emails
        $emails = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->toArray();
        $sentCount = 0;

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // In production, use Mail facade; for now just count
                $sentCount++;
            }
        }

        // Save record
        $annPath = storage_path('app/announcements.json');
        $records = [];
        if (is_file($annPath)) {
            $content = file_get_contents($annPath);
            if ($content !== false) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $records = $decoded;
                }
            }
        }
        $records[] = [
            'id' => 'ann_' . uniqid('', true),
            'subject' => $subject,
            'message' => $message,
            'file' => $imagePath ? basename($imagePath) : '',
            'sent_count' => $sentCount,
            'total_users' => count($emails),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
        file_put_contents($annPath, json_encode($records, JSON_PRETTY_PRINT));

        // Create in-app notifications for all users
        $userIds = User::whereNotNull('email')->where('email', '!=', '')->pluck('id')->toArray();
        $now = now();
        $notifications = array_map(function ($userId) use ($subject, $message, $imagePath, $now) {
            return [
                'user_id' => $userId,
                'title' => $subject,
                'message' => $message,
                'type' => 'announcement',
                'link' => null,
                'image' => $imagePath,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $userIds);

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }

        $status = 'success:' . $sentCount;
        return redirect()->route('admin.dashboard', ['tab' => 'announcements', 'status' => $status]);
    }

    private function addPromotion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:promotions,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        Promotion::create([
            'code' => strtoupper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'min_order' => !empty($data['min_order']) ? $data['min_order'] : null,
            'max_uses' => !empty($data['max_uses']) ? $data['max_uses'] : null,
            'starts_at' => !empty($data['starts_at']) ? $data['starts_at'] : null,
            'expires_at' => !empty($data['expires_at']) ? $data['expires_at'] : null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'promotions']);
    }

    private function deletePromotion(Request $request): RedirectResponse
    {
        $promoId = (int) $request->input('promotion_id', 0);
        if ($promoId > 0) {
            Promotion::where('id', $promoId)->delete();
        }
        return redirect()->route('admin.dashboard', ['tab' => 'promotions']);
    }

    private function togglePromotion(Request $request): RedirectResponse
    {
        $promoId = (int) $request->input('promotion_id', 0);
        $promo = Promotion::find($promoId);
        if ($promo) {
            $promo->update(['is_active' => !$promo->is_active]);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'promotions']);
    }
}
