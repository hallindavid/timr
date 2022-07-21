<?php

namespace App\Commands;

use App\Project;
use App\TimeLog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DeleteProjectCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'project:delete {id : the project ID }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete a project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Project::find($this->argument('id'));

        if (empty($project)) {
            $this->error('Project not found');
            return 1;
        }


        if ($this->confirm(sprintf("Are you sure you want to delete this project? %s", $project->detailed_title))) {
            TimeLog::where('project_id', $project->id)->delete();
            $project->delete();
            $this->info('Project Deleted');
        }
        return 0;
    }

    public function schedule(Schedule $schedule): void
    {
    }
}
