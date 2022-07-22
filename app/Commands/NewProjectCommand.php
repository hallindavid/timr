<?php

namespace App\Commands;

use App\Project;
use App\Traits\RequiresSetup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class NewProjectCommand extends Command
{
    use RequiresSetup;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'project:new {name? : The name of the project} {shortCode? : The short code of the project, up to 3 letters }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // Determine or prompt user for the new projects name
        if (!empty($this->argument('name'))) {
            $name = trim($this->argument('name'));
        } else {
            $name = trim($this->ask('What should the project be called?  (eg. Acme Corp Project)'));
        }

        // Stop execution if user has not set a project name
        if (empty($name)) {
            $this->error('Unable to create project without a name');
            return 1;
        }


        // Determine or prompt user if they would like a project short code & what it should be
        $short_code = null;
        if (!empty($this->argument('shortCode'))) {
            $short_code = trim($this->argument('shortCode'));
        } else {
            if ($this->confirm('Would you like to add a short code (acronym/initials etc. to make referencing easier)')) {
                $short_code = trim($this->ask('What should the short code be? (eg. acp for Acme Corp Project)'));
            }
        }

        // Inform user of successful input validation
        $this->info("Now creating project $name" . (is_null($short_code) ? '' : ' (' . $short_code . ')'));

        $project = Project::create([
            'name' => $name,
            'short_code' => $short_code,
        ]);

        // Inform user a completed project creation
        $this->info(sprintf("Project: %s created.", $project->detailed_title));

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
