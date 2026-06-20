@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Import Leads from CSV</h1>
    <p>Bulk-upload collected prospect data instead of entering leads one by one</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Upload File</h2>
        <p>Select a CSV file containing your lead data</p>
    </div>

    <div style="padding: 24px;">

        <div style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 10px; padding: 18px 20px; margin-bottom: 24px;">
            <p style="color: #60A5FA; font-weight: 600; margin-bottom: 8px; font-size: 13px; text-transform: uppercase;">Expected Columns</p>
            <p style="color: var(--text-light); margin: 0 0 12px; font-size: 14px; line-height: 1.6;">
                <strong>Required:</strong> Name, Email, Phone &nbsp;•&nbsp;
                <strong>Optional:</strong> Company, Product, Priority, Notes, Date
            </p>
            <p style="color: var(--text-muted); margin: 0; font-size: 13px; line-height: 1.6;">
                Header names are flexible — for example, "Phone", "Phone Number", and "Mobile" are all recognized automatically.
                Not sure about the format? Download the starter template below.
            </p>
            <a href="{{ route('leads.import.template') }}" class="btn btn-secondary" style="margin-top: 14px; display: inline-block;">
                📄 Download Template
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('leads.import.preview') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <label>CSV File</label>
                <input type="file" name="csv_file" accept=".csv,text/csv" class="form-control" required>
                <p style="color: var(--text-muted); font-size: 12px; margin-top: 6px;">Max file size: 5MB</p>
            </div>

            <div style="display:flex; gap:10px; align-items:center;">
                <button type="submit" class="btn btn-primary">Upload &amp; Preview</button>
                <a href="{{ route('leads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

    </div>
</div>

<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h2>How It Works</h2>
    </div>
    <div style="padding: 0 24px 24px; color: var(--text-muted); font-size: 14px; line-height: 1.8;">
        <p>1. Upload your CSV — nothing is saved to the database yet.</p>
        <p>2. You'll see a preview of every row, including which ones will be skipped (missing info or already exist as a lead).</p>
        <p>3. Confirm the import to add all valid, non-duplicate rows as new leads with status "New."</p>
        <p>4. Each imported lead is automatically logged in the activity history, just like a manually created one.</p>
    </div>
</div>
@endsection