<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Week;
use Carbon\CarbonImmutable;
use App\Models\Goal;
use App\Models\Task;
use App\Modules\LifeStatistics;

class QuickStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:quick-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays in terminal on each login if enabled in scripts/aliases';

    private function listTimeStats()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $today = CarbonImmutable::now($timezone);
        $events = LifeStatistics::getLifeEventDateMapping();
        $thisWeek = Week::current();

        // Calculate time left
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $thisWeekTimeLeft = $thisWeek->end->diffAsCarbonInterval($today);
        $thisDayTimeLeft = $today->endOfDay()->diffAsCarbonInterval($today);

        $age = $today->diffAsCarbonInterval($events['birth']);
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $totalLife = $events['death']->diffInDays($events['birth']);
        $lifeLived = $today->diffInDays($events['birth']);
        $percentComplete = round($lifeLived / $totalLife * 100, 4);



        // Lifetime
        $this->comment('Life');
        $life = [
                "Age" => $age->forHumans(['parts' => 3]) . ' ' . "($percentComplete%)",
                "Life Left" => $timeLeft->forHumans(['parts' => 4]),
                'Time Left Today' => $thisDayTimeLeft->forHumans(['parts' => 3]),
                "Time Left This Week" => $thisWeekTimeLeft->forHumans(['parts' => 3]),
            ];

        $tabs = 3;
        foreach($life as $key => $value) {
            $tabString = $tabs > 0 ? str_repeat("\t", $tabs) : "\t";
            $this->line("\t<fg=green> $key </>" . $tabString . $value);
            $tabs--;
        }

        $this->newLine();
    }

    private function listGoals()
    {
        $work = Goal::work()->get();
        $personal = Goal::personal()->get();

        function buildGoalDetails($goal)
        {
            $timeLeft = $goal->time_left->forHumans(['parts' => 4]);
            $dateString = $goal->due_date ? (' | ' . $goal->due_date->toFormattedDayDateString() . ' | ' . $timeLeft) : '';
            $goalDetails = $goal->title . $dateString;
            return $goalDetails;
        }

        if ($work->count() === 0 && $personal->count() === 0) {
            $this->line("No goals yet");
        } else {
            $this->comment('Goals');

            // Personal
            if ($personal->count() !== 0) {
                $this->line("\t<fg=green> Personal </>");
            }
            foreach($personal as $goal) {
                $goalDetails = buildGoalDetails($goal);
                $this->line("\t  $goalDetails");
            }

            // Work
            if ($work->count() !== 0) {
                $this->line("\t<fg=green> Work </>");
            }
            foreach($work as $goal) {
                $goalDetails = buildGoalDetails($goal);
                $this->line("\t  $goalDetails");
            }
        }

        $this->newLine();
    }

    private function listTasks()
    {
        $work = Task::work()->get();
        $personal = Task::personal()->get();

        function buildTaskDetails($task)
        {
            $timeLeft = $task->time_left->forHumans(['parts' => 4]);
            $dateString = $task->due_date ? (' | ' . $task->due_date->toFormattedDayDateString() . ' | ' . $timeLeft) : '';
            $taskDetails = $task->title . $dateString;
            return $taskDetails;
        }

        if ($work->count() === 0 && $personal->count() === 0) {
            $this->line("No tasks yet");
        } else {
            $this->comment('Tasks');

            // Personal
            if ($personal->count() !== 0) {
                $this->line("\t<fg=green> Personal </>");
            }
            foreach($personal as $task) {
                $taskDetails = buildTaskDetails($task);
                $this->line("\t  $taskDetails");
            }

            // Work
            if ($work->count() !== 0) {
                $this->line("\t<fg=green> Work </>");
            }
            foreach($work as $task) {
                $taskDetails = buildTaskDetails($task);
                $this->line("\t  $taskDetails");
            }
        }

        $this->newLine();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('MEMENTO MORI');
        $this->newLine();

        $this->line('Run `forward` to start app.');
        $this->newLine();

        $this->listTimeStats();

        $this->listGoals();

        $this->listTasks();

        $this->newLine();
    }
}
