<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Week;
use Carbon\CarbonImmutable;
use App\Models\Goal;
use App\Models\Task;
use App\Modules\LifeStatistics;

class Overview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:overview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays in terminal on each login if enabled in scripts/aliases';

    private function buildTableRows($items)
    {

        return $items->map(function ($item) {
            $dueDate = $item->due_date? CarbonImmutable::parse($item->due_date, env('APP_TIMEZONE'))->toFormattedDayDateString() : '';
            $timeLeft = $item->time_left->forHumans(['parts' => 4]);
            $hoursLeft = $item->time_left->totalHours;
            $overdue = $hoursLeft < 0;
            $color = '';

            if ($hoursLeft > 24 && $hoursLeft < 48) {
                $color = '<fg=yellow>';
            }
            if ($hoursLeft < 24) {
                $color = '<fg=red>';
            }
            $colorBreak = $color === '' ? '' : '</>';

            return [
                "\t  " . $item->title,
                "\t" . $dueDate,
                "\t" . $color  . ($overdue ? '(Overdue) -' : '') .  $timeLeft . $colorBreak,
            ];
        });
    }

    private function listTimeStats()
    {
        $today = CarbonImmutable::now();
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
        $work = Goal::work()->active()->get();
        $personal = Goal::personal()->active()->get();

        if ($work->count() === 0 && $personal->count() === 0) {
            $this->line("No goals yet");
        } else {
            $this->comment('Goals');

            // Personal
            if ($personal->count() !== 0) {
                $this->line("\t<fg=green> Personal </>");
            }

            $goalDetails = $this->buildTableRows($personal);
            $this->table(null, $goalDetails, 'compact');

            // Work
            if ($work->count() !== 0) {
                $this->line("\t<fg=green> Work </>");
            }

            $goalDetails = $this->buildTableRows($work);
            $this->table(null, $goalDetails, 'compact');
        }

        $this->newLine();
    }

    private function listTasks()
    {
        $work = Task::work()->active()->get();
        $personal = Task::personal()->active()->get();

        if ($work->count() === 0 && $personal->count() === 0) {
            $this->line("No tasks yet");
        } else {
            $this->comment('Tasks');

            // Personal
            if ($personal->count() !== 0) {
                $this->line("\t<fg=green> Personal </>");
            }

            $taskDetails = $this->buildTableRows($personal);
            $this->table(null, $taskDetails, 'compact');

            // Work
            if ($work->count() !== 0) {
                $this->line("\t<fg=green> Work </>");
            }

            $taskDetails = $this->buildTableRows($work);
            $this->table(null, $taskDetails, 'compact');
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