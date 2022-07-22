<?php

namespace App\Commands;

use App\Helpers\MinuteHelper;
use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class NotifyCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'notify';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'designed to be a cron job - every hour it will send a desktop notification if you are currently working on a project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $open_logs = TimeLog::open()->orderBy('started_at', 'ASC')->get();

        $notification_messages = array();

        foreach ($open_logs as $log) {
            $min = round(((strtotime(now()) - strtotime($log->started_at)) / 60), 1);

            $notification_messages[] = sprintf("%s since %s (%s)",
                $log->project->name,
                $log->local_started_at->format('g:i a'),
                MinuteHelper::format_minutes($min)
            );
        }

        if (count($notification_messages) > 0) {
            $this->notify("timr status", implode("\n", $notification_messages), "timr.png");
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
        $schedule->command(static::class)
            ->timezone(config('app.user_timezone'))
            ->between('06:00', '21:00')
            ->weekdays()
            ->hourly();
    }
}
