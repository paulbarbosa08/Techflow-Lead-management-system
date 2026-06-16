@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>MY LEADS</h2>
        <p><strong>View Assigned Leads</strong></p>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->company }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ $lead->phone }}</td>
                <td>
                    <select onchange="updateLeadStatus({{ $lead->id }}, this.value)" class="form-control" style="width: auto;">
                        <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ $lead->status == 'qualified' ? 'selected' : '' }}>Qualified</option>
                    </select>
                </td>
                <td>{{ $lead->date->format('m-d-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection