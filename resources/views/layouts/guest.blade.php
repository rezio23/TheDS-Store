@extends('layouts.app')

@section('title', $title ?? 'The DS')

@section('content')
    <main class="auth-page">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </main>
@endsection
