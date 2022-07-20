<?php

namespace App\Commands;

use App\Project;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ListProjectsCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'project:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Output a list of the projects in the system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table(['ID', 'Name', 'Short Code', 'This Week', 'Last 30 Days', 'All Time', 'Last Entry'],
            Project::orderBy('name', 'ASC')->get()->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'short_code' => $project->short_code,
                    'this_week' => $project->this_week,
                    'last 30 days' => $project->last_thirty,
                    'all_time' => $project->all_time,
                    'last_entry' => $project->last_entry,
                ];
            })->toArray());
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
