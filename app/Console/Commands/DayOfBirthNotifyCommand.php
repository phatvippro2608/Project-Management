<?php

namespace App\Console\Commands;

use App\Models\EmployeeModel;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DayOfBirthNotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $employees = EmployeeModel::whereRaw('MONTH(date_of_birth) = ?', [$currentMonth])
            ->whereRaw('DAY(date_of_birth) = ?', [$currentDay])
            ->get();

        Log::info($employees);
        return EmployeeModel::where('date_of_birth','>',0)->get();
    }
}
