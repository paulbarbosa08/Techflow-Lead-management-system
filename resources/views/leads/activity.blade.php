@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Activity Log</h1>
    <p>History for lead: {{ $lead->name }} ({{ $lead->company }})</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Timeline</h2>
        <p>All actions taken on this lead</p>
    </div>

    <div style="padding: 24px;">
        @forelse($lead->activities as $activity)
            <div style="border-left: 3px solid var(--primary-yellow); padding: 12px 20px; margin-bottom: 12px;">
                <p style="color: var(--text-light); margin-bottom: 4px;">
                    {{ $activity->description }}
                </p>
                <p style="color: var(--text-muted); font-size: 13px;">
                    {{ $activity->created_at->format('M d, Y - h:i A') }}
                </p>
            </div>
        @empty
            <p style="color: var(--text-muted);">No activity recorded yet.</p>
        @endforelse
    </div>

    <div style="padding: 0 24px 24px;">
        <a href="{{ route('leads.index') }}" class="btn" style="background: #6b7280; color: white;">← Back to Leads</a>
    </div>
</div>

<div style="padding: 24px; border-top: 1px solid rgba(255,255,255,0.08);">
    <h2 style="color: var(--text-light); margin-bottom: 16px;">Notes</h2>

    <form method="POST" action="{{ route('leads.notes.add', $lead->id) }}" style="margin-bottom: 24px;">
        @csrf
        <div class="form-group">
            <textarea name="note" class="form-control" rows="3" placeholder="Add a note about this lead..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Note</button>
    </form>

    @forelse($lead->leadNotes as $note)
        <div style="border-left: 3px solid #3B82F6; padding: 12px 20px; margin-bottom: 12px;">
            <p style="color: var(--text-light); margin-bottom: 4px;">
                {{ $note->note }}
            </p>
            <p style="color: var(--text-muted); font-size: 13px;">
                {{ $note->user->first_name ?? 'Unknown' }} — {{ $note->created_at->format('M d, Y - h:i A') }}
            </p>
        </div>
    @empty
        <p style="color: var(--text-muted);">No notes yet.</p>
    @endforelse
</div>
@endsection