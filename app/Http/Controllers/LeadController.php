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
            'status' => 'required|in:contacted,accepted,denied'

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

// ADMIN: Show the CSV import form
public function showImport()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    return view('leads.import');
}

// ADMIN: Download a starter CSV template
public function downloadTemplate()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $filename = 'techflow_lead_import_template.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Name', 'Company', 'Email', 'Phone', 'Product', 'Priority', 'Notes', 'Date']);
        fputcsv($file, ['Juan Dela Cruz', 'ABC Trading Corp', 'juan@abctrading.com', '09171234567', 'CRM Software', 'High', 'Inquired via trade show booth.', '2026-06-20']);
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

// ADMIN: Parse uploaded CSV and show a preview before committing
public function importPreview(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:5120',
    ]);

    $path = $request->file('csv_file')->getRealPath();
    $handle = fopen($path, 'r');

    if ($handle === false) {
        return back()->with('error', 'Could not read the uploaded file.');
    }

    $rawHeaders = fgetcsv($handle);
    if ($rawHeaders === false) {
        fclose($handle);
        return back()->with('error', 'The CSV file appears to be empty.');
    }

    // Flexible header mapping: normalize each header then match against known aliases
    $fieldAliases = [
        'name'     => ['name', 'fullname', 'full_name', 'leadname', 'contactname', 'clientname'],
        'company'  => ['company', 'companyname', 'organization', 'business', 'businessname'],
        'email'    => ['email', 'emailaddress', 'mail'],
        'phone'    => ['phone', 'phonenumber', 'mobile', 'mobilenumber', 'contactnumber', 'cellphone'],
        'product'  => ['product', 'productofinterest', 'productinterest', 'service', 'serviceoffered', 'interest'],
        'priority' => ['priority', 'leadpriority', 'urgency'],
        'notes'    => ['notes', 'note', 'remarks', 'comments', 'initialnotes'],
        'date'     => ['date', 'targetdate', 'datecreated', 'inquirydate'],
    ];

    $normalize = function ($string) {
        return strtolower(preg_replace('/[^a-z0-9]/i', '', $string));
    };

    $columnMap = []; // column index => our internal field name
    foreach ($rawHeaders as $index => $rawHeader) {
        $normalizedHeader = $normalize($rawHeader);
        foreach ($fieldAliases as $field => $aliases) {
            if (in_array($normalizedHeader, $aliases)) {
                $columnMap[$index] = $field;
                break;
            }
        }
    }

    if (!in_array('name', $columnMap) || !in_array('email', $columnMap) || !in_array('phone', $columnMap)) {
        fclose($handle);
        return back()->with('error', 'Your CSV must include at minimum a Name, Email, and Phone column. Please check the template for the expected format.');
    }

    $rows = [];
    $rowNumber = 1; // header was row 1

    while (($data = fgetcsv($handle)) !== false) {
        $rowNumber++;

        // skip fully blank lines
        if (count(array_filter($data, fn($v) => trim((string) $v) !== '')) === 0) {
            continue;
        }

        $parsed = [
            'row'      => $rowNumber,
            'name'     => '',
            'company'  => '',
            'email'    => '',
            'phone'    => '',
            'product'  => '',
            'priority' => 'medium',
            'notes'    => '',
            'date'     => null,
        ];

        foreach ($columnMap as $index => $field) {
            $parsed[$field] = trim((string) ($data[$index] ?? ''));
        }

        // Normalize priority to one of low/medium/high, default medium if unrecognized
        $priorityNormalized = strtolower($parsed['priority']);
        $parsed['priority'] = in_array($priorityNormalized, ['low', 'medium', 'high']) ? $priorityNormalized : 'medium';

        // Validate required fields per-row
        $rowErrors = [];
        if ($parsed['name'] === '') $rowErrors[] = 'Missing name';
        if ($parsed['email'] === '' || !filter_var($parsed['email'], FILTER_VALIDATE_EMAIL)) $rowErrors[] = 'Invalid or missing email';
        if ($parsed['phone'] === '') $rowErrors[] = 'Missing phone';

        // Check for duplicate against existing leads (email or phone match)
        $isDuplicate = false;
        if (empty($rowErrors)) {
            $isDuplicate = Lead::where('email', $parsed['email'])
                ->orWhere('phone', $parsed['phone'])
                ->exists();
        }

        $parsed['errors'] = $rowErrors;
        $parsed['is_duplicate'] = $isDuplicate;
        $parsed['will_import'] = empty($rowErrors) && !$isDuplicate;

        $rows[] = $parsed;
    }

    fclose($handle);

    if (empty($rows)) {
        return back()->with('error', 'No data rows were found in the uploaded CSV.');
    }

    // Store the parsed rows in the session so the confirm step doesn't need
    // the file to be re-uploaded.
    session(['csv_import_rows' => $rows]);

    $summary = [
        'total'       => count($rows),
        'will_import' => count(array_filter($rows, fn($r) => $r['will_import'])),
        'duplicates'  => count(array_filter($rows, fn($r) => $r['is_duplicate'])),
        'invalid'     => count(array_filter($rows, fn($r) => !empty($r['errors']))),
    ];

    return view('leads.import-preview', compact('rows', 'summary'));
}

// ADMIN: Commit the previously-parsed CSV rows into the leads table
public function importConfirm(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $rows = session('csv_import_rows');

    if (empty($rows)) {
        return redirect()->route('leads.import')->with('error', 'Your import session expired. Please upload the CSV again.');
    }

    $importedCount = 0;

    foreach ($rows as $row) {
        if (!$row['will_import']) {
            continue;
        }

        $lead = Lead::create([
            'name'     => $row['name'],
            'company'  => $row['company'] ?: null,
            'email'    => $row['email'],
            'phone'    => $row['phone'],
            'product'  => $row['product'] ?: null,
            'priority' => $row['priority'],
            'notes'    => $row['notes'] ?: null,
            'date'     => $row['date'] ?: now()->toDateString(),
            'status'   => 'new',
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => auth()->user()->first_name . ' imported this lead via CSV.',
        ]);

        $importedCount++;
    }

    session()->forget('csv_import_rows');

    return redirect()->route('leads.index')->with('success', "$importedCount lead(s) imported successfully.");
}

}