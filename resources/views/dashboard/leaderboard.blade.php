@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Staff Leaderboard</h1>
    <p>Performance ranking based on lead conversion</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Rankings</h2>
        <p>Sorted by conversion rate, then total leads handled</p>
    </div>

    <div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Staff Name</th>
                <th>Total Leads</th>
                <th>Accepted</th>
                <th>Denied</th>
                <th>Conversion Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaderboard as $index => $entry)
            <tr>
                <td>
                    @if($index == 0)
                        🥇
                    @elseif($index == 1)
                        🥈
                    @elseif($index == 2)
                        🥉
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>{{ $entry['name'] }}</td>
                <td>{{ $entry['total_leads'] }}</td>
                <td>{{ $entry['accepted'] }}</td>
                <td>{{ $entry['denied'] }}</td>
                <td>
                    <span class="priority-badge {{ $entry['conversion_rate'] >= 50 ? 'priority-low' : 'priority-high' }}">
                        {{ $entry['conversion_rate'] }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection