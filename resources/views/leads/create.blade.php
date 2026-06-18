@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Add New Lead</h1>
    <p>Enter lead information and submit</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Lead Form</h2>
        <p>Fill in the details below</p>
    </div>

    <div style="padding: 20px;">
        @if ($errors->any())
            <div style="background:#f8d7da; padding:10px; border-radius:6px; margin-bottom:15px;">
                <strong>Fix the errors below:</strong>
                <ul style="margin: 10px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('leads.store') }}" id="create-lead-form">
            @csrf

            <div id="duplicate-warning" style="display: none; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 10px; padding: 16px; margin-bottom: 20px;">
    <p style="color: #F59E0B; font-weight: 600; margin-bottom: 8px;">⚠️ Possible Duplicate Lead</p>
    <p id="duplicate-details" style="color: var(--text-light); margin: 0;"></p>
</div>

            <div style="margin-bottom: 12px;">
                <label>Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="{{ old('company') }}">
            </div>

           <div class="form-group">
    <label>Email</label>
    <input type="email" name="email" id="lead-email" class="form-control" required>
</div>

<div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone" id="lead-phone" class="form-control" required>
</div>

            <div style="margin-bottom: 12px;">
                <label>Product (tech product to offer)</label>
                <input type="text" name="product" class="form-control" value="{{ old('product') }}">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
            </div>

            <div class="form-group">
    <label>Priority</label>
    <select name="priority" class="form-control" required>
        <option value="low">Low</option>
        <option value="medium" selected>Medium</option>
        <option value="high">High</option>
    </select>
</div>

            <div style="margin-bottom: 12px;">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}">
            </div>

            <div style="display:flex; gap:10px; align-items:center;">
                <button type="submit" class="btn btn-primary">Submit Lead</button>
                <a href="{{ route('leads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const form = document.getElementById('create-lead-form');
    const emailInput = document.getElementById('lead-email');
    const phoneInput = document.getElementById('lead-phone');
    const warningBox = document.getElementById('duplicate-warning');
    const warningDetails = document.getElementById('duplicate-details');

    let confirmedDuplicate = false;

    form.addEventListener('submit', function (e) {
        // If already confirmed once, let it submit normally
        if (confirmedDuplicate) return;

        const email = emailInput.value.trim();
        const phone = phoneInput.value.trim();

        if (!email || !phone) return; // let normal validation handle empty fields

        e.preventDefault();

        fetch('{{ route("leads.check-duplicate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email: email, phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.duplicate) {
                const lead = data.lead;
                warningDetails.innerHTML = `
                    A lead with this email or phone already exists:<br>
                    <strong>${lead.name}</strong> (${lead.company}) — Status: ${lead.status}<br>
                    Assigned to: ${lead.assigned_to} — Created: ${lead.created_at}<br><br>
                    Click <strong>Submit</strong> again to create this lead anyway, or update the details above.
                `;
                warningBox.style.display = 'block';
                confirmedDuplicate = true; // allow next submit to go through
                warningBox.scrollIntoView({ behavior: 'smooth' });
            } else {
                // No duplicate, submit for real
                confirmedDuplicate = true;
                form.submit();
            }
        });
    });
</script>

@endsection
