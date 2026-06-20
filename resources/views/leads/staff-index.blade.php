@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>My Leads</h1>
    <p>View your assigned leads</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Assigned Leads</h2>
        <p>Leads assigned to you</p>
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
                <th>Actions</th>
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
                    <span class="status-badge status-{{ $lead->status }}">
                        {{ ucfirst($lead->status) }}
                    </span>
                </td>
                <td>
                    <span class="priority-badge priority-{{ $lead->priority }}">
                        {{ ucfirst($lead->priority) }}
                    </span>
                </td>
                <td>{{ $lead->date->format('m-d-Y') }}</td>
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
@endsection