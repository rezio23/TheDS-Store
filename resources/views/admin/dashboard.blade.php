<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    @include('admin.partials.styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
</head>
<body class="admin-body">

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <a href="{{ url('/') }}">the DS</a>
            <span class="admin-badge">Admin</span>
        </div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}?tab=dashboard" class="admin-nav-link {{ $activeTab === 'dashboard' ? 'is-active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('admin.dashboard') }}?tab=orders" class="admin-nav-link {{ $activeTab === 'orders' ? 'is-active' : '' }}">
                <i data-lucide="shopping-cart"></i> Orders
            </a>
            <a href="{{ route('admin.dashboard') }}?tab=products" class="admin-nav-link {{ $activeTab === 'products' ? 'is-active' : '' }}">
                <i data-lucide="package"></i> Products
            </a>
            <a href="{{ route('admin.dashboard') }}?tab=users" class="admin-nav-link {{ $activeTab === 'users' ? 'is-active' : '' }}">
                <i data-lucide="users"></i> Users
            </a>
            <a href="{{ route('admin.dashboard') }}?tab=requests" class="admin-nav-link {{ $activeTab === 'requests' ? 'is-active' : '' }}">
                <i data-lucide="inbox"></i> User Requests
            </a>
            <a href="{{ route('admin.dashboard') }}?tab=announcements" class="admin-nav-link {{ $activeTab === 'announcements' ? 'is-active' : '' }}">
                <i data-lucide="megaphone"></i> Announcements
            </a>
        </nav>
        <div class="admin-sidebar-footer">
            <span class="admin-email">{{ Auth::user()->email ?? '' }}</span>
            <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="admin-logout" style="background:none;border:none;cursor:pointer;color:inherit;font:inherit;display:flex;align-items:center;gap:0.4rem;">
                    <i data-lucide="log-out"></i> Log Out
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @if ($activeTab === 'dashboard')
            <div class="admin-header">
                <h1>Dashboard</h1>
            </div>

            <div class="admin-stats">
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="users"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value">{{ number_format($totalUsers) }}</span>
                        <span class="admin-stat-label">Total Users</span>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="shopping-bag"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value">{{ number_format($totalOrders) }}</span>
                        <span class="admin-stat-label">Total Orders</span>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="package"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value">{{ number_format($totalProducts) }}</span>
                        <span class="admin-stat-label">Total Products</span>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="dollar-sign"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value">${{ number_format($totalRevenue, 2) }}</span>
                        <span class="admin-stat-label">Total Revenue</span>
                    </div>
                </div>
            </div>

            <div class="admin-section admin-chart-section">
                <h2>Monthly Revenue</h2>
                <div class="admin-chart-wrap">
                    @if ($monthlyRevenue->isEmpty())
                        <div class="admin-chart-empty">No revenue data yet.</div>
                    @else
                        <canvas id="revenueChart"></canvas>
                    @endif
                </div>
            </div>

            <div class="admin-sections">
                <div class="admin-section">
                    <h2>Recent Orders</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->full_name ?? 'Guest' }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td><span class="admin-badge-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="admin-section">
                    <h2>Recent Users</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentUsers as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif ($activeTab === 'orders')
            <div class="admin-header">
                <h1>Orders</h1>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Shipping</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->full_name ?? 'Guest' }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td><span class="admin-badge-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->shipping_mode ?? 'Standard' }}</td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form method="post" action="{{ route('admin.dashboard') }}?tab=orders" style="display:flex;gap:0.5rem;align-items:center;">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <div class="admin-select-control" data-admin-select>
                                            <button type="button" class="admin-select-toggle" data-admin-select-toggle aria-expanded="false" aria-haspopup="listbox" aria-controls="order-status-{{ $order->id }}">
                                                <span data-admin-select-current>{{ ucfirst($order->status) }}</span>
                                                <i data-lucide="chevron-down"></i>
                                            </button>
                                            <div class="admin-select-menu" id="order-status-{{ $order->id }}" role="listbox" data-admin-select-menu>
                                                @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                                                    <button type="button" role="option" data-admin-select-option data-value="{{ $s }}" class="{{ $order->status === $s ? 'is-selected' : '' }}" aria-selected="{{ $order->status === $s ? 'true' : 'false' }}">{{ ucfirst($s) }}</button>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="status" value="{{ $order->status }}" data-admin-select-input>
                                        </div>
                                        <button type="submit" name="update_order_status" class="admin-btn admin-btn--small">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif ($activeTab === 'products')
            <div class="admin-header">
                <h1>Products</h1>
            </div>

            <div class="admin-section" style="margin-bottom: 24px;">
                <div style="display:flex;gap:12px;">
                    <button type="button" class="admin-btn admin-toggle" style="flex:1;" data-admin-toggle aria-expanded="false" aria-controls="add-product-form">
                        <i data-lucide="plus"></i> <span>Add New Product</span>
                        <i data-lucide="chevron-down" class="admin-toggle__icon" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="admin-btn admin-toggle" style="flex:1;" data-admin-toggle aria-expanded="false" aria-controls="category-manager">
                        <i data-lucide="folder-open"></i> <span>Manage Categories</span>
                        <i data-lucide="chevron-down" class="admin-toggle__icon" aria-hidden="true"></i>
                    </button>
                </div>

                <form id="add-product-form" method="post" action="{{ route('admin.dashboard') }}?tab=products" class="admin-form admin-panel-content" data-admin-content hidden>
                    @csrf
                    <div class="admin-form-grid">
                        <div class="admin-form-group">
                            <label for="prod-name">Name</label>
                            <input type="text" id="prod-name" name="name" required>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-brand">Brand</label>
                            <input type="text" id="prod-brand" name="brand" required>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-price">Price</label>
                            <input type="number" id="prod-price" name="price" step="0.01" min="0.01" required>
                        </div>
                        <div class="admin-form-group">
                            <label>Category</label>
                            <div class="admin-select-control" data-admin-select style="width:100%;border-radius:8px;">
                                <button type="button" class="admin-select-toggle" data-admin-select-toggle aria-expanded="false" aria-haspopup="listbox" aria-controls="prod-cat-menu" style="width:100%;">
                                    <span data-admin-select-current>— Select Category —</span>
                                    <i data-lucide="chevron-down"></i>
                                </button>
                                <div class="admin-select-menu" id="prod-cat-menu" role="listbox" data-admin-select-menu>
                                    <button type="button" role="option" data-admin-select-option data-value="" class="is-selected" aria-selected="true">— Select Category —</button>
                                    @foreach ($allCategories as $cat)
                                        <button type="button" role="option" data-admin-select-option data-value="{{ $cat->slug }}" aria-selected="false">{{ $cat->name }}</button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="category" id="prod-category" value="">
                            </div>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-badge">Badge</label>
                            <input type="text" id="prod-badge" name="badge" placeholder="e.g. New, Sale">
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-rating">Rating</label>
                            <input type="text" id="prod-rating" name="rating" placeholder="e.g. 4.5">
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="prod-image">Image URL</label>
                            <input type="url" id="prod-image" name="image" placeholder="https://example.com/image.png" required>
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label>Gallery Images</label>
                            <div id="gallery-list" class="gallery-list">
                                <div class="gallery-row">
                                    <input type="url" class="gallery-url" placeholder="https://example.com/image.png">
                                    <button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="admin-btn admin-btn--small" id="gallery-add-btn">
                                <i data-lucide="plus"></i> Add Image
                            </button>
                            <input type="hidden" name="gallery" id="gallery-combined">
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="prod-tags">Tags (comma-separated)</label>
                            <input type="text" id="prod-tags" name="tags" placeholder="e.g. sneaker, man, popular">
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="prod-description">Description</label>
                            <textarea id="prod-description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" name="add_product" class="admin-btn">Save Product</button>
                    </div>
                </form>

                <script>
                    (function() {
                        var form = document.getElementById('add-product-form');
                        form.addEventListener('submit', function() {
                            var inputs = form.querySelectorAll('.gallery-url');
                            var urls = [];
                            inputs.forEach(function(input) {
                                var val = input.value.trim();
                                if (val) urls.push(val);
                            });
                            document.getElementById('gallery-combined').value = urls.join('|');
                        });
                        document.getElementById('gallery-add-btn').addEventListener('click', function() {
                            var row = document.createElement('div');
                            row.className = 'gallery-row';
                            row.innerHTML = '<input type="url" class="gallery-url" placeholder="https://example.com/image.png"><button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>';
                            document.getElementById('gallery-list').appendChild(row);
                        });
                        document.getElementById('gallery-list').addEventListener('click', function(e) {
                            if (e.target.classList.contains('gallery-remove')) {
                                e.target.parentElement.remove();
                            }
                        });
                    })();
                </script>

                <div id="category-manager" class="admin-panel-content" data-admin-content hidden>
                    <div class="admin-form" style="margin-bottom:18px;">
                        <h3 style="margin:0 0 14px;font-size:0.65rem;font-weight:400;">Add New Category</h3>
                        <form method="post" action="{{ route('admin.dashboard') }}?tab=products">
                            @csrf
                            <div style="display:flex;gap:10px;align-items:flex-end;">
                                <div class="admin-form-group" style="flex:1;">
                                    <label for="category-name">Category Name</label>
                                    <input type="text" id="category-name" name="category_name" placeholder="e.g. Sneakers" required>
                                </div>
                                <button type="submit" name="add_category" class="admin-btn">Add</button>
                            </div>
                        </form>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allCategories as $cat)
                                    <tr>
                                        <td>{{ $cat->id }}</td>
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ $cat->slug }}</td>
                                        <td>
                                            <form method="post" action="{{ route('admin.dashboard') }}?tab=products" onsubmit="return confirm('Delete this category?');" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="category_id" value="{{ $cat->id }}">
                                                <button type="submit" name="delete_category" class="admin-btn admin-btn--danger admin-btn--small">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @php
                $currentCategoryLabel = 'All Categories';
                foreach ($allCategories as $cat) {
                    if ($selectedCategory === $cat->slug) {
                        $currentCategoryLabel = $cat->name;
                        break;
                    }
                }
            @endphp

            <div class="admin-filter-bar" style="display:flex;justify-content:flex-end;margin-bottom:12px;">
                <form method="get" action="{{ route('admin.dashboard') }}" id="admin-filter-form" style="display:flex;gap:8px;align-items:center;">
                    <input type="hidden" name="tab" value="products">
                    <input type="hidden" name="category" id="admin-category-input" value="{{ $selectedCategory }}">
                    <input type="hidden" name="sort" id="admin-sort-input" value="{{ $selectedSort }}">

                    <div class="admin-select-control" data-admin-select data-admin-auto-submit>
                        <span class="admin-select-control__label">Category</span>
                        <button type="button" class="admin-select-toggle" data-admin-select-toggle aria-expanded="false" aria-haspopup="listbox" aria-controls="admin-cat-menu">
                            <span data-admin-select-current>{{ $currentCategoryLabel }}</span>
                            <i data-lucide="chevron-down"></i>
                        </button>
                        <div class="admin-select-menu" id="admin-cat-menu" role="listbox" data-admin-select-menu>
                            <button type="button" role="option" data-admin-select-option data-value="" class="{{ $selectedCategory === '' ? 'is-selected' : '' }}" aria-selected="{{ $selectedCategory === '' ? 'true' : 'false' }}">All Categories</button>
                            @foreach ($allCategories as $cat)
                                <button type="button" role="option" data-admin-select-option data-value="{{ $cat->slug }}" class="{{ $selectedCategory === $cat->slug ? 'is-selected' : '' }}" aria-selected="{{ $selectedCategory === $cat->slug ? 'true' : 'false' }}">{{ $cat->name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="admin-select-control" data-admin-select data-admin-auto-submit>
                        <span class="admin-select-control__label">Sort</span>
                        <button type="button" class="admin-select-toggle" data-admin-select-toggle aria-expanded="false" aria-haspopup="listbox" aria-controls="admin-sort-menu">
                            <span data-admin-select-current>
                                @php
                                    $sortLabels = [
                                        'number_desc' => 'Number',
                                        'name_asc' => 'Name (A-Z)',
                                        'name_desc' => 'Name (Z-A)',
                                        'price_asc' => 'Price (Low to High)',
                                        'price_desc' => 'Price (High to Low)',
                                    ];
                                    echo $sortLabels[$selectedSort] ?? 'Number';
                                @endphp
                            </span>
                            <i data-lucide="chevron-down"></i>
                        </button>
                        <div class="admin-select-menu" id="admin-sort-menu" role="listbox" data-admin-select-menu>
                            <button type="button" role="option" data-admin-select-option data-value="number_desc" class="{{ $selectedSort === 'number_desc' ? 'is-selected' : '' }}" aria-selected="{{ $selectedSort === 'number_desc' ? 'true' : 'false' }}">Number</button>
                            <button type="button" role="option" data-admin-select-option data-value="name_asc" class="{{ $selectedSort === 'name_asc' ? 'is-selected' : '' }}" aria-selected="{{ $selectedSort === 'name_asc' ? 'true' : 'false' }}">Name (A-Z)</button>
                            <button type="button" role="option" data-admin-select-option data-value="name_desc" class="{{ $selectedSort === 'name_desc' ? 'is-selected' : '' }}" aria-selected="{{ $selectedSort === 'name_desc' ? 'true' : 'false' }}">Name (Z-A)</button>
                            <button type="button" role="option" data-admin-select-option data-value="price_asc" class="{{ $selectedSort === 'price_asc' ? 'is-selected' : '' }}" aria-selected="{{ $selectedSort === 'price_asc' ? 'true' : 'false' }}">Price (Low to High)</button>
                            <button type="button" role="option" data-admin-select-option data-value="price_desc" class="{{ $selectedSort === 'price_desc' ? 'is-selected' : '' }}" aria-selected="{{ $selectedSort === 'price_desc' ? 'true' : 'false' }}">Price (High to Low)</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $productIndex = 1; @endphp
                        @foreach ($allProducts as $product)
                            <tr>
                                <td>{{ $productIndex++ }}</td>
                                <td>{{ $product->id }}</td>
                                <td><img src="{{ asset('storage/' . $product->image) }}" alt="" class="admin-product-thumb"></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock ?? 0 }}</td>
                                <td>{{ $product->categoryModel->name ?? '—' }}</td>
                                <td>
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        <button type="button" class="admin-btn admin-btn--small">View</button>
                                        <button type="button" class="admin-btn admin-btn--small">Edit</button>
                                        <form method="post" action="{{ route('admin.dashboard') }}?tab=products" onsubmit="return confirm('Delete this product?');" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" name="delete_product" class="admin-btn admin-btn--danger admin-btn--small">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif ($activeTab === 'users')
            <div class="admin-header">
                <h1>Users</h1>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\App\Models\User::orderBy('id', 'desc')->get() as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td>{{ ucfirst($user->gender ?? '—') }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif ($activeTab === 'requests')
            <div class="admin-header">
                <h1>User Requests</h1>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allRequests as $req)
                            <tr>
                                <td>#{{ $req->id }}</td>
                                <td>{{ $req->email }}</td>
                                <td>{{ $req->phone ?? '—' }}</td>
                                <td>{{ $req->subject }}</td>
                                <td><span class="admin-badge-status status-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                                <td>{{ $req->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        @if ($req->status === 'pending')
                                            <form method="post" action="{{ route('admin.dashboard') }}?tab=requests" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="request_id" value="{{ $req->id }}">
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" name="update_request_status" class="admin-btn admin-btn--small">Accept</button>
                                            </form>
                                            <form method="post" action="{{ route('admin.dashboard') }}?tab=requests" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="request_id" value="{{ $req->id }}">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" name="update_request_status" class="admin-btn admin-btn--danger admin-btn--small">Reject</button>
                                            </form>
                                        @else
                                            <span class="admin-badge-status status-delivered">Processed</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif ($activeTab === 'announcements')
            <div class="admin-header">
                <h1>Announcements</h1>
            </div>

            <div class="admin-section" style="margin-bottom: 24px;">
                <form method="post" action="{{ route('admin.dashboard') }}?tab=announcements" enctype="multipart/form-data" id="announcement-form">
                    @csrf
                    <div class="admin-form-grid">
                        <div class="admin-form-group admin-form-group--full">
                            <label for="ann-subject">Subject</label>
                            <input type="text" id="ann-subject" name="subject" placeholder="e.g. New Collection Launch" maxlength="120" required>
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="ann-message">Message</label>
                            <textarea id="ann-message" name="message" rows="6" placeholder="Write your announcement here..." maxlength="2000" required></textarea>
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label class="help-request-form__label" for="ann-file">Attachment (optional)</label>
                            <div class="help-request-form__file">
                                <span class="help-request-form__file-icon"><i data-lucide="upload-cloud"></i></span>
                                <span class="help-request-form__file-text" id="ann-file-text">Click to choose a file</span>
                                <span class="help-request-form__file-meta">Images &amp; PDF only</span>
                                <input id="ann-file" name="attachment" type="file" accept="image/*,application/pdf" aria-label="Attachment" onchange="var n=this.files[0]?this.files[0].name:'Click to choose a file'; document.getElementById('ann-file-text').textContent=n;">
                            </div>
                        </div>
                    </div>
                    <div class="admin-form-actions" style="margin-top: 14px;">
                        <button type="submit" name="send_announcement" class="admin-btn"><i data-lucide="send"></i> Send to All Users</button>
                    </div>
                </form>
            </div>

            <div class="admin-section">
                <h2>Sent Announcements</h2>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Sent</th>
                                <th>Users</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($announcements as $ann)
                                <tr>
                                    <td>{{ $ann['id'] ?? '—' }}</td>
                                    <td>{{ $ann['subject'] ?? '—' }}</td>
                                    <td>{{ $ann['sent_count'] ?? 0 }}</td>
                                    <td>{{ $ann['total_users'] ?? 0 }}</td>
                                    <td>{{ isset($ann['created_at']) ? \Carbon\Carbon::parse($ann['created_at'])->format('M d, Y H:i') : '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" style="text-align:center;color:var(--muted);">No announcements sent yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

@if (!$monthlyRevenue->isEmpty())
<script>
    (function() {
        var ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        var labels = [@foreach ($monthlyRevenue as $m)'{{ \Carbon\Carbon::parse($m->month . "-01")->format("M Y") }}',@endforeach];
        var data = [@foreach ($monthlyRevenue as $m){{ (float) $m->revenue }},@endforeach];
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: data,
                    backgroundColor: 'rgba(192, 107, 0, 0.7)',
                    borderColor: 'rgba(192, 107, 0, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    })();
</script>
@endif

<script>
(function() {
    // Panel toggles (Add New Product / Manage Categories)
    var panelToggles = document.querySelectorAll('[data-admin-toggle]');
    panelToggles.forEach(function(btn) {
        var contentId = btn.getAttribute('aria-controls');
        var content = document.getElementById(contentId);
        if (!content) return;

        var isExpanded = btn.getAttribute('aria-expanded') === 'true';
        content.hidden = !isExpanded;
        if (isExpanded) {
            content.classList.add('is-open');
            content.style.maxHeight = 'none';
        } else {
            content.style.maxHeight = '0px';
        }

        btn.addEventListener('click', function() {
            var shouldOpen = btn.getAttribute('aria-expanded') !== 'true';
            btn.setAttribute('aria-expanded', String(shouldOpen));

            if (shouldOpen) {
                content.hidden = false;
                content.style.maxHeight = '0px';
                content.classList.add('is-open');
                requestAnimationFrame(function() {
                    content.style.maxHeight = content.scrollHeight + 'px';
                });
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                requestAnimationFrame(function() {
                    content.classList.remove('is-open');
                    content.style.maxHeight = '0px';
                });
            }
        });

        content.addEventListener('transitionend', function(e) {
            if (e.propertyName !== 'max-height') return;
            var open = btn.getAttribute('aria-expanded') === 'true';
            if (open) {
                content.style.maxHeight = 'none';
            } else {
                content.hidden = true;
            }
        });
    });

    // Custom select dropdowns (same UX as shop selects)
    var selectControls = document.querySelectorAll('[data-admin-select]');
    selectControls.forEach(function(control) {
        var toggle = control.querySelector('[data-admin-select-toggle]');
        var menu = control.querySelector('[data-admin-select-menu]');
        var options = control.querySelectorAll('[data-admin-select-option]');
        var current = control.querySelector('[data-admin-select-current]');
        var input = control.querySelector('[data-admin-select-input]');
        var autoSubmit = control.hasAttribute('data-admin-auto-submit');
        if (!toggle || !menu || !options.length || !current) return;

        function setOpen(isOpen) {
            toggle.setAttribute('aria-expanded', String(isOpen));
            control.classList.toggle('is-open', isOpen);
        }

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = toggle.getAttribute('aria-expanded') === 'true';
            // Close all other selects first
            selectControls.forEach(function(other) {
                if (other === control) return;
                var otherToggle = other.querySelector('[data-admin-select-toggle]');
                if (otherToggle && otherToggle.getAttribute('aria-expanded') === 'true') {
                    other.classList.remove('is-open');
                    otherToggle.setAttribute('aria-expanded', 'false');
                }
            });
            setOpen(!isOpen);
        });

        options.forEach(function(opt) {
            opt.addEventListener('click', function(e) {
                e.stopPropagation();
                var val = opt.getAttribute('data-value');
                var label = opt.textContent;

                options.forEach(function(o) {
                    o.classList.remove('is-selected');
                    o.setAttribute('aria-selected', 'false');
                });
                opt.classList.add('is-selected');
                opt.setAttribute('aria-selected', 'true');

                current.textContent = label;
                if (input) input.value = val;

                // Update hidden form inputs for filter selects
                var controlId = toggle.getAttribute('aria-controls');
                if (controlId === 'admin-cat-menu') {
                    var catInput = document.getElementById('admin-category-input');
                    if (catInput) catInput.value = val;
                } else if (controlId === 'admin-sort-menu') {
                    var sortInput = document.getElementById('admin-sort-input');
                    if (sortInput) sortInput.value = val;
                } else if (controlId === 'prod-cat-menu') {
                    var prodCatInput = document.getElementById('prod-category');
                    if (prodCatInput) prodCatInput.value = val;
                }

                setOpen(false);

                if (autoSubmit) {
                    var form = control.closest('form');
                    if (form) form.submit();
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (!control.contains(e.target)) {
                setOpen(false);
            }
        });
    });
})();
</script>

</body>
</html>
