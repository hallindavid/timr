<?php

namespace App\Commands;

use App\Helpers\MinuteHelper;
use App\TimeLog;
use App\Traits\RequiresSetup;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class EndProjectCommand extends Command
{
    use RequiresSetup;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stop { --round=5 : round out the end time to the nearest interval here }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'stop tracking time on a project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get open time log entries
        $time_logs = TimeLog::with(['project'])
            ->whereNull('ended_at')
            ->orderBy('started_at', 'ASC')
            ->get();


        // Halt execution if there are no projects being tracked
        if ($time_logs->count() == 0) {
            $this->error("There are no projects that are currently being worked on");
            return 1;
        }


        // Check to see how many open projects there are
        if ($time_logs->count() > 1) {
            // Prompt user for which project they'd like to stop tracking
            $project_selection = [];
            foreach ($time_logs as $log) {
                $project_selection[] = $log->project->detailed_title;
            }
            $project_selection[] = "None";

            $selected = $this->choice("Which Project would you like to stop tracking?", $project_selection, 0);

            if ($selected == "None") {
                $this->info("Ok - No project ended");
                return 0;
            }

            $time_log = $time_logs->filter(function ($log) use ($selected) {
                return $log->project->detailed_title == $selected;
            })->first();
        } else {
            $time_log = $time_logs->first();
        }
        // Prompt user for note about entry
        $note = $this->ask("Would you like to leave a note on this entry to say what you worked on?");

        // Handle Rounding
        $round = $this->option('round');
        $ended_at = now();
        $ended_at->setMinutes((ceil($ended_at->format('i') / $round) * $round));
        $ended_at->setSeconds(0);

        $time_log->update([
            'ended_at' => $ended_at,
            'notes' => $note,
        ]);

        $this->info(sprintf("Stopped tracking project: %s at %s.  [Entry Length: %s]",
            $time_log->project->detailed_title,
            $ended_at->timezone(config('app.user_timezone'))->format('g:i a'),
            MinuteHelper::format_minutes((strtotime($time_log->fresh()->ended_at->format('Y-m-d H:i:s')) - strtotime($time_log->started_at)) / 60),
        ));

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
