<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SmartSeederCommand extends Command
{
    protected $signature = 'db:smart-seed';
    protected $description = 'Run new seeders by comparing the seeders directory with the logs table';

    public function handle()
    {
        $seederPath = database_path('seeders');
        $files = File::files($seederPath);
        
        // 1. Get all seeder class names from the directory
        $allSeeders = collect($files)
            ->map(fn($file) => $file->getBasename('.php'))
            ->filter(fn($name) => $name !== 'DatabaseSeeder'); // Skip the main wrapper

        // 2. Get already ran seeders from the DB
        $ranSeeders = DB::table('laravel_seeder_logs')->pluck('seeder')->toArray();

        // 3. Compare
        $toRun = $allSeeders->diff($ranSeeders);

        if ($toRun->isEmpty()) {
            $this->info('No new seeders to run.');
            return;
        }

        $batch = DB::table('laravel_seeder_logs')->max('batch') + 1;

        foreach ($toRun as $seederName) {
            $this->comment("Seeding: $seederName");

            // Execute the seeder
            $className = "Database\\Seeders\\$seederName";
            
            if (class_exists($className)) {
                $this->call('db:seed', ['--class' => $className]);

                // Record in log
                DB::table('laravel_seeder_logs')->insert([
                    'seeder' => $seederName,
                    'batch'  => $batch,
                    'ran_at' => now()
                ]);
                
                $this->info("Completed: $seederName");
            } else {
                $this->error("Class $className not found!");
            }
        }

        $this->info('Smart seeding finished successfully.');
    }
}
