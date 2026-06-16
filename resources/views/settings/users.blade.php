@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Manage Staff Accounts</h1>
    <p>Activate or deactivate staff members</p>
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
                @foreach($staff as $user)
                <tr id="user-row-{{ $user->id }}">
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="status-badge {{ $user->is_active ? 'status-accepted' : 'status-denied' }}" id="status-text-{{ $user->id }}">
                            {{ $user->is_active ? 'Active' : 'Deactivated' }}
                        </span>
                    </td>
                    <td>
                        <button 
                            class="btn btn-toggle-active" 
                            data-id="{{ $user->id }}"
                            style="background: {{ $user->is_active ? '#dc3545' : '#10B981' }}; color: white; padding: 5px 10px; font-size: 12px;"
                        >
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-toggle-active').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');

            fetch(`/users/${userId}/toggle-active`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });
</script>
@endsection