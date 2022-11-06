<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'started_at',
        'ended_at',
        'notes',
        'billed',
        'logged',
        'tag',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'billed' => 'boolean',
        'logged' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeOpen($query)
    {
        $query->whereNull('ended_at')->whereNotNull('started_at');
    }

    public function scopeClosed($query)
    {
        $query->whereNotNull('ended_at')->whereNotNull('started_at');
    }

    public function getLocalStartedAtAttribute()
    {
        return $this->started_at->timezone(config('app.user_timezone'));
    }

    public function getLocalEndedAtAttribute()
    {
        return $this->ended_at->timezone(config('app.user_timezone'));
    }
}
