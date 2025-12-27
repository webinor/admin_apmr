<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeDatabaseIndexes extends Command
{
    protected $signature = 'db:optimize-indexes';
    protected $description = 'Create missing performance indexes';

    public function handle()
    {
        $indexes = [
            "CREATE INDEX idx_assistances_flight_date 
             ON assistances(flight_date)",

            "CREATE INDEX idx_assistances_signature 
             ON signatures(model_id, model_type)",

            "CREATE INDEX idx_assistance_lines_wheel_chair 
             ON assistance_lines(wheel_chair_id)",

            "CREATE INDEX idx_assistance_lines_assistance 
             ON assistance_lines(assistance_id)",
        ];

        foreach ($indexes as $sql) {
            try {
                DB::statement($sql);
                $this->info('✔ Index created');
            } catch (\Throwable $e) {
                $this->warn('⚠ Index already exists or error: ' . $e->getMessage());
            }
        }

        $this->info('🚀 Database optimization done.');
    }
}
