<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\LifeStatistics;

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

        $headingMapping = [
            "age" => "Age" ,
            "timeLeft" => "Time Left",
            "percentComplete" => "Percent Complete",
            "timeUntilBirthday" => "Time Until Birthday" ,
            "timeUntil30" => "Time Until 30" ,
            "timeUntil50" => "Time Until 50" ,
            "weekTimeLeft" => "Week Time Left",
            "weekNumber" => "Week Number",
            "weeksLeft" => "Weeks Left",
            "timeLeftToday" => "Time Left Today",
        ];

        $statistics = LifeStatistics::calculateTimeLeftStatistics();
        
        foreach($statistics as $key => $value) {
            $this->info($headingMapping[$key]);
            $this->line($value);
            $this->newLine();
        }
    }
}
