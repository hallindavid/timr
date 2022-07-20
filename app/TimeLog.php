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
}
