<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LifeEvent;
use App\Models\Week;
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
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $today = CarbonImmutable::now($timezone);
        $events = LifeEvent::getLifeEventDateMapping();
        $thisWeek = Week::current();

        // Calculate time left
        $weekNumber = $thisWeek->id;
        $weeksLeft = Week::all()->last()->id - $thisWeek->id;
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $timeUntil30 = $events['age30']->diffAsCarbonInterval($today);
        $timeUntil50 = $events['midLife']->diffAsCarbonInterval($today);
        $age = $today->diffAsCarbonInterval($events['birth']);
        $thisWeekTimeLeft = $thisWeek->end->diffAsCarbonInterval($today);
        
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

        $this->info('Time Left');
        $this->info('-----------------');
        $this->info("Age\t\t\t" . '=> ' . $age->forHumans(['parts' => 4]));
        $this->info("Time Until Birthday\t" . '=> ' . $timeUntilBirthday->forHumans(['parts' => 4]));
        $this->info("Time Until 30\t\t" . '=> ' . $timeUntil30->forHumans(['parts' => 4]));
        $this->info("Time Until 50\t\t" . '=> ' . $timeUntil50->forHumans(['parts' => 4]));
        $this->info("Time Left\t\t" . '=> ' . $timeLeft->forHumans(['parts' => 4]));
        $this->info("Percent Complete\t" . '=> ' . $percentComplete . "%");

        $this->info("\nWeeks");
        $this->info('-----------------');
        $this->info("Week Time Left\t\t" . '=> ' . $thisWeekTimeLeft->forHumans(['parts' => 4]));
        $this->info("Week Number\t\t" . '=> ' . $weekNumber);
        $this->info("Weeks Left\t\t" . '=> ' . $weeksLeft);
    }
}
