@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>All Leads</h1>
    <a href="{{ route('leads.create') }}" class="btn btn-primary" style="margin-top: 10px;">
        ➕ Add New Lead
    </a>
    <p>Manage and assign leads to staff members</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Lead Management</h2>
        <p>View, delete, and assign leads</p>
    </div>

    <!-- Search & Filter Bar -->
    <form method="GET" action="{{ route('leads.index') }}" class="search-filter-bar">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Search by name, company, or email..." 
            value="{{ request('search') }}"
            style="max-width: 320px;"
        >

        <select name="status" class="form-control" style="max-width: 180px;">
            <option value="">All Statuses</option>
            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
        </select>

        <button type="submit" class="btn btn-primary">🔍 Filter</button>

        @if(request('search') || request('status'))
            <a href="{{ route('leads.index') }}" class="btn" style="background: #6b7280; color: white;">Clear</a>
        @endif
    </form>
    <a href="{{ route('leads.export', request()->query()) }}" class="btn btn-primary" style="margin: 0 24px 16px;">
    📥 Export to CSV
</a>
<a href="{{ route('leads.import') }}" class="btn btn-secondary" style="margin: 0 24px 16px;">
    📤 Import CSV
</a>

    <!-- ✅ WRAP TABLE so it fits at 100% zoom -->
    <div class="table-wrapper">
        <table class="table table-compact">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 160px;">Name</th>
                    <th style="width: 160px;">Company</th>
                    <th style="width: 220px;">Email</th>
                    <th style="width: 140px;">Phone</th>
                    <th style="width: 160px;">Product</th>
                    <th style="width: 130px;">Status</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 100px;">Priority</th>
                    <th style="width: 170px;">Assigned To</th>
                    <th style="width: 120px;">Date</th>
                    <th style="width: 110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td>{{ $lead->id }}</td>
                    <td title="{{ $lead->name }}">{{ $lead->name }}</td>
                    <td title="{{ $lead->company }}">{{ $lead->company }}</td>
                    <td title="{{ $lead->email }}">{{ $lead->email }}</td>
                    <td title="{{ $lead->phone }}">{{ $lead->phone }}</td>
                    <td title="{{ $lead->product ?? '-' }}">{{ $lead->product ?? '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ $lead->status }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td>
    <span class="priority-badge priority-{{ $lead->priority }}">
        {{ ucfirst($lead->priority) }}
    </span>
</td>
                    <td>
                        <!-- ✅ compact select class -->
                        <select class="form-control assign-select assign-select-compact" data-id="{{ $lead->id }}">
                            <option value="">Unassigned</option>
                            @foreach($staff as $user)
                            <option value="{{ $user->id }}" {{ $lead->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td>{{ $lead->date->format('m-d-Y') }}</td>
                    <td>
    <a href="{{ route('leads.activity', $lead->id) }}" class="btn" style="background: #3B82F6; color: white; padding: 5px 10px; font-size: 12px; display: inline-block; margin-bottom: 4px;">
        📜 Log
    </a>
    <button class="btn btn-delete" data-id="{{ $lead->id }}" style="background: #dc3545; color: white; padding: 5px 10px; font-size: 12px;">
        🗑️ Delete
    </button>
</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Assign lead to staff
    document.querySelectorAll('.assign-select').forEach(select => {
        select.addEventListener('change', function() {
            const leadId = this.getAttribute('data-id');
            const staffId = this.value;

            fetch(`/leads/${leadId}/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ staff_id: staffId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Lead assigned successfully!');
                } else if (data.error) {
                    alert(data.error);
                }
            });
        });
    });

    // Delete lead
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Are you sure you want to delete this lead?')) return;

            const leadId = this.getAttribute('data-id');

            fetch(`/leads/${leadId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('tr').remove();
                    alert('Lead deleted successfully!');
                } else if (data.error) {
                    alert(data.error);
                }
            });
        });
    });
</script>
@endsection
