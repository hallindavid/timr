<?php

namespace App\Commands;

use App\Project;
use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\RequiresSetup;

class StartProjectCommand extends Command
{
    use RequiresSetup;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start {shortCode? : the project short code} { --round=5 : round out the start time to nearest interval here }';

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
            $project = Project::firstWhere('short_code', $this->argument('shortCode'));
        }

        // Halt execution if no project selected
        if (empty($project)) {
            $this->error('Unable to find project');
            return 1;
        }

        // Handle Rounding
        $started_at = now()->setSeconds(0);
        $round = $this->option('round');
        $started_at->setMinutes((floor($started_at->format('i') / $round) * $round));


        // Create the time log for the project
        $project->time_logs()->create([
            'started_at' => $started_at,
        ]);

        // Inform user of successful creation
        $this->info(sprintf("Started tracking project: %s as of %s", $project->detailed_title, $started_at->timezone(config('app.user_timezone'))->format('g:i a')));

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
