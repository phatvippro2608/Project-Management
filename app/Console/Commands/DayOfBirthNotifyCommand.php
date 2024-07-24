<?php

namespace App\Console\Commands;

use App\Models\EmployeeModel;
use Illuminate\Console\Command;

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
        EmployeeModel::where('date_of_birth','>',0)->get();
    }
}
