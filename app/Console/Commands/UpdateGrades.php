<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;

class RunUpdateGrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-grades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates grades and echelons for employees based on certain conditions.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // استدعاء الإجراء المخزن
            DB::statement('CALL update_grades_and_echelons()');
            $this->info('Grades and echelons have been updated successfully!');
        } catch (\Exception $e) {
            // سجل الخطأ مع مزيد من التفاصيل
            Log::error('Error executing stored procedure: ' . $e->getMessage(), [
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            $this->error('An error occurred while updating grades and echelons. Please check the logs for details.');
        }
    }
}
