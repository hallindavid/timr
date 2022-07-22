<?php

namespace App\Commands;

use App\TimeLog;
use App\Traits\RequiresSetup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use LaravelZero\Framework\Commands\Command;

class EditTimeLogCommand extends Command
{
    use RequiresSetup;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'log:edit { id : the id of the time log entry you want to edit }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit a time log entry ';

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

        $this->info("Found Entry for Project: " . $entry->project->detailed_title);

        $updates = [];

        if ($this->confirm("Would you like the change the start time from " . $entry->local_started_at->format('M j, Y g:i a') . '?')) {
            $new_started_at = Carbon::createFromFormat(
                "Y-m-d H:i",
                $this->ask("Please enter the new start date/time in format Y-m-d H:i (local timezone)"),
                config('app.user_timezone'));
            if ($new_started_at !== false) {
                $updates['started_at'] = $new_started_at->timezone('UTC');
            }
        }

        if ($this->confirm("Would you like the change the end time from " . $entry->local_ended_at->format('M j, Y g:i a') . '?')) {
            $new_ended_at = Carbon::createFromFormat(
                "Y-m-d H:i",
                $this->ask("Please enter the new end date/time in format Y-m-d H:i (local timezone)"),
                config('app.user_timezone'));
            if ($new_ended_at !== false) {
                $updates['ended_at'] = $new_ended_at->timezone('UTC');
            }
        }


        if ($this->confirm("Would you like to edit the notes on this entry?")) {
            $updates['notes'] = $this->ask("Please enter notes for this entry");
        }

        $entry->update($updates);
        $this->info("Entry Updated!");
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
