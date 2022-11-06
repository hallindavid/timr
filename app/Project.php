<?php

namespace App;

use App\Helpers\MinuteHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_code', 'last_bill', 'next_bill', 'weely_hours'];

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

    public function getThisWeekAttribute()
    {
        return MinuteHelper::format_minutes(DB::table('time_logs')
            ->where('project_id', $this->id)
            ->where('started_at', '>', Carbon::now(config('app.user_timezone'))->startOfWeek(Carbon::SUNDAY)->timezone('UTC')->format('Y-m-d H:i:s'))
            ->whereNotNull('ended_at')
            ->selectRaw("SUM(ROUND((JULIANDAY(ended_at) - JULIANDAY(started_at)) * 1440)) as minutes")
            ->first()->minutes);
    }

    public function getLastThirtyAttribute()
    {
        return MinuteHelper::format_minutes(DB::table('time_logs')
            ->where('project_id', $this->id)
            ->where('started_at', '>', Carbon::now(config('app.user_timezone'))->subDays(30)->setTime(0, 0, 0)->timezone('UTC')->format('Y-m-d H:i:s'))
            ->whereNotNull('ended_at')
            ->selectRaw("SUM(ROUND((JULIANDAY(ended_at) - JULIANDAY(started_at)) * 1440)) as minutes")
            ->first()->minutes);
    }

    public function getAllTimeAttribute()
    {
        return MinuteHelper::format_minutes(DB::table('time_logs')
            ->where('project_id', $this->id)
            ->whereNotNull('ended_at')
            ->selectRaw("SUM(ROUND((JULIANDAY(ended_at) - JULIANDAY(started_at)) * 1440)) as minutes")
            ->first()->minutes);
    }

    public function getLastEntryAttribute()
    {
        $last_entry = TimeLog::where('project_id', $this->id)
            ->orderBy('started_at', 'DESC')
            ->first();

        if (empty($last_entry)) {
            return '-';
        }

        return $last_entry->started_at->timezone(config('app.user_timezone'))->format('M j, Y g:i a')
            . (is_null($last_entry->ended_at) ? '(open)' : '');
    }


}
