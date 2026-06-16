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

        <form method="POST" action="{{ route('leads.store') }}">
            @csrf

            <div style="margin-bottom: 12px;">
                <label>Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="{{ old('company') }}">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label>Phone *</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
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
@endsection
