@extends('layouts.app')

@section('title', 'Help Center')
@section('body_class', 'help-center-page')

@section('content')
    <main class="help-main">
        <section class="help-section" aria-labelledby="help-heading">
            <div class="help-hero">
                <h1 id="help-heading">Help Center</h1>
                <p>How can we help you today?</p>
            </div>

            <div class="help-content">
                <article class="help-block">
                    <h2>Frequently Asked Questions</h2>
                    <dl class="help-faq">
                        <dt>How do I track my order?</dt>
                        <dd>You can track your order by logging into your account and viewing your order history.</dd>

                        <dt>What is your return policy?</dt>
                        <dd>We accept returns within 14 days of delivery. Items must be in original condition with tags attached.</dd>

                        <dt>How long does shipping take?</dt>
                        <dd>Standard shipping takes 5-7 business days. Express shipping is available for faster delivery.</dd>

                        <dt>Are all products authentic?</dt>
                        <dd>Yes, we guarantee the authenticity of every product we sell.</dd>
                    </dl>
                </article>

                <article class="help-block">
                    <h2>Contact Us</h2>
                    <p>Can't find what you're looking for? Send us a message and we'll get back to you within 24 hours.</p>

                    @if (session('success'))
                        <div class="auth-success" style="color: #2a9d8f; margin-bottom: 1rem; font-size: 0.9rem;">{{ session('success') }}</div>
                    @endif

                    <form class="help-form" method="POST" action="{{ route('help-center') }}">
                        @csrf
                        <div class="edit-form-group">
                            <label class="edit-form-label" for="subject">Subject</label>
                            <input class="edit-form-input" id="subject" name="subject" type="text" required>
                        </div>

                        <div class="edit-form-group">
                            <label class="edit-form-label" for="message">Message</label>
                            <textarea class="edit-form-input" id="message" name="message" rows="5" required></textarea>
                        </div>

                        <div class="edit-form-actions">
                            <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Send Message</button>
                        </div>
                    </form>
                </article>
            </div>
        </section>
    </main>
@endsection
