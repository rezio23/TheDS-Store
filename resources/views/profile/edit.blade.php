@extends('layouts.app')

@section('title', 'Edit Profile | The DS')

@section('content')
    <main class="edit-profile-page">
        <div class="edit-profile-card">
            <h1>Edit Personal Detail</h1>
            <hr class="edit-form-divider">

                    <form class="edit-form" method="POST" action="{{ route('profile') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <p style="margin: 0.25rem 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-full-name">Full name</label>
                    <input class="edit-form-input" id="edit-full-name" name="full_name" type="text" value="{{ old('full_name', $user->full_name) }}" placeholder="e.g. John Smith">
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-username">Username</label>
                    <input class="edit-form-input" id="edit-username" name="username" type="text" value="{{ '@' . strtolower(preg_replace('/[^a-z0-9]/', '', $user->full_name)) }}" placeholder="e.g. johnsmith123">
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-gender">Gender</label>
                    <select class="edit-form-input edit-form-select" id="edit-gender" name="gender">
                        <option value="Hidden" {{ old('gender', $user->gender) === 'Hidden' ? 'selected' : '' }}>Hidden</option>
                        <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-profile-pic">Profile Picture</label>
                    <div class="edit-form-file-wrap">
                        <span class="edit-form-file-text" id="edit-file-text">Browser File</span>
                        <input id="edit-profile-pic" name="profile_picture" type="file" accept="image/*" aria-label="Profile picture">
                    </div>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-address">Address</label>
                    <input class="edit-form-input" id="edit-address" name="address" type="text" value="{{ old('address', $user->address) }}" placeholder="e.g. Toul Kork, Cambodia">
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-phone">Phone</label>
                    <input class="edit-form-input" id="edit-phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 85511 223 344">
                </div>

                <div class="edit-form-actions">
                    <a href="{{ route('profile') }}" class="edit-form-button edit-form-button--cancel" role="button">Cancel</a>
                    <button type="submit" class="edit-form-button edit-form-button--submit">Submit</button>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    const fileInput = document.getElementById('edit-profile-pic');
    const fileText = document.getElementById('edit-file-text');
    if (fileInput && fileText) {
        fileInput.addEventListener('change', () => {
            fileText.textContent = fileInput.files[0]?.name || 'Browser File';
        });
    }
</script>
@endpush
