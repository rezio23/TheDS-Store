@extends('layouts.app')

@section('content')
    <main class="auth-page">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </main>
@endsection
