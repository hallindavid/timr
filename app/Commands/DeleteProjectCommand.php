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
    protected $signature = 'project:delete {--id= : the project ID } {--code= : the project short code }';

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
        $project = null;

        if (!empty($this->option("id"))) {
            $project_id = intval(trim($this->option("id")));
            $project = Project::find($project_id);
        }

        if (!empty($this->option("code")) && empty($project)) {
            $project_short_code = $this->option('code');
            $project = Project::where('short_code', $project_short_code)->first();
        }

        if (empty($project)) {
            $project = $this->select_project();
        }


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


    // Re-usable select project function
    public function select_project()
    {
        $this->table(['ID', 'Name', 'Short Code'], Project::orderBy('name', 'ASC')->select(['id', 'name', 'short_code'])->get()->toArray());
        $search_input = trim($this->ask('Which project would you like to delete? (short code or ID)'));

        if (is_numeric($search_input)) {
            $project = Project::find($search_input);
        } else {
            $project = Project::firstWhere('short_code', $search_input);
        }

        return $project;

    }

    public function schedule(Schedule $schedule): void
    {
    }
}
