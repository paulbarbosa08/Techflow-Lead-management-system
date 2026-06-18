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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="color: var(--text-light); margin: 0;">Notes</h2>
        <button id="summarize-btn" class="btn btn-primary" data-id="{{ $lead->id }}">
            ✨ AI Summary
        </button>
    </div>

    <div id="summary-box" style="display: none; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 10px; padding: 16px; margin-bottom: 20px;">
        <p style="color: #3B82F6; font-weight: 600; margin-bottom: 8px; font-size: 13px; text-transform: uppercase;">AI Summary</p>
        <p id="summary-text" style="color: var(--text-light); margin: 0;"></p>
    </div>

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

<script>
    document.getElementById('summarize-btn').addEventListener('click', function() {
        const leadId = this.getAttribute('data-id');
        const btn = this;
        const box = document.getElementById('summary-box');
        const text = document.getElementById('summary-text');

        btn.disabled = true;
        btn.textContent = 'Generating...';

        fetch(`/leads/${leadId}/summarize`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = '✨ AI Summary';

            if (data.summary) {
                text.textContent = data.summary;
                box.style.display = 'block';
            } else {
                alert(data.error || 'Something went wrong.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = '✨ AI Summary';
            alert('Failed to connect. Please try again.');
        });
    });
</script>
@endsection