@extends('layouts.app')

@section('title', 'Edit Profile')
@section('body_class', 'edit-profile-page')

@section('content')
    <main class="edit-profile-main">
        <section class="edit-profile-section" aria-labelledby="edit-profile-heading">
            <h1 id="edit-profile-heading">Edit Profile</h1>
            <hr class="edit-form-divider">

            <form class="edit-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @if ($errors->any())
                    <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <p style="margin: 0.25rem 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="edit-form-group">
                    <label class="edit-form-label" for="full_name">Full Name</label>
                    <input class="edit-form-input" id="full_name" name="full_name" type="text" value="{{ old('full_name', $user->full_name) }}" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="email">Email</label>
                    <input class="edit-form-input" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="phone">Phone</label>
                    <input class="edit-form-input" id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="gender">Gender</label>
                    <select class="edit-form-input" id="gender" name="gender">
                        <option value="Hidden" {{ old('gender', $user->gender) === 'Hidden' ? 'selected' : '' }}>Hidden</option>
                        <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="address">Address</label>
                    <textarea class="edit-form-input" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="edit-form-actions">
                    <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Save Changes</button>
                    <a href="{{ route('profile') }}" class="edit-form-button edit-form-button--cancel">Cancel</a>
                </div>
            </form>
        </section>
    </main>
@endsection
