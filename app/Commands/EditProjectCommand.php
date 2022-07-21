<?php

namespace App\Commands;

use App\Project;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class EditProjectCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'project:edit { id : the id of the project you want to edit }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit project name and short code';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Project::find($this->argument("id"));

        if (empty($project)) {
            $this->error("Unable to find project");
            return 1;
        }

        $this->info("Found Project: " . $project->detailed_title);

        $new_project_name = $this->anticipate('What would you like the project name to be?', [$project->name]);
        $new_project_short_code = $this->anticipate("What would you like the short code to be? (max 4 chars)", [$project->short_code]);


        $project->update([
            'name' => $new_project_name,
            'short_code' => $new_project_short_code
        ]);

        $this->info("Project Updated!");
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
