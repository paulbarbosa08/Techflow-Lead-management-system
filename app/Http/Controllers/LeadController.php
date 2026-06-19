<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\LeadNote;
use App\Models\LeadActivity;
use App\Mail\LeadAssignedMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;


class LeadController extends Controller
{
    // ADMIN: View all leads
    // ADMIN: View all leads
public function adminIndex(Request $request)
{
    // Check if user is admin
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }
    
    $query = Lead::with('assignedTo');

    // Search by name, company, or email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $leads = $query->latest()->get();
    $staff = User::where('role', 'staff')->get();
    
    return view('leads.admin-index', compact('leads', 'staff'));
}

// ADMIN: Export leads to CSV
public function exportCsv(Request $request)
{
    // Check if user is admin
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $query = Lead::with('assignedTo');

    // Respect the same search/filter as the leads page
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $leads = $query->latest()->get();

    $filename = 'leads_export_' . now()->format('Y-m-d_His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($leads) {
        $file = fopen('php://output', 'w');

        // Column headers
        fputcsv($file, ['ID', 'Name', 'Company', 'Email', 'Phone', 'Product', 'Status', 'Assigned To', 'Date', 'Notes']);

        // Data rows
        foreach ($leads as $lead) {
            fputcsv($file, [
                $lead->id,
                $lead->name,
                $lead->company,
                $lead->email,
                $lead->phone,
                $lead->product,
                ucfirst($lead->status),
                $lead->assignedTo ? $lead->assignedTo->first_name . ' ' . $lead->assignedTo->last_name : 'Unassigned',
                $lead->date->format('m-d-Y'),
                $lead->notes,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
    
    // ADMIN: Delete lead
    public function destroy($id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
        
        $lead = Lead::findOrFail($id);
        $lead->delete();
        
        return response()->json(['success' => 'Lead deleted successfully.']);
    }
    
    // ADMIN: Assign lead to staff
    public function assign(Request $request, $id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
        
        $request->validate([
            'staff_id' => 'required|exists:users,id'
        ]);
        
        $lead = Lead::findOrFail($id);
$lead->update(['assigned_to' => $request->staff_id]);

$staffName = 'Unassigned';

if ($request->staff_id) {
    $staffUser = User::find($request->staff_id);
    $staffName = $staffUser->first_name . ' ' . $staffUser->last_name;

    // Send email notification to the assigned staff member
    Mail::to($staffUser->email)->send(new LeadAssignedMail($lead));
}

LeadActivity::create([
    'lead_id' => $lead->id,
    'user_id' => auth()->id(),
    'action' => 'assigned',
    'description' => auth()->user()->first_name . ' assigned this lead to ' . $staffName . '.',
]);

return response()->json(['success' => 'Lead assigned successfully.']);
    }
    
    // STAFF: View assigned leads
    public function staffIndex()
    {
        // Check if user is staff
        if (auth()->user()->role !== 'staff') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access. Staff only.');
        }
        
        $user = auth()->user();
        $leads = Lead::where('assigned_to', $user->id)->get();
        
        return view('leads.staff-index', compact('leads'));
    }
    
    // STAFF: Update lead status page
    public function updateIndex()
    {
        // Check if user is staff
        if (auth()->user()->role !== 'staff') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access. Staff only.');
        }
        
        $user = auth()->user();
        $leads = Lead::where('assigned_to', $user->id)->get();
        
        return view('leads.update-status', compact('leads'));
    }
    
    // STAFF: Update lead status
    public function updateStatus(Request $request, $id)
    {
        // Check if user is staff
        if (auth()->user()->role !== 'staff') {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
        
        $request->validate([
            'status' => 'required|in:new,contacted,accepted,denied'

        ]);
        
        $lead = Lead::findOrFail($id);
        
        // Additional check: make sure staff can only update their own leads
        if ($lead->assigned_to !== auth()->id()) {
            return response()->json(['error' => 'You can only update your own leads.'], 403);
        }
        
        $oldStatus = $lead->status;
$lead->update(['status' => $request->status]);

LeadActivity::create([
    'lead_id' => $lead->id,
    'user_id' => auth()->id(),
    'action' => 'status_changed',
    'description' => auth()->user()->first_name . ' changed status from ' . ucfirst($oldStatus) . ' to ' . ucfirst($request->status) . '.',
]);

return response()->json(['success' => true]);
    }

// ADMIN: Show add lead form
public function create()
{
    // Check if user is admin
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    return view('leads.create');
}

// ADMIN: Store new lead
public function store(Request $request)
{
    // Check if user is admin
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $validated = $request->validate([
    'name'     => 'required|string|max:255',
    'company'  => 'nullable|string|max:255',
    'email'    => 'required|email|max:255',
    'phone'    => 'required|string|max:50',
    'product'  => 'nullable|string|max:255',
    'priority' => 'required|in:low,medium,high',
    'notes'    => 'nullable|string',
    'date'     => 'nullable|date',
]);

    // If date is not provided, use today (because your migration requires a date)
    if (empty($validated['date'])) {
        $validated['date'] = now()->toDateString();
    }

    // Default status
    $validated['status'] = 'new';

    $lead = Lead::create($validated);

LeadActivity::create([
    'lead_id' => $lead->id,
    'user_id' => auth()->id(),
    'action' => 'created',
    'description' => auth()->user()->first_name . ' created this lead.',
]);

return redirect()->route('leads.index')->with('success', 'Lead added successfully!');
}

// View activity log for a specific lead
public function activity($id)
{
    $lead = Lead::with(['activities.user', 'leadNotes.user'])->findOrFail($id);

    return view('leads.activity', compact('lead'));
}

// Add a timestamped note to a lead (admin or assigned staff)
public function addNote(Request $request, $id)
{
    $lead = Lead::findOrFail($id);
    $user = auth()->user();

    // Only admin or the staff assigned to this lead can add notes
    if ($user->role !== 'admin' && $lead->assigned_to !== $user->id) {
        return response()->json(['error' => 'Unauthorized access.'], 403);
    }

    $request->validate([
        'note' => 'required|string|max:1000',
    ]);

    LeadNote::create([
        'lead_id' => $lead->id,
        'user_id' => $user->id,
        'note' => $request->note,
    ]);

    return redirect()->back()->with('success', 'Note added successfully!');
}

// Summarize all notes for a lead using Groq AI
public function summarizeNotes($id)
{
    $lead = Lead::with('leadNotes')->findOrFail($id);

    // Only admin or the assigned staff can summarize
    if (auth()->user()->role !== 'admin' && $lead->assigned_to !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized access.'], 403);
    }

    if ($lead->leadNotes->isEmpty()) {
        return response()->json(['error' => 'No notes available to summarize.'], 400);
    }

    // Build the notes text block
    $notesText = $lead->leadNotes->map(function ($note) {
        return '- (' . $note->created_at->format('M d, Y') . ') ' . $note->note;
    })->implode("\n");

    $prompt = "Summarize the following lead notes into a short, professional paragraph (3-4 sentences max) for a sales dashboard. Focus on what's happened so far and what the next step should be.\n\nLead: {$lead->name} from {$lead->company}\n\nNotes:\n{$notesText}";

    $apiKey = config('services.groq.api_key');

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 200,
    ]);

    if ($response->failed()) {
        \Log::error('Groq API failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return response()->json(['error' => 'Failed to generate summary: ' . $response->status() . ' - ' . $response->body()], 500);
    }

    $data = $response->json();
    $summary = $data['choices'][0]['message']['content'] ?? 'Unable to generate summary.';

    return response()->json(['summary' => trim($summary)]);
}

// Check if a lead with the same email or phone already exists
public function checkDuplicate(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'phone' => 'required|string',
    ]);

    $existing = Lead::with('assignedTo')
        ->where(function ($q) use ($request) {
            $q->where('email', $request->email)
              ->orWhere('phone', $request->phone);
        })
        ->first();

    if ($existing) {
        return response()->json([
            'duplicate' => true,
            'lead' => [
                'name' => $existing->name,
                'company' => $existing->company,
                'status' => ucfirst($existing->status),
                'assigned_to' => $existing->assignedTo 
                    ? $existing->assignedTo->first_name . ' ' . $existing->assignedTo->last_name 
                    : 'Unassigned',
                'created_at' => $existing->created_at->format('M d, Y'),
            ]
        ]);
    }

    return response()->json(['duplicate' => false]);
}

}