@extends('layouts.app')

@section('content')

<div class="welcome-banner">
    <div class="welcome-banner-text">
        <h2>Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h2>
        <p>Your lead management overview</p>
    </div>
    <div class="welcome-avatar">
        {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
    </div>
</div>

@if($staleLeads->count() > 0)
<div class="card" style="border-color: rgba(239, 68, 68, 0.4); background: rgba(239, 68, 68, 0.05); margin-bottom: 24px;">
    <div class="card-header">
        <h2 style="color: #EF4444;">⚠️ Leads Needing Attention</h2>
        <p>{{ $staleLeads->count() }} of your lead(s) have been inactive for 3+ days</p>
    </div>

    <div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Status</th>
                <th>Days Inactive</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staleLeads as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->company }}</td>
                <td>
                    <span class="status-badge status-{{ $lead->status }}">
                        {{ ucfirst($lead->status) }}
                    </span>
                </td>
                <td style="color: #EF4444; font-weight: 600;">{{ $lead->daysSinceUpdate() }} days</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endif

<div class="mini-stats-grid">
    <div class="mini-stat-card" style="border-left-color: #3B82F6;">
        <i data-lucide="users" style="color:#3B82F6; width:18px; height:18px;"></i>
        <div class="mini-stat-number">{{ $totalLeads }}</div>
        <div class="mini-stat-label">Assigned</div>
    </div>
    <div class="mini-stat-card" style="border-left-color: #F59E0B;">
        <i data-lucide="clock" style="color:#F59E0B; width:18px; height:18px;"></i>
        <div class="mini-stat-number">{{ $contactedLeads }}</div>
        <div class="mini-stat-label">Contacted</div>
    </div>
    <div class="mini-stat-card" style="border-left-color: #10B981;">
        <i data-lucide="check" style="color:#10B981; width:18px; height:18px;"></i>
        <div class="mini-stat-number">{{ $completedLeads }}</div>
        <div class="mini-stat-label">Accepted</div>
    </div>
    <div class="mini-stat-card" style="border-left-color: #EF4444;">
        <i data-lucide="x" style="color:#EF4444; width:18px; height:18px;"></i>
        <div class="mini-stat-number">{{ $deniedLeads }}</div>
        <div class="mini-stat-label">Denied</div>
    </div>
</div>

<div class="dashboard-grid">

    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <h2>Lead Breakdown</h2>
            <p>Your current pipeline</p>
        </div>
        <div style="padding: 0 24px 24px;">
            <p style="color: var(--text-muted); font-size: 13px;">New leads</p>
            <p style="color: var(--text-light); font-size: 28px; font-weight: 700; margin-bottom: 16px;">{{ $newLeads }}</p>
            <p style="color: var(--text-muted); font-size: 13px;">You have {{ $totalLeads }} total leads assigned to you, with {{ $contactedLeads }} currently in progress.</p>
        </div>
    </div>

    <div class="activity-feed-card">
        <p class="activity-feed-title">Recent Activity</p>

        @forelse($recentActivity as $activity)
        <div class="activity-item">
            <i data-lucide="circle-dot" style="width:16px; height:16px; color: var(--primary-yellow); margin-top:2px; flex-shrink:0;"></i>
            <div class="activity-item-text">
                <p>{{ $activity->description }}</p>
                <span class="activity-item-time">{{ $activity->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <p style="color: var(--text-muted); font-size: 13px;">No recent activity yet.</p>
        @endforelse
    </div>

</div>

@endsection