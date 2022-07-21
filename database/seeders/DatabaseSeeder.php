<?php

namespace Database\Seeders;

use App\Project;
use App\TimeLog;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Project::factory(10)->create();
        foreach (Project::all() as $project) {
            for ($i = 0; $i <= rand(20, 50); $i++) {
                $project->time_logs()->save(
                    TimeLog::factory()->make()
                );
            }
        }
    }
}
