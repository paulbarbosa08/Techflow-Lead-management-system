@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>SETTINGS</h1>
    <p><strong>Manage your account settings</strong></p>
</div>

<div class="settings-grid">
    <!-- Profile Section -->
    <div class="settings-section">
        <h3>PROFILE</h3>
        <form method="POST" action="{{ route('settings.update-profile') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>LAST NAME</label>
                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" required>
            </div>

            <div class="form-group">
                <label>FIRST NAME</label>
                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
            </div>

            <div class="form-group">
                <label>EMAIL ADDRESS</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label>ROLE</label>
                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
            </div>

            <button type="submit" class="btn">UPDATE PROFILE</button>
        </form>
    </div>

    <!-- Password Section -->
    <div class="settings-section">
        <h3>PASSWORD</h3>
        <form method="POST" action="{{ route('settings.change-password') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>CURRENT PASSWORD</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>NEW PASSWORD</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>CONFIRM NEW PASSWORD</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn">CHANGE PASSWORD</button>
        </form>
    </div>
</div>
@endsection