@extends('layouts.app')

@section('auth-content')
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-container">
            <div class="auth-logo">
    <img src="/images/logo.png" alt="Techflow Logo">
</div>
<h3 class="auth-title">TECHFLOW</h3>
            <p class="auth-subtitle">Lead Management System</p>

            <div class="role-selector">
                <div class="role-option active" onclick="selectLoginRole('admin', this)">ADMIN</div>
                <div class="role-option" onclick="selectLoginRole('staff', this)">STAFF</div>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <input type="hidden" name="role" id="user_role" value="admin">

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-block">LOGIN</button>
            </form>

            <div id="staff-only-links" style="display: none;">
                <div class="forgot-password-link">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
                <div class="auth-link">
                    <a href="/register">CREATE NEW ACCOUNT</a>
                </div>
            </div>

        </div>
    </div>
    <div class="auth-right"></div>
</div>

<script>
    function selectLoginRole(role, el) {
        document.querySelectorAll('.role-option').forEach(opt => {
            opt.classList.remove('active');
        });
        el.classList.add('active');
        document.getElementById('user_role').value = role;
        document.getElementById('staff-only-links').style.display = (role === 'staff') ? 'block' : 'none';
    }
</script>
@endsection