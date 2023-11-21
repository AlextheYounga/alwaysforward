<?php

namespace App\Console\Commands\Goal;

use App\Enums\GoalStatus;
use Illuminate\Console\Command;
use App\Models\Goal;

class MarkGoalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:mark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark a goal complete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $goals = Goal::active()->get();
        $goalArray = $goals->map(fn($goal) => $goal->title)->toArray();

        if ($goals) {
            $goalSelect = $this->choice("Mark a goal complete.", $goalArray, null);
            $goal = $goals->firstWhere('title', $goalSelect);

            $statuses = GoalStatus::values();
            $status = $this->choice("Mark a goal complete.", $statuses, null);

            $goal->status = GoalStatus::tryFrom($status);
            $goal->save();
        } else {
            $this->info("No goals yet");
            return;
        }
    }
}
