@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Manage Staff Accounts</h1>
    <p>Create and manage your team's access</p>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="margin-bottom: 20px;">{{ session('error') }}</div>
@endif

<div class="card" style="margin-bottom: 32px;">
    <div class="card-header">
        <h2>Create New Staff Account</h2>
        <p>New staff members will receive their login credentials from you directly.</p>
    </div>
    <div style="padding: 24px;">
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Temporary Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 characters">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div style="margin-top: 16px;">
                <button type="submit" class="btn">Create Staff Account</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Staff List</h2>
        <p>Toggle access for staff accounts</p>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $user)
                <tr id="user-row-{{ $user->id }}">
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="status-badge {{ $user->is_active ? 'status-accepted' : 'status-denied' }}">
                            {{ $user->is_active ? 'Active' : 'Deactivated' }}
                        </span>
                    </td>
                    <td>
                        <button
                            class="btn btn-toggle-active"
                            data-id="{{ $user->id }}"
                            style="background: {{ $user->is_active ? '#dc3545' : '#10B981' }}; color: white; padding: 5px 10px; font-size: 12px;">
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6b7280;">No staff accounts yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-toggle-active').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            fetch(`/users/${userId}/toggle-active`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(r => r.json())
            .then(data => { if (data.success) location.reload(); });
        });
    });
</script>
@endsection