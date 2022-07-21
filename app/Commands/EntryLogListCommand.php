<?php

namespace App\Commands;

use App\Helpers\MinuteHelper;
use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class EntryLogListCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'log:list {--from= : (YYYY-MM-DD) start of reporting period default: -30 days } {--to= : (YYYY-MM-DD) end of reporting period default:today  }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'display log entries';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Determine date/time range (using local timezone on I/O and UTC on DB queries
        $from_local = now(config('app.user_timezone'))->setTime(0, 0, 0)->subDays(30);
        $to_local = now(config('app.user_timezone'));

        if (!empty($this->option('from'))) {
            $from_option = Carbon::createFromFormat("Y-m-d", $this->option('from'), config('app.user_timezone'));
            if ($from_option !== false) {
                $from_local = $from_option;
            }
        }

        if (!empty($this->option('to'))) {
            $to_option = Carbon::createFromFormat("Y-m-d", $this->option('to'), config('app.user_timezone'));
            if ($to_option !== false) {
                $to = $to_option;
            }
        }

        $from = $from_local->copy()->timezone('UTC');
        $to = $to_local->copy()->timezone('UTC');


        // Display the Query Parameters to the User
        $this->info(sprintf("Reporting on entries between %s and %s",
            $from_local->format('M j, Y'),
            $to_local->format('M j, Y'),
        ));


        // Query for results
        $time_logs = TimeLog::with(['project'])
            ->where(DB::raw("IFNULL(ended_at, strftime('%Y-%m-%d %H:%M:%S','now'))"), '>=', $from)
            ->where('started_at', '<=', $to)
            ->orderBy('started_at', 'ASC')
            ->select('id', 'project_id', 'started_at', 'ended_at', 'notes', DB::raw("ROUND((JULIANDAY(IFNULL(ended_at,strftime('%Y-%m-%d %H:%M:%S','now'))) - JULIANDAY(started_at)) * 1440) as minutes"))
            ->get()->map(function ($entry) {
                return [
                    'log_id' => $entry->id,
                    'project_id' => $entry->project_id,
                    'project' => $entry->project->name,
                    'date' => $entry->started_at->timezone(config('app.user_timezone'))->format('M j, Y'),
                    'start' => $entry->started_at->timezone(config('app.user_timezone'))->format('g:i a'),
                    'end' => empty($entry->ended_at) ? '(open)' : $entry->ended_at->timezone(config('app.user_timezone'))->format('g:i a'),
                    'minutes' => MinuteHelper::format_minutes($entry->minutes),
                    'notes' => $entry->notes,
                ];
            })->toArray();


        // Query for total
        $net_minutes = TimeLog::where(DB::raw("IFNULL(ended_at, strftime('%Y-%m-%d %H:%M:%S','now'))"), '>=', $from)
            ->where('started_at', '<=', $to)
            ->orderBy('started_at', 'ASC')
            ->selectRaw("SUM(ROUND((JULIANDAY(IFNULL(ended_at,strftime('%Y-%m-%d %H:%M:%S','now'))) - JULIANDAY(started_at)) * 1440)) as minutes")
            ->first()->minutes;

        // Append Totals Row to table output
        $time_logs[] = ['', '', '', '','', 'TOTAL:', MinuteHelper::format_minutes($net_minutes), ''];

        // Display Table
        $this->table(['Entry ID', 'Project ID', 'Project', 'Date', 'Start', 'End', 'Minutes', 'Notes'], $time_logs);

        return 0;
    }

    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}