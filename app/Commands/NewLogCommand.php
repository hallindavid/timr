<?php

namespace App\Commands;

use App\Project;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use LaravelZero\Framework\Commands\Command;

class NewLogCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'log:new {shortCode? : the project short code}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'create an entry for for a project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (empty($this->argument("shortCode"))) {
            $project = $this->select_project();
        } else {
            $project = Project::firstWhere('short_code', $this->argument('shortCode'));
        }

        // Halt execution if no project selected
        if (empty($project)) {
            $this->error('Unable to find project');
            return 1;
        }

        $date_of_entry = false;
        while (!$date_of_entry) {
            $date_of_entry = date_create_from_format("Y-m-d", $this->ask("Please enter the date of the entry in format YYYY-MM-DD"));
        }


        $new_started_at = Carbon::createFromFormat(
            "Y-m-d H:i",
            $date_of_entry->format('Y-m-d') . ' ' . $this->ask("Please enter the start time in format HH:MM (local timezone, 24 hour time)"),
            config('app.user_timezone'));

        if ($new_started_at !== false) {
            $updates['started_at'] = $new_started_at->timezone('UTC');
        }

        $new_ended_at = Carbon::createFromFormat(
            "Y-m-d H:i",
            $date_of_entry->format('Y-m-d') . ' ' . $this->ask("Please enter the end time in format HH:MM (local timezone, 24 hour time)"),
            config('app.user_timezone'));


        // If times cross over midnight, rollover the next day
        if ($new_ended_at < $new_started_at) {
            $new_ended_at->addDay();
        }

        if ($new_ended_at !== false) {
            $updates['ended_at'] = $new_ended_at->timezone('UTC');
        }

        $updates['notes'] = $this->ask("Please enter notes for this entry");


        $project->time_logs()->create($updates);
        $this->info("Entry Created");
        return 0;

    }

    // Re-usable select project function
    public function select_project()
    {
        $this->table(['ID', 'Name', 'Short Code'], Project::orderBy('name', 'ASC')->select(['id', 'name', 'short_code'])->get()->toArray());
        $search_input = trim($this->ask('Which project would you like to add an entry to? (short code or ID)'));

        if (is_numeric($search_input)) {
            $project = Project::find($search_input);
        } else {
            $project = Project::firstWhere('short_code', $search_input);
        }
        return $project;
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
