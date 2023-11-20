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

    /**
     * Execute the console command.
     */
    public function handle()
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

        $thisYearBirthday = CarbonImmutable::create(
            $today->year,
            $events['birth']->month,
            $events['birth']->day,
            0,
            0,
            0
        )->timezone($timezone);

        if ($thisYearBirthday->isPast()) {
            $thisYearBirthday = $thisYearBirthday->addYear();
        }

        $timeUntilBirthday = $thisYearBirthday->diffAsCarbonInterval($today);

        $this->info('MEMENTO MORI');
        $this->newLine();

        $this->line('Run `forward` to start goals app.');
        $this->newLine();

        // Lifetime
        $rows = [[
                "Age" => $age->forHumans(['parts' => 3]) . ' ' . "($percentComplete%)" . '   ',
                "Birthday" => $timeUntilBirthday->forHumans(['parts' => 4]) . '   ',
                "Life Left" => $timeLeft->forHumans(['parts' => 4]) . '   '
            ]];

        $this->table(
            ['Age', 'Birthday', 'Life Left'],
            $rows,
            'compact',
        );
        $this->newLine();

        // This Week
        $rows = [[
            'Time Left Today' => $thisDayTimeLeft->forHumans(['parts' => 3]) . '   ',
            "Time Left This Week" => $thisWeekTimeLeft->forHumans(['parts' => 3]) . '   ',
            ]];

        $this->table(
            ['Time Left Today', 'Time Left This Week'],
            $rows,
            'compact'
        );
        $this->newLine();

        // Goals
        $goals = Goal::all();
        if (empty($goals)) {
            $this->line("No goals yet");
        } else {
            $this->info('Goals');
            foreach($goals as $goal) {
                $timeLeft = $goal->time_left->forHumans(['parts' => 4]);
                $dateString = $goal->due_date ? (' | ' . $goal->due_date->toFormattedDayDateString() . ' | ' . $timeLeft) : '';
                $output = $goal->title . $dateString;
                $this->line($output);
            }
        }

        $this->newLine();

        // Tasks
        $tasks = Task::current();
        if (empty($tasks)) {
            $this->line("No tasks yet");
        } else {
            $this->info('Tasks');
            foreach($tasks as $task) {
                $timeLeft = $task->time_left->forHumans(['parts' => 3]);
                $dateString = $task->due_date ? (' | ' . $goal->due_date->toFormattedDayDateString() . ' | ' . $timeLeft) : '';
                $output = $task->title . $dateString;
                $this->line($output);
            }
        }
    }
}
