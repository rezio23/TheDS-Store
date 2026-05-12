@extends('layouts.app')

@section('title', 'Terms & Conditions')
@section('body_class', 'terms-page')

@section('content')
    <main class="terms-main">
        <div class="terms-header-row">
            <nav class="terms-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}#home">Home</a>
                <span>/</span>
                <span aria-current="page">Terms &amp; Conditions</span>
            </nav>
        </div>

        <section class="terms-hero" aria-labelledby="terms-hero-heading">
            <h1 id="terms-hero-heading">Terms &amp; <span>Conditions</span></h1>
            <p class="terms-hero-lead">Please read these terms carefully before using our website or making a purchase. By using The DS, you agree to these terms.</p>
        </section>

        <div class="terms-layout">
            <aside class="terms-sidebar" aria-label="Table of contents">
                <nav class="terms-toc">
                    <p class="terms-toc-label">Contents</p>
                    <ul>
                        @foreach ($termsSections as $index => $section)
                            <li>
                                <a href="#terms-section-{{ $index + 1 }}" class="terms-toc-link" data-section="terms-section-{{ $index + 1 }}">
                                    <span class="terms-toc-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="terms-toc-title">{{ $section['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </aside>

            <section class="terms-content" aria-label="Terms and conditions">
                <div class="terms-meta">
                    <span class="terms-updated">Last updated: May 11, 2026</span>
                </div>
                <div class="terms-card">
                    @foreach ($termsSections as $index => $section)
                        <article class="terms-section" id="terms-section-{{ $index + 1 }}">
                            <div class="terms-section-header">
                                <span class="terms-section-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h2>{{ $section['title'] }}</h2>
                            </div>
                            <p>{{ $section['content'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    var tocLinks = document.querySelectorAll('.terms-toc-link');
    var sections = document.querySelectorAll('.terms-section');
    var scrollOffset = 140;

    function setActive(id) {
        for (var i = 0; i < tocLinks.length; i++) {
            tocLinks[i].classList.toggle('is-active', tocLinks[i].dataset.section === id);
        }
    }

    function onScroll() {
        var scrollPos = window.scrollY + scrollOffset;
        var activeId = '';
        for (var i = 0; i < sections.length; i++) {
            if (sections[i].offsetTop <= scrollPos) {
                activeId = sections[i].id;
            }
        }
        if (activeId) setActive(activeId);
    }

    for (var i = 0; i < tocLinks.length; i++) {
        tocLinks[i].addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                var top = target.getBoundingClientRect().top + window.scrollY - scrollOffset;
                window.scrollTo({ top: top, behavior: 'smooth' });
                history.replaceState(null, '', this.getAttribute('href'));
                setActive(this.dataset.section);
            }
        });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();
</script>
@endpush
