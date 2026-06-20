@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Import Preview</h1>
    <p>Review the parsed data below before adding it to your leads</p>
</div>

<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <h3>TOTAL ROWS</h3>
        <div class="stat-number">{{ $summary['total'] }}</div>
    </div>
    <div class="stat-card" style="border-color: rgba(16, 185, 129, 0.3);">
        <h3>WILL IMPORT</h3>
        <div class="stat-number" style="color: #34D399;">{{ $summary['will_import'] }}</div>
    </div>
    <div class="stat-card" style="border-color: rgba(245, 158, 11, 0.3);">
        <h3>DUPLICATES SKIPPED</h3>
        <div class="stat-number" style="color: #FBBF24;">{{ $summary['duplicates'] }}</div>
    </div>
    <div class="stat-card" style="border-color: rgba(239, 68, 68, 0.3);">
        <h3>INVALID ROWS</h3>
        <div class="stat-number" style="color: #F87171;">{{ $summary['invalid'] }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Row-by-Row Preview</h2>
        <p>Rows in green will be imported. Rows in yellow or red will be skipped automatically.</p>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>CSV Row</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th>Priority</th>
                    <th>Outcome</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr style="{{ !$row['will_import'] ? 'opacity: 0.6;' : '' }}">
                    <td>{{ $row['row'] }}</td>
                    <td>{{ $row['name'] ?: '—' }}</td>
                    <td>{{ $row['company'] ?: '—' }}</td>
                    <td>{{ $row['email'] ?: '—' }}</td>
                    <td>{{ $row['phone'] ?: '—' }}</td>
                    <td>{{ $row['product'] ?: '—' }}</td>
                    <td>
                        <span class="priority-badge priority-{{ $row['priority'] }}">
                            {{ ucfirst($row['priority']) }}
                        </span>
                    </td>
                    <td>
                        @if($row['will_import'])
                            <span class="status-badge status-accepted">Will Import</span>
                        @elseif($row['is_duplicate'])
                            <span class="status-badge status-contacted">Duplicate — Skipped</span>
                        @else
                            <span class="status-badge status-denied" title="{{ implode(', ', $row['errors']) }}">
                                Invalid — {{ implode(', ', $row['errors']) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="padding: 24px; display:flex; gap:10px; align-items:center;">
        @if($summary['will_import'] > 0)
            <form method="POST" action="{{ route('leads.import.confirm') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    ✅ Confirm Import ({{ $summary['will_import'] }} lead{{ $summary['will_import'] === 1 ? '' : 's' }})
                </button>
            </form>
        @else
            <p style="color: var(--text-muted);">No valid rows to import. Please fix the issues above and re-upload.</p>
        @endif
        <a href="{{ route('leads.import') }}" class="btn btn-secondary">Upload a Different File</a>
        <a href="{{ route('leads.index') }}" class="btn" style="background: #6b7280; color: white;">Cancel</a>
    </div>
</div>
@endsection