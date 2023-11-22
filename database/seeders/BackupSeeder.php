<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = Storage::disk('public')->get('backup.json');
        if (!empty($data)) {
            $data = json_decode($data, true);
            
            try {
                DB::table('boards')->insert($data['boards']);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            try {
                DB::table('goals')->insert($data['goals']);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            try {
                DB::table('tasks')->insert($data['tasks']);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            try {
                DB::table('task_week')->insert($data['week_tasks']);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

    }
}
