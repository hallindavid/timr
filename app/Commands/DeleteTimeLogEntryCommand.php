<?php

namespace App\Commands;

use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DeleteTimeLogEntryCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'log:delete { id : the id of the time log entry you want to delete }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete a time log entry';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entry = TimeLog::find($this->argument('id'));

        if (empty($entry)) {
            $this->error("Unable to find entry");
            return 1;
        }

        $this->info("Found Entry");
        $this->info("Project: " . $entry->project->detailed_title);
        $this->info("Start Date: " . $entry->local_started_at->format('M j, Y g:i a'));
        $this->info("End Date: " . $entry->local_started_at->format('M j, Y g:i a'));
        $this->info("Notes: " . $entry->notes);


        if ($this->confirm("Are you sure you want to delete this entry?")) {
            $entry->delete();
            $this->info("Time Log Entry Deleted!");
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
        // $schedule->command(static::class)->everyMinute();
    }
}
