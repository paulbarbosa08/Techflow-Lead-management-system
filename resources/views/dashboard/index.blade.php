@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Welcome back, {{ $user->first_name }}!</h1>
    <p>Here's your lead management overview</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>TOTAL ASSIGNED LEADS</h3>
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
        <h3>QUALIFIED LEADS</h3>
        <div class="stat-number">{{ $qualifiedLeads }}</div>
    </div>
</div>
@endsection