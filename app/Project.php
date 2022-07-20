<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_code'];

    public function time_logs()
    {
        return $this->hasMany(TimeLog::class);
    }


}
