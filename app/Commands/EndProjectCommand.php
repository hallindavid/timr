<?php

namespace App\Commands;

use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class EndProjectCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'project:end {--id= : the project id} {--code= : the project short code }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Ends a time log for a given project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time_log = TimeLog::whereNull('ended_at')->orderBy('started_at', 'ASC')->take(1)->get();

        if (empty($time_log)) {
            $this->error("There are no projects that are currently being worked on");
            return;
        }

        $project = $time_log->project;

        if ($this->confirm("End work on project " . $project->title_with_id)) {

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
