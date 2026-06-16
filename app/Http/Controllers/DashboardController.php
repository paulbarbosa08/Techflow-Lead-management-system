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
    // Admin dashboard - total system stats
    $totalLeads = Lead::count();
    $newLeads = Lead::where('status', 'new')->count();
    $contactedLeads = Lead::where('status', 'contacted')->count();
    $completedLeads = Lead::where('status', 'accepted')->count();
    $deniedLeads = Lead::where('status', 'denied')->count();
    $totalStaff = User::where('role', 'staff')->count();

    // Conversion rate: accepted vs (accepted + denied)
    $decidedLeads = $completedLeads + $deniedLeads;
    $conversionRate = $decidedLeads > 0 
        ? round(($completedLeads / $decidedLeads) * 100, 1) 
        : 0;
    
    return view('dashboard.admin', compact(
        'user',
        'totalLeads',
        'newLeads',
        'contactedLeads',
        'completedLeads',
        'deniedLeads',
        'totalStaff',
        'conversionRate'
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
            
            return view('dashboard.staff', compact(
                'user',
                'totalLeads',
                'newLeads',
                'contactedLeads',
                'completedLeads',
                'deniedLeads'
            ));
        }
    }
}
