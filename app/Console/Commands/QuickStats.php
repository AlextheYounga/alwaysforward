<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LifeEvent;
use App\Models\Week;
use Carbon\CarbonImmutable;
use App\Models\Goal;
use App\Models\Task;

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
        $events = LifeEvent::getLifeEventDateMapping();
        $thisWeek = Week::current();

        // Calculate time left
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $thisWeekTimeLeft = $thisWeek->end->diffAsCarbonInterval($today);
        $thisDayTimeLeft = $today->endOfDay()->diffAsCarbonInterval($today);
        
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $totalLife = $events['death']->diffInDays($events['birth']);
        $lifeLived = $today->diffInDays($events['birth']);
        $percentComplete = round($lifeLived / $totalLife * 100, 4);

        $thisYearBirthday = CarbonImmutable::create(
            $today->year, 
            $events['birth']->month, 
            $events['birth']->day,
            0, 0, 0
        )->timezone($timezone);

        $timeUntilBirthday = $thisYearBirthday->diffAsCarbonInterval($today);

        # Goals
        $goals = Goal::all(['title', 'due_date'])->toArray();
        $tasks = Task::all(['title', 'due_date'])->toArray();

        $this->info('Momento Mori');
        $this->info('-----------------');
        $this->info("This Day Left\t\t" . '=> ' . $thisDayTimeLeft->forHumans(['parts' => 4]));
        $this->info("Week Time Left\t\t" . '=> ' . $thisWeekTimeLeft->forHumans(['parts' => 4]));
        $this->info("Time Until Birthday\t" . '=> ' . $timeUntilBirthday->forHumans(['parts' => 4]));
        $this->info("Lifetime Left\t\t" . '=> ' . $timeLeft->forHumans(['parts' => 4]));
        $this->info("Percent Complete\t" . '=> ' . $percentComplete . "%");
        $this->info("");

        if (empty($goals)) {
            $this->info("No goals yet");
        } else {
            $this->info('Goals');
            $this->info('-----------------');

            foreach($goals as $goal) {
                $dueDate = CarbonImmutable::parse($goal['due_date'])->toFormattedDayDateString();
                $this->info($goal['title'] . ' => ' . $dueDate);
            }
            $this->info('');
        }

        if (empty($tasks)) {
            $this->info("No tasks yet");
        } else {
            $this->info('Tasks');
            $this->info('-----------------');

            foreach($tasks as $task) {
                $dueDate = CarbonImmutable::parse($task['due_date'])->toFormattedDayDateString();
                $this->info($task['title'] . '=> ' . $dueDate);
            }
        }
    }
}
