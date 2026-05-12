@extends('layouts.app')

@section('body_class', 'auth-page')

@section('content')
    <main class="auth-page">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </main>
@endsection
