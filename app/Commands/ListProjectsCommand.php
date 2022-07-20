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
        $this->table(['ID', 'Name', 'Short Code'], Project::orderBy('name', 'ASC')->select(['id', 'name', 'short_code'])->get()->toArray());
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
