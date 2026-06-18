<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
       if ($user->role == 'admin') {
    $totalLeads = Lead::count();
    $newLeads = Lead::where('status', 'new')->count();
    $contactedLeads = Lead::where('status', 'contacted')->count();
    $completedLeads = Lead::where('status', 'accepted')->count();
    $deniedLeads = Lead::where('status', 'denied')->count();
    $totalStaff = User::where('role', 'staff')->count();

    $decidedLeads = $completedLeads + $deniedLeads;
    $conversionRate = $decidedLeads > 0 
        ? round(($completedLeads / $decidedLeads) * 100, 1) 
        : 0;

    // Stale leads: stuck in New/Contacted for 3+ days
    $staleLeads = Lead::with('assignedTo')
        ->whereIn('status', ['new', 'contacted'])
        ->where('updated_at', '<=', now()->subDays(3))
        ->orderBy('updated_at', 'asc')
        ->get();
    
        $recentActivity = \App\Models\LeadActivity::with('lead', 'user')
    ->latest()
    ->take(5)
    ->get();

    return view('dashboard.admin', compact(
        'user',
        'totalLeads',
        'newLeads',
        'contactedLeads',
        'completedLeads',
        'deniedLeads',
        'totalStaff',
        'conversionRate',
        'staleLeads',
        'recentActivity'
    ));
}
        
        else {
            // Staff dashboard - personal stats
            $totalLeads = Lead::where('assigned_to', $user->id)->count();
            $newLeads = Lead::where('assigned_to', $user->id)->where('status', 'new')->count();
            $contactedLeads = Lead::where('assigned_to', $user->id)->where('status', 'contacted')->count();

            // OPTIONAL: update staff too so it matches new statuses
            $completedLeads = Lead::where('assigned_to', $user->id)->where('status', 'accepted')->count();
            $deniedLeads = Lead::where('assigned_to', $user->id)->where('status', 'denied')->count();
            
            $staleLeads = Lead::where('assigned_to', $user->id)
    ->whereIn('status', ['new', 'contacted'])
    ->where('updated_at', '<=', now()->subDays(3))
    ->orderBy('updated_at', 'asc')
    ->get();

    $recentActivity = \App\Models\LeadActivity::with('lead', 'user')
    ->whereHas('lead', function ($q) use ($user) {
        $q->where('assigned_to', $user->id);
    })
    ->latest()
    ->take(5)
    ->get();

            return view('dashboard.staff', compact(
                'user',
                'totalLeads',
                'newLeads',
                'contactedLeads',
                'completedLeads',
                'deniedLeads',
                'staleLeads',
                'recentActivity'
            ));
        }
    }

    // ADMIN: Staff performance leaderboard
public function leaderboard()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $staff = User::where('role', 'staff')->get();

    $leaderboard = $staff->map(function ($user) {
        $totalLeads = Lead::where('assigned_to', $user->id)->count();
        $accepted = Lead::where('assigned_to', $user->id)->where('status', 'accepted')->count();
        $denied = Lead::where('assigned_to', $user->id)->where('status', 'denied')->count();
        $decided = $accepted + $denied;

        $conversionRate = $decided > 0 ? round(($accepted / $decided) * 100, 1) : 0;

        return [
            'name' => $user->first_name . ' ' . $user->last_name,
            'total_leads' => $totalLeads,
            'accepted' => $accepted,
            'denied' => $denied,
            'conversion_rate' => $conversionRate,
        ];
    });

    // Sort by conversion rate (highest first), then by total leads as tiebreaker
    $leaderboard = $leaderboard->sortByDesc('conversion_rate')->sortByDesc('total_leads')->values();

    // Actually re-sort properly: conversion rate primary, total leads secondary
    $leaderboard = $leaderboard->sort(function ($a, $b) {
        if ($a['conversion_rate'] == $b['conversion_rate']) {
            return $b['total_leads'] <=> $a['total_leads'];
        }
        return $b['conversion_rate'] <=> $a['conversion_rate'];
    })->values();

    return view('dashboard.leaderboard', compact('leaderboard'));
}

}
