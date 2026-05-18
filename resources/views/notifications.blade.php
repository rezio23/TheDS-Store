@extends('layouts.app')

@section('title', 'Notifications | The DS')
@section('body_class', 'notifications-page')

@section('content')
    <main class="notifications-main">
        <nav class="notifications-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}#home">Home</a>
            <span>/</span>
            <span aria-current="page">Notifications</span>
        </nav>

        <div class="notifications-header">
            <div class="notifications-title-wrap">
                <h1>Notifications</h1>
                @php $unreadCount = $notifications->whereNull('read_at')->count(); @endphp
                @if ($unreadCount > 0)
                    <span class="notifications-unread-badge">{{ $unreadCount }} unread</span>
                @endif
            </div>
            @if ($notifications->count())
                <button type="button" class="notifications-mark-all" data-page-mark-all>Mark all as read</button>
            @endif
        </div>

        @if ($notifications->count())
            <div class="notifications-filters" role="tablist" aria-label="Notification filters">
                <button type="button" class="notifications-filter is-active" data-filter="all" role="tab" aria-selected="true">
                    All
                    <span class="notifications-filter-count">{{ $notifications->count() }}</span>
                </button>
                <button type="button" class="notifications-filter" data-filter="unread" role="tab" aria-selected="false">
                    Unread
                    <span class="notifications-filter-count">{{ $unreadCount }}</span>
                </button>
            </div>
        @endif

        <div class="notifications-list">
            @forelse ($notifications as $notification)
                @php
                    $typeIcon = match($notification->type) {
                        'order' => 'package',
                        'promo', 'promotion' => 'tag',
                        'message' => 'mail',
                        'alert', 'warning' => 'alert-triangle',
                        'success' => 'check-circle',
                        default => 'bell',
                    };
                @endphp
                <div class="notifications-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}" data-read="{{ $notification->read_at ? '1' : '0' }}">
                    <div class="notifications-item__icon">
                        <i data-lucide="{{ $typeIcon }}"></i>
                    </div>
                    <div class="notifications-item__body">
                        <div class="notifications-item__top">
                            <span class="notifications-item__title">{{ $notification->title }}</span>
                            <span class="notifications-item__time">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if ($notification->message)
                            <p class="notifications-item__message">{{ Str::limit($notification->message, 120) }}</p>
                        @endif
                    </div>
                    <button
                        type="button"
                        class="notifications-item__action"
                        data-notif-view
                        data-notif-id="{{ $notification->id }}"
                        data-notif-title="{{ htmlspecialchars($notification->title, ENT_QUOTES, 'UTF-8') }}"
                        data-notif-message="{{ htmlspecialchars($notification->message ?? '', ENT_QUOTES, 'UTF-8') }}"
                        data-notif-time="{{ $notification->created_at->format('M d, Y \a\t h:i A') }}"
                        data-notif-read="{{ $notification->read_at ? '1' : '0' }}"
                        data-notif-image="{{ $notification->image ? asset('storage/' . $notification->image) : '' }}"
                    >
                        <i data-lucide="eye"></i>
                        <span>View</span>
                    </button>
                </div>
            @empty
                <div class="notifications-empty">
                    <div class="notifications-empty__icon">
                        <i data-lucide="bell-off"></i>
                    </div>
                    <h2>No notifications yet</h2>
                    <p>When you receive updates about orders, promotions, or messages, they'll appear here.</p>
                </div>
            @endforelse
        </div>
    </main>

    <div id="notif-modal" class="profile-notification-overlay" aria-hidden="true">
        <div class="profile-notification-modal" role="dialog" aria-modal="true" aria-labelledby="notif-modal-title">
            <button type="button" class="profile-notification-modal__close" onclick="document.getElementById('notif-modal').classList.remove('is-open');document.getElementById('notif-modal').setAttribute('aria-hidden','true');">
                <i data-lucide="x"></i>
            </button>

            <div class="profile-notification-modal__head">
                <span class="profile-notification-modal__meta">Notification</span>
                <h2 id="notif-modal-title"></h2>
            </div>

            <div class="profile-notification-modal__body">
                <div class="profile-notification-meta-grid">
                    <div class="meta-item">
                        <span>ID</span>
                        <strong id="notif-modal-id"></strong>
                    </div>
                    <div class="meta-item">
                        <span>Status</span>
                        <strong id="notif-modal-status"></strong>
                    </div>
                    <div class="meta-item">
                        <span>Date</span>
                        <strong id="notif-modal-time"></strong>
                    </div>
                </div>

                <div class="profile-notification-field">
                    <span class="profile-notification-field__label">Message</span>
                    <div id="notif-modal-message" class="profile-notification-field__value"></div>
                </div>

                <div id="notif-modal-file-wrap" class="profile-notification-field" style="display:none;">
                    <span class="profile-notification-field__label">Attachment</span>
                    <div id="notif-modal-file"></div>
                </div>
            </div>

            <div class="profile-notification-modal__foot">
                <button type="button" class="profile-notification-close-btn" onclick="document.getElementById('notif-modal').classList.remove('is-open');document.getElementById('notif-modal').setAttribute('aria-hidden','true');">Close</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var modal = document.getElementById('notif-modal');

            function closeNotifModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeNotifModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeNotifModal();
                }
            });

            document.querySelectorAll('[data-notif-view]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = btn.getAttribute('data-notif-id');
                    var title = btn.getAttribute('data-notif-title');
                    var message = btn.getAttribute('data-notif-message');
                    var time = btn.getAttribute('data-notif-time');
                    var read = btn.getAttribute('data-notif-read');
                    var image = btn.getAttribute('data-notif-image');

                    document.getElementById('notif-modal-id').textContent = '#' + id;
                    document.getElementById('notif-modal-title').textContent = title;
                    document.getElementById('notif-modal-message').textContent = message || 'No message.';
                    document.getElementById('notif-modal-time').textContent = time;
                    document.getElementById('notif-modal-status').textContent = read === '1' ? 'Read' : 'Unread';
                    document.getElementById('notif-modal-status').style.color = read === '1' ? '#2a9d8f' : '#e63946';

                    var fileWrap = document.getElementById('notif-modal-file-wrap');
                    var fileBox = document.getElementById('notif-modal-file');
                    if (image) {
                        fileWrap.style.display = 'block';
                        fileBox.innerHTML = '<a href="' + image + '" target="_blank" class="profile-notification-attachment"><img src="' + image + '" alt="Attachment" style="max-width:100%;height:auto;display:block;border-radius:10px;" onerror="this.parentElement.outerHTML = \'<div class=\\\'admin-chart-empty\\\' style=\\\'height:auto;padding:40px 20px;\\\'>Image failed to load.</div>\'"></a>';
                    } else {
                        fileWrap.style.display = 'none';
                        fileBox.innerHTML = '';
                    }

                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');

                    // Mark as read via AJAX if unread
                    if (read !== '1') {
                        fetch('/notifications/' + id + '/read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        }).then(function(res) {
                            if (res.ok) {
                                btn.setAttribute('data-notif-read', '1');
                                var item = btn.closest('.notifications-item');
                                if (item) {
                                    item.classList.remove('is-unread');
                                    item.classList.add('is-read');
                                    item.setAttribute('data-read', '1');
                                }
                                // Update global notification count in navbar if it exists
                                var countBadge = document.querySelector('[data-notification-count]');
                                if (countBadge) {
                                    var current = parseInt(countBadge.textContent || '0', 10);
                                    if (current > 0) countBadge.textContent = current - 1;
                                    if (parseInt(countBadge.textContent || '0', 10) === 0) countBadge.hidden = true;
                                }
                                // Update unread filter count
                                var unreadFilterCount = document.querySelector('[data-filter="unread"] .notifications-filter-count');
                                if (unreadFilterCount) {
                                    var unreadVal = parseInt(unreadFilterCount.textContent || '0', 10);
                                    if (unreadVal > 0) unreadFilterCount.textContent = unreadVal - 1;
                                }
                            }
                        }).catch(function() {});
                    }
                });
            });

            // Mark all as read on this page
            var markAllBtn = document.querySelector('[data-page-mark-all]');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function() {
                    fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }).then(function(res) {
                        if (res.ok) {
                            document.querySelectorAll('.notifications-item.is-unread').forEach(function(item) {
                                item.classList.remove('is-unread');
                                item.classList.add('is-read');
                                item.setAttribute('data-read', '1');
                            });
                            document.querySelectorAll('[data-notif-view]').forEach(function(btn) {
                                btn.setAttribute('data-notif-read', '1');
                            });
                            var countBadge = document.querySelector('[data-notification-count]');
                            if (countBadge) {
                                countBadge.textContent = '0';
                                countBadge.hidden = true;
                            }
                            var unreadBadge = document.querySelector('.notifications-unread-badge');
                            if (unreadBadge) unreadBadge.remove();
                            var unreadFilterCount = document.querySelector('[data-filter="unread"] .notifications-filter-count');
                            if (unreadFilterCount) unreadFilterCount.textContent = '0';
                            markAllBtn.style.display = 'none';
                        }
                    }).catch(function() {});
                });
            }

            // Filter tabs
            var filterBtns = document.querySelectorAll('[data-filter]');
            filterBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var filter = btn.getAttribute('data-filter');

                    filterBtns.forEach(function(b) {
                        b.classList.remove('is-active');
                        b.setAttribute('aria-selected', 'false');
                    });
                    btn.classList.add('is-active');
                    btn.setAttribute('aria-selected', 'true');

                    document.querySelectorAll('.notifications-item').forEach(function(item) {
                        if (filter === 'all') {
                            item.style.display = '';
                        } else if (filter === 'unread') {
                            item.style.display = item.getAttribute('data-read') === '0' ? '' : 'none';
                        }
                    });
                });
            });
        })();
    </script>
@endsection
