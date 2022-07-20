<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_code'];

    public function time_logs()
    {
        return $this->hasMany(TimeLog::class);
    }


    public function getDetailedTitleAttribute()
    {
        return sprintf("[%d] %s%s",
            $this->attributes['id'],
            $this->attributes['name'],
            (empty($this->attributes['short_code']) ? '' : ' (' . $this->attributes['short_code'] . ')')
        );
    }
    
}
