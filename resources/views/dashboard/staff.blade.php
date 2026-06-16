@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h1>
    <p>Your lead management overview</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>ASSIGNED LEADS</h3>
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
    
    {{-- CHANGE THIS FROM QUALIFIED TO COMPLETED --}}
    <div class="stat-card">
        <h3>COMPLETED LEADS</h3>
        <div class="stat-number">{{ $completedLeads }}</div>
    </div>
    
    {{-- OPTIONAL: Add denied leads if you want --}}
    <div class="stat-card">
        <h3>DENIED LEADS</h3>
        <div class="stat-number">{{ $deniedLeads }}</div>
    </div>
</div>
@endsection