@extends('layouts.app')

@section('auth-content')
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-container">
            <h1 class="auth-title">Set New Password</h1>
            <p class="auth-subtitle">Enter your new password</p>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-block">Reset Password</button>
            </form>

            <div class="auth-link">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
    <div class="auth-right"></div>
</div>
@endsection