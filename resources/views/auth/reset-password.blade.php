@extends('layout.app')

<main class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="card-title text-center mb-3">Reset Password</h3>

        {{-- Error message --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="Your email" required>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="New Password" required>
                <span class="position-absolute" style="top: 38px; right: 10px; cursor: pointer;" onclick="togglePassword('password')">
                    👁️
                </span>
            </div>

            <div class="mb-3 position-relative">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password" required>
                <span class="position-absolute" style="top: 38px; right: 10px; cursor: pointer;" onclick="togglePassword('password_confirmation')">
                    👁️
                </span>
            </div>

            <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>
    </div>
</main>

{{-- Show/Hide Password Script --}}
<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>
