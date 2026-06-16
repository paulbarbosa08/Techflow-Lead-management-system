@extends('layouts.app')

@section('auth-content')
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-container">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your email to receive a password reset link</p>

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <button type="submit" class="btn btn-block">Send Password Reset Link</button>
            </form>

            <div class="auth-link">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
    <div class="auth-right"></div>
</div>
@endsection