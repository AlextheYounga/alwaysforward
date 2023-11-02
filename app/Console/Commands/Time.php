<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LifeEvent;
use App\Models\Week;
use App\Models\Task;
use Carbon\CarbonImmutable;

class Time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'life:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Momento Mori';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = CarbonImmutable::now();
        $events = LifeEvent::getLifeEventDateMapping();
        $thisWeek = Week::getThisWeek();

        // Calculate time left
        $weekNumber = $thisWeek->id;
        $weeksLeft = Week::all()->last()->id - $thisWeek->id;
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $timeUntil30 = $events['age30']->diffAsCarbonInterval($today);
        $timeUntil50 = $events['midLife']->diffAsCarbonInterval($today);
        $age = $today->diffAsCarbonInterval($events['birth']);
        $thisWeekTimeLeft = $thisWeek->end->diffAsCarbonInterval($today);

        $thisYearBirthday = CarbonImmutable::create(
            $today->year, 
            $events['birth']->month, 
            $events['birth']->day,
            0, 0, 0
        );

        $timeUntilBirthday = $thisYearBirthday->diffAsCarbonInterval($today);

        $this->info('Time Left');
        $this->info('-----------------');
        $this->info("Age\t\t\t" . '=> ' . $age->forHumans(['parts' => 4]));
        $this->info("Time Until Birthday\t" . '=> ' . $timeUntilBirthday->forHumans(['parts' => 4]));
        $this->info("Time Until 30\t\t" . '=> ' . $timeUntil30->forHumans(['parts' => 4]));
        $this->info("Time Until 50\t\t" . '=> ' . $timeUntil50->forHumans(['parts' => 4]));
        $this->info("Time Left\t\t" . '=> ' . $timeLeft->forHumans(['parts' => 4]));

        $this->info("\nWeeks");
        $this->info('-----------------');
        $this->info("Week Time Left\t\t" . '=> ' . $thisWeekTimeLeft->forHumans(['parts' => 4]));
        $this->info("Week Number\t\t" . '=> ' . $weekNumber);
        $this->info("Weeks Left\t\t" . '=> ' . $weeksLeft);
    }
}
