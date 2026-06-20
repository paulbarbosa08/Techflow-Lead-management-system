@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Update Leads</h1>
    <p>Update the status of your assigned leads</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Update Lead Status</h2>
        <p>Change the status of your leads</p>
    </div>

    <div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Product</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Date</th>
                <th>Update</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->company }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ $lead->phone }}</td>
                <td>{{ $lead->product ?? '-' }}</td>
                <td>
                    <select class="form-control status-select" data-id="{{ $lead->id }}">
                        @if($lead->status == 'new')
                            <option value="new" selected disabled>New (not yet contacted)</option>
                        @endif
                        <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="accepted" {{ $lead->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="denied" {{ $lead->status == 'denied' ? 'selected' : '' }}>Denied</option>
                    </select>
                </td>
                <td>
                    <span class="priority-badge priority-{{ $lead->priority }}">
                        {{ ucfirst($lead->priority) }}
                    </span>
                </td>
                <td>{{ $lead->date->format('m-d-Y') }}</td>
                <td>
                    <button class="btn btn-update" data-id="{{ $lead->id }}" style="background: #F9B933; color: #2D2D2D; padding: 5px 10px; font-size: 12px;">
                        💾 Update
                    </button>
                </td>
                <td>
                    <a href="{{ route('leads.activity', $lead->id) }}" class="btn" style="background: #3B82F6; color: white; padding: 5px 10px; font-size: 12px; display: inline-block; margin-bottom: 4px;">
                        📝 View
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ urlencode($lead->email) }}&su={{ urlencode('Regarding your inquiry — ' . ($lead->product ?? 'our services')) }}" target="_blank" rel="noopener" class="btn" style="background: #EA4335; color: white; padding: 5px 10px; font-size: 12px; display: inline-block;">
                        ✉️ Email
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

<script>
    // Update lead status
    document.querySelectorAll('.btn-update').forEach(button => {
        button.addEventListener('click', function() {
            const leadId = this.getAttribute('data-id');
            const row = this.closest('tr');
            const status = row.querySelector('.status-select').value;
            
            fetch(`/leads/${leadId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Lead status updated successfully!');
                } else if (data.error) {
                    alert(data.error);
                } else {
                    alert('Something went wrong.');
                }
            });
        });
    });
</script>
@endsection