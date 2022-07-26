<?php

namespace App\Commands;

use App\Helpers\MinuteHelper;
use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class StatusCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'status {--notify : send a desktop notification, see https://laravel-zero.com/docs/send-desktop-notifications }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Shows your current project status';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $open_logs = TimeLog::open()->orderBy('started_at', 'ASC')->get();

        $terminal_messages = array();

        foreach ($open_logs as $log) {
            $terminal_messages[] = sprintf("Tracking %s since %s (%s)",
                $log->project->detailed_title,
                $log->local_started_at->format('g:i a'),
                $log->started_at->diffForHumans(['parts' => 2])
            );
        }

        foreach ($terminal_messages as $msg) {
            $this->info($msg);
        }

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

    }
}
