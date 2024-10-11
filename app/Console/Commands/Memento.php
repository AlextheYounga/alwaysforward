<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\CarbonImmutable;
use App\Models\LifeEvent;
use App\Models\Task;
use App\Models\LifeStatistics;

class Memento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:memento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays in terminal on each login if enabled in ./aliases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('MEMENTO MORI');
        $this->newLine();
        $this->line('Link to Board: <fg=cyan>' . env('APP_URL') . ':' . env('APP_PORT') . '/board </>');
        $this->line('Try to track your time: <fg=cyan>https://track.toggl.com/timer </>');
        $this->newLine();
        $this->listTimeStats();
        $this->listLifeEvents();
        $this->listTasks();
        $this->newLine();
    }

    private function listTimeStats()
    {
        $today = CarbonImmutable::now();
        $events = LifeStatistics::getLifeEventDateMapping();

        // Calculate time left
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $thisDayTimeLeft = $today->endOfDay()->diffAsCarbonInterval($today);

        $age = $today->diffAsCarbonInterval($events['birth']);
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $totalLife = $events['death']->diffInDays($events['birth']);
        $lifeLived = $today->diffInDays($events['birth']);
        $percentComplete = round($lifeLived / $totalLife * 100, 4);

        // Lifetime
        $this->comment('Life');
        $life = [
                "Age" => $age->forHumans(['parts' => 4]) . ' ' . "($percentComplete%)",
                "Life Left" => $timeLeft->forHumans(['parts' => 4]),
                'Time Left Today' => $thisDayTimeLeft->forHumans(['parts' => 4]),
            ];

        $tabs = 3;
        foreach($life as $key => $value) {
            $tabString = $tabs > 0 ? str_repeat("\t", $tabs) : "\t";
            $this->line("\t<fg=green> $key </>" . $tabString . $value);
            $tabs--;
        }

        $this->newLine();
    }

    private function listTasks()
    {
        $tasks = Task::ongoing()
            ->orderBy('created_at', 'desc')
            ->with('goal')
            ->get();

        if ($tasks->count() === 0) {
            $this->line("No tasks yet");
        } else {
            $this->comment('Tasks');
            $this->newLine();

            $tableData = $this->buildTaskTableData($tasks);
            $this->table($tableData[0], $tableData[1], 'compact');
        }

        $this->newLine();
    }

    private function listLifeEvents()
    {
        $this->comment('Events');
        $events = LifeEvent::upcoming()->get();

        if ($events->count() === 0) {
            $this->line("\tNo events yet");
        } else {
            $taskDetails = $this->buildEventTable($events);
            $this->table(null, $taskDetails, 'compact');
        }


        $this->newLine();
    }

    private function buildTaskTableData($items)
    {
        $headers = ['Title', 'Type', 'Due Date', 'Goal', 'Goal Due Date'];
        $data = $items->map(function ($item) {
            $taskTitle = '<fg=cyan>' . $item->title . '</>';
            $taskDueDate = $item->due_date ? CarbonImmutable::parse($item->due_date)->toFormattedDayDateString() : '';
            $taskTimeLeft = $this->colorizeTimeLeft($item->time_left);
            $taskDateField = $taskDueDate !== '' ? $taskDueDate . ' (' . $taskTimeLeft . ')' : '';

            $goal = $item->goal;
            $goalTitle = $goal ? $goal->title : '';
            $goalDueDate = ($goal && $goal->due_date) ?
                CarbonImmutable::parse($goal->due_date)->toFormattedDayDateString() :
                '';
            $goalTimeLeft = $goal ? $this->colorizeTimeLeft($goal->time_left) : '';
            $goalDateField = $goalDueDate !== '' ? $goalDueDate . ' (' . $goalTimeLeft . ')' : '';
            
            return [
                $taskTitle . "  ",
                $item->type->value . "  ",
                $taskDateField . "  ",
                $goalTitle . "  ",
                $goalDateField . "  "
            ];
        });

        return [
            $headers,
            $data
        ];
    }

    private function buildEventTable($events)
    {

        return $events->map(function ($event) {
            $date = CarbonImmutable::parse($event->date)->toFormattedDayDateString();
            $timeLeft = $event->time_left->forHumans(['parts' => 4]);

            return [
                "\t" . $event->title,
                "\t" . $date,
                "\t" . $timeLeft,
            ];
        });
    }

    private function colorizeTimeLeft($timeLeft)
    {
        if (!$timeLeft) {
            return '';
        }

        $timeLeftHumanized = $timeLeft->forHumans(['parts' => 3]);
        $hoursLeft = $timeLeft->totalHours;
        $overDue = $hoursLeft < 0;
        $color = '';

        if ($hoursLeft > 24 && $hoursLeft < 48) {
            $color = '<fg=yellow>';
        }
        if ($hoursLeft < 24) {
            $color = '<fg=red>';
        }

        $colorBreak = $color === '' ? '' : '</>';
        $dateString = $overDue ? '-' . $timeLeftHumanized : $timeLeftHumanized;

        return $color . $dateString . $colorBreak;
    }
}
