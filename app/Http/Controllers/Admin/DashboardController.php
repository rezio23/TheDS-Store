<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(10)->get();

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();

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
        $allOrders = Order::with('user')->orderBy('created_at', 'desc')->get();

        // All requests
        $allRequests = UserRequest::with('user')->orderBy('created_at', 'desc')->get();

        $sortLabels = [
            'number_desc' => 'Number',
            'number_asc' => 'Number',
            'name_asc' => 'Name (A-Z)',
            'name_desc' => 'Name (Z-A)',
            'price_asc' => 'Price (Low to High)',
            'price_desc' => 'Price (High to Low)',
        ];
        $currentSortLabel = $sortLabels[$selectedSort] ?? 'Number';

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
            'announcements'
        ));
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
            'file' => $request->file('attachment') ? $request->file('attachment')->getClientOriginalName() : '',
            'sent_count' => $sentCount,
            'total_users' => count($emails),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
        file_put_contents($annPath, json_encode($records, JSON_PRETTY_PRINT));

        $status = 'success:' . $sentCount;
        return redirect()->route('admin.dashboard', ['tab' => 'announcements', 'status' => $status]);
    }
}
