<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

   protected $fillable = [
    'name',
    'company',
    'email',
    'phone',
    'product',
    'status',
    'priority',
    'assigned_to',
    'date',
    'notes'
];


    protected $casts = [
        'date' => 'date'
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

public function activities()
{
    return $this->hasMany(LeadActivity::class)->latest();
}

public function leadNotes()
{
    return $this->hasMany(LeadNote::class)->latest();
}

// Returns true if this lead has been sitting in New/Contacted for 3+ days
public function isStale()
{
    if (!in_array($this->status, ['new', 'contacted'])) {
        return false;
    }

    return $this->updated_at->diffInDays(now()) >= 3;
}

// Returns how many days this lead has been sitting without resolution
public function daysSinceUpdate()
{
    return $this->updated_at->diffInDays(now());
}

}