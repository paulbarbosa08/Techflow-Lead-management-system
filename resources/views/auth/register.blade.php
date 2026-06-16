@extends('layouts.app')

@section('auth-content')
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-container">
            <h1 class="auth-title">CREATE NEW ACCOUNT</h1>

            <div class="role-selector">
                <div class="role-option" onclick="selectLoginRole('admin', this)">ADMIN</div>
                <div class="role-option active" onclick="selectLoginRole('staff', this)">STAFF</div>
            </div>

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" id="user_role" value="staff">

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Create Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the Terms and Services and Privacy Policy</label>
                </div>

                <button type="submit" class="btn btn-block">REGISTER</button>
            </form>

            <div class="auth-link">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </div>
    </div>
    <div class="auth-right"></div>
</div>

<script>
    function selectLoginRole(role, el) {
        document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('user_role').value = role;
    }
</script>
@endsection