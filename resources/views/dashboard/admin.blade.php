@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Admin Dashboard</h1>
    <p>System overview and statistics</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>TOTAL LEADS</h3>
        <div class="stat-number">{{ $totalLeads }}</div>
    </div>
    
    <div class="stat-card">
        <h3>NEW LEADS</h3>
        <div class="stat-number">{{ $newLeads }}</div>
    </div>
    
    <div class="stat-card">
        <h3>CONTACTED</h3>
        <div class="stat-number">{{ $contactedLeads }}</div>
    </div>
    
    <div class="stat-card">
        <h3>COMPLETED LEADS</h3>
        <div class="stat-number">{{ $completedLeads }}</div>
    </div>

    <div class="stat-card">
        <h3>DENIED LEADS</h3>
        <div class="stat-number">{{ $deniedLeads }}</div>
    </div>
    
    <div class="stat-card">
        <h3>TOTAL STAFF</h3>
        <div class="stat-number">{{ $totalStaff }}</div>
    </div>
</div>

<div class="card" style="margin-top: 24px; padding: 32px; display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">
    <div style="position: relative; width: 180px; height: 180px;">
        <svg viewBox="0 0 100 100" style="width: 100%; height: 100%; transform: rotate(-90deg);">
            <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="12" />
            <circle 
                cx="50" cy="50" r="40" 
                fill="none" 
                stroke="#10B981" 
                stroke-width="12"
                stroke-dasharray="{{ 2 * 3.1416 * 40 }}"
                stroke-dashoffset="{{ (2 * 3.1416 * 40) * (1 - $conversionRate / 100) }}"
                stroke-linecap="round"
            />
        </svg>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: var(--text-light);">{{ $conversionRate }}%</div>
            <div style="font-size: 12px; color: var(--text-muted);">Conversion</div>
        </div>
    </div>

    <div>
        <h3 style="color: var(--text-light); margin-bottom: 16px;">Conversion Rate</h3>
        <p style="color: var(--text-muted); margin-bottom: 16px; max-width: 320px;">
            Percentage of decided leads that were accepted vs. denied.
        </p>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <span style="width: 12px; height: 12px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
            <span style="color: var(--text-light);">Accepted: {{ $completedLeads }}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.08); display: inline-block;"></span>
            <span style="color: var(--text-light);">Denied: {{ $deniedLeads }}</span>
        </div>
    </div>
</div>
@endsection
