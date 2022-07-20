<?php

namespace App\Commands;

use App\Project;
use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class StartProjectCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'project:start {shortCode? : the project short code}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'begin tracking time on a project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Warn user of projects that are already in progress
        $project_in_progress = $this->check_if_there_is_already_project_being_tracked();

        if (!empty($project_in_progress)) {
            $this->error("You have a project that is already in progress.  " . $project_in_progress->detailed_title);
            if (!$this->confirm("Would you like to proceed?")) {
                return 0;
            }
        }

        // Determine or prompt for the project to begin work on
        if (empty($this->argument("shortCode"))) {
            $project = $this->select_project();
        } else {
            $project = Project::firstWhere('short_code', $this->argument('short_code'));
        }

        // Halt execution if no project selected
        if (empty($project)) {
            $this->error('Unable to find project');
            return 1;
        }

        // Create the time log for the project
        $project->time_logs()->create([
            'started_at' => now(),
        ]);

        // Inform user of successful creation
        $this->info("Started tracking project: " . $project->detailed_title);

        return 0;
    }

    public function check_if_there_is_already_project_being_tracked()
    {
        $log = TimeLog::open()->first();
        if (!empty($log)) {
            return $log->project;
        }
        return null;
    }

    public function select_project()
    {
        $this->table(['ID', 'Name', 'Short Code'], Project::orderBy('name', 'ASC')->select(['id', 'name', 'short_code'])->get()->toArray());
        $search_input = trim($this->ask('Which project would you like to begin tracking? (short code or ID)'));

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
