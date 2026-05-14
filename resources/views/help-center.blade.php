@extends('layouts.app')

@section('title', 'Help Center | The DS')
@section('body_class', 'help-page')

@section('content')
    <main class="help-main">
        <section class="help-hero" aria-labelledby="help-hero-heading">
            <div class="help-hero-copy">
                <p class="pixel-note"><span>Home / Help /</span> Support</p>
                <h1 id="help-hero-heading">Help <span>Center</span></h1>
                <p class="help-hero-lead">Find answers to common questions below, or chat with our AI assistant for instant support.</p>
                <a href="#faq" class="help-hero-cta">
                    Browse FAQs
                    <i data-lucide="arrow-down" aria-hidden="true"></i>
                </a>
            </div>
            <figure class="help-hero-model">
                <img src="https://parkaccess.com.ph/cdn/shop/files/AURORA_FD2596-602_PHSLH000-2000.png?v=1770200188" alt="Customer support illustration">
            </figure>
        </section>

        <section class="brand-ticker help-brand-ticker" aria-label="Featured brands">
            <div class="brand-track">
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($helpTickerBrands as $brand)
                        <span>{{ $brand }}</span>
                    @endforeach
                @endfor
            </div>
        </section>

        <section class="help-contact" aria-label="Contact options">
            <div class="help-contact-grid">
                <article class="help-contact-card">
                    <span class="help-contact-icon"><i data-lucide="bot"></i></span>
                    <h3>AI Chat</h3>
                    <p>Get instant answers 24/7 from our AI assistant.</p>
                    <a href="#chat">Start Chatting <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </article>
                <article class="help-contact-card">
                    <span class="help-contact-icon"><i data-lucide="mail"></i></span>
                    <h3>Email Us</h3>
                    <p>thedaservice@store.com</p>
                    <a href="mailto:thedaservice@store.com">Send Email <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </article>
                <article class="help-contact-card">
                    <span class="help-contact-icon"><i data-lucide="phone"></i></span>
                    <h3>Call Us</h3>
                    <p>+855 112 233</p>
                    <a href="tel:+855112233">Call Now <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </article>
            </div>
        </section>

        <section class="help-faq" id="faq" aria-labelledby="faq-heading">
            <div class="help-faq__intro">
                <h2 id="faq-heading">Frequently Asked Questions</h2>
                <p class="pixel-note">Quick answers to the most common topics.</p>
            </div>

            <div class="help-faq-search">
                <span class="help-faq-search__icon"><i data-lucide="search"></i></span>
                <input type="search" id="faq-search" placeholder="Search questions..." aria-label="Search FAQs">
            </div>

            <div class="help-faq-list">
                @foreach ($helpFaqs as $index => $faq)
                    <article class="help-faq-item">
                        <button
                            class="help-faq-toggle"
                            type="button"
                            data-product-toggle
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="faq-panel-{{ $index }}"
                        >
                            <span class="help-faq-toggle__num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="help-faq-toggle__text">{{ $faq['question'] }}</span>
                            <i data-lucide="chevron-down" aria-hidden="true"></i>
                        </button>
                        <div
                            class="help-faq-content"
                            id="faq-panel-{{ $index }}"
                            data-product-content
                            {!! $index !== 0 ? 'hidden' : '' !!}
                        >
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            <p class="help-faq-empty" data-faq-empty hidden>No questions match your search.</p>
        </section>

        <section class="help-chat" id="chat" aria-labelledby="chat-heading">
            <div class="help-chat__card">
                <div class="help-chat__sidebar">
                    <div class="help-chat__brand">
                        <span class="help-chat__brand-icon"><i data-lucide="bot"></i></span>
                        <div>
                            <h2 id="chat-heading">AI Support</h2>
                            <p>Powered by DS</p>
                        </div>
                    </div>
                    <div class="help-chat__info">
                        <p>Our AI assistant can help with orders, shipping, returns, sizing, and product questions.</p>
                        <ul>
                            <li><i data-lucide="check-circle-2"></i> Instant replies</li>
                            <li><i data-lucide="check-circle-2"></i> 24/7 available</li>
                            <li><i data-lucide="check-circle-2"></i> Secure &amp; private</li>
                        </ul>
                    </div>
                    <div class="help-chat__status">
                        <span class="help-chat__dot"></span> Online now
                    </div>
                </div>
                <div class="help-chat__body">
                    <div class="help-chat__window" data-help-chat-window>
                        <div class="help-chat__messages" data-help-chat-messages>
                            <div class="help-chat__message help-chat__message--assistant">
                                <span class="help-chat__avatar"><i data-lucide="bot"></i></span>
                                <div class="help-chat__bubble">
                                    <p>Hi there! I am The DS AI assistant. How can I help you today?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="help-chat__input" data-help-chat-form>
                        @csrf
                        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                        <input
                            type="text"
                            data-help-chat-input
                            placeholder="Type your question..."
                            aria-label="Chat message"
                            autocomplete="off"
                            maxlength="500"
                        >
                        <button type="submit" aria-label="Send message">
                            <i data-lucide="send"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="help-cta" aria-labelledby="help-cta-heading">
            <div class="help-cta-card">
                <h2 id="help-cta-heading">Still need help?</h2>
                <p>Our human support team is ready to assist you with anything the AI could not resolve.</p>
                <div class="help-cta-actions">
                    <a href="mailto:thedaservice@store.com" class="help-cta-btn help-cta-btn--primary">
                        <i data-lucide="mail"></i> Email Support
                    </a>
                    <a href="tel:+855112233" class="help-cta-btn">
                        <i data-lucide="phone"></i> Call Us
                    </a>
                    <button type="button" class="help-cta-btn" data-help-request-btn>
                        <i data-lucide="file-text"></i> Personal Request
                    </button>
                </div>
            </div>
        </section>
    </main>

    <!-- Personal Request Modal -->
    <div class="help-request-overlay" id="help-request-overlay" aria-hidden="true">
        <div class="help-request-modal" role="dialog" aria-modal="true" aria-labelledby="help-request-title">
            <button type="button" class="help-request-modal__close" id="help-request-close" aria-label="Close">
                <i data-lucide="x"></i>
            </button>
            <h2 id="help-request-title">Personal Request</h2>
            <p class="help-request-modal__sub">Describe your issue and attach a file if needed. We will reply via email.</p>

            @if ($requestSuccess)
                <div class="help-request-alert help-request-alert--success">
                    <i data-lucide="check-circle"></i>
                    <span>Your request has been submitted successfully.</span>
                </div>
            @elseif ($requestError !== '')
                <div class="help-request-alert help-request-alert--error">
                    <i data-lucide="alert-circle"></i>
                    <span>{{ $requestError }}</span>
                </div>
            @endif

            <form method="post" action="{{ route('help-center') }}" enctype="multipart/form-data" class="help-request-form">
                @csrf
                <input type="hidden" name="personal_request" value="1">
                @php
                    $prefillEmail = old('request_email', Auth::user()?->email ?? '');
                @endphp
                <div class="help-request-group">
                    <label for="request-email">Email</label>
                    <input type="email" id="request-email" name="request_email" placeholder="you@example.com" required
                        value="{{ $prefillEmail }}">
                </div>
                <div class="help-request-group">
                    <label for="request-phone">Phone Number</label>
                    <input type="tel" id="request-phone" name="request_phone" placeholder="+855 112 233" required
                        value="{{ old('request_phone', '') }}">
                </div>
                <div class="help-request-group">
                    <label for="request-text">Your Request</label>
                    <textarea id="request-text" name="request_text" rows="5" placeholder="Describe what you need help with..." required>{{ old('request_text', '') }}</textarea>
                </div>
                <div class="help-request-group">
                    <label for="request-file">Attachment (optional)</label>
                    <div class="help-request-file">
                        <input type="file" id="request-file" name="request_file" accept="image/*,.pdf,.txt" class="help-request-file__input" onchange="var n=this.files[0]?this.files[0].name:'No file chosen'; document.getElementById('request-file-name').textContent=n; document.getElementById('request-file-name').classList.toggle('is-empty', !this.files.length);">
                        <label for="request-file" class="help-request-file__btn">
                            <i data-lucide="upload-cloud"></i> Choose File
                        </label>
                        <span id="request-file-name" class="help-request-file__name is-empty">No file chosen</span>
                    </div>
                    <span class="help-request-hint">Max 5MB. Images, PDF, or text files accepted.</span>
                </div>
                <button type="submit" class="help-request-submit">
                    <i data-lucide="send"></i> Submit Request
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var $form = $('[data-help-chat-form]');
    var $input = $('[data-help-chat-input]');
    var $messages = $('[data-help-chat-messages]');
    var $window = $('[data-help-chat-window]');
    var csrfToken = $form.find('input[name="csrf_token"]').val();

    var scrollToBottom = function () {
        if ($window.length) {
            $window[0].scrollTop = $window[0].scrollHeight;
        }
    };

    var escapeHtml = function (text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    };

    var appendMessage = function (role, text) {
        var isUser = role === 'user';
        var icon = isUser ? 'user-round' : 'bot';
        var html = '<div class="help-chat__message help-chat__message--' + role + '">' +
            '<span class="help-chat__avatar"><i data-lucide="' + icon + '"></i></span>' +
            '<div class="help-chat__bubble"><p>' + escapeHtml(text) + '</p></div></div>';
        $messages.append(html);
        if (window.lucide) {
            window.lucide.createIcons();
        }
        scrollToBottom();
    };

    var setLoading = function (isLoading) {
        $form.toggleClass('is-loading', isLoading);
        $input.prop('disabled', isLoading);
        if (isLoading) {
            var typingHtml = '<div class="help-chat__message help-chat__message--assistant" data-typing-indicator>' +
                '<span class="help-chat__avatar"><i data-lucide="bot"></i></span>' +
                '<div class="help-chat__bubble">' +
                '<span class="help-chat__typing"><span></span><span></span><span></span></span>' +
                '</div></div>';
            $messages.append(typingHtml);
            if (window.lucide) {
                window.lucide.createIcons();
            }
            scrollToBottom();
        } else {
            $('[data-typing-indicator]').remove();
        }
    };

    $form.on('submit', function (event) {
        event.preventDefault();
        var message = $input.val().trim();
        if (!message) return;

        appendMessage('user', message);
        $input.val('');
        setLoading(true);

        $.ajax({
            url: '{{ url('/chat-api') }}',
            type: 'POST',
            data: { message: message, csrf_token: csrfToken },
            dataType: 'json',
        }).done(function (response) {
            setLoading(false);
            if (response.reply) {
                appendMessage('assistant', response.reply);
            } else if (response.error) {
                appendMessage('assistant', 'Sorry: ' + response.error);
            }
        }).fail(function (xhr) {
            setLoading(false);
            var errorText = 'Something went wrong. Please try again later.';
            try {
                var resp = JSON.parse(xhr.responseText);
                if (resp.error) errorText = resp.error;
            } catch (e) {}
            appendMessage('assistant', 'Sorry: ' + errorText);
        });
    });

    scrollToBottom();

    // FAQ search
    var $faqSearch = $('#faq-search');
    var $faqItems = $('.help-faq-item');
    var $faqEmpty = $('[data-faq-empty]');

    $faqSearch.on('input', function () {
        var query = $(this).val().trim().toLowerCase();
        var visibleCount = 0;
        $faqItems.each(function () {
            var $item = $(this);
            var text = $item.text().toLowerCase();
            var matches = query === '' || text.includes(query);
            $item.toggleClass('is-hidden', !matches);
            if (matches) visibleCount++;
        });
        $faqEmpty.prop('hidden', visibleCount > 0);
    });

    // Personal request modal
    var $requestOverlay = $('#help-request-overlay');
    var $requestBtn = $('[data-help-request-btn]');
    var $requestClose = $('#help-request-close');

    function openRequestModal() {
        $requestOverlay.addClass('is-open');
        $requestOverlay.attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function closeRequestModal() {
        $requestOverlay.removeClass('is-open');
        $requestOverlay.attr('aria-hidden', 'true');
        $('body').css('overflow', '');
    }

    $requestBtn.on('click', openRequestModal);
    $requestClose.on('click', closeRequestModal);

    $requestOverlay.on('click', function (e) {
        if (e.target === $requestOverlay[0]) {
            closeRequestModal();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $requestOverlay.hasClass('is-open')) {
            closeRequestModal();
        }
    });

    @if ($requestSuccess || $requestError !== '')
        openRequestModal();
    @endif
})();
</script>
@endpush
