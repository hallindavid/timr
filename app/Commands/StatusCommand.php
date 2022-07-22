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
        $notification_messages = array();

        foreach ($open_logs as $log) {
            $terminal_messages[] = sprintf("Tracking %s since %s (%s)",
                $log->project->detailed_title,
                $log->local_started_at->format('g:i a'),
                $log->started_at->diffForHumans(['parts' => 2])
            );

            $min = round(((strtotime(now()) - strtotime($log->started_at)) / 60), 1);

            $notification_messages[] = sprintf("%s since %s (%s)",
                $log->project->name,
                $log->local_started_at->format('g:i a'),
                MinuteHelper::format_minutes($min)
            );
        }


        if (!empty($this->option('notify'))) {
            if (count($notification_messages) > 0) {
                $this->notify("timr status", implode("\n", $notification_messages), "timr.png");
            }
        } else {
            foreach ($terminal_messages as $msg) {
                $this->info($msg);
            }
        }

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
