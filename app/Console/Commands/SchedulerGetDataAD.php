<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MngUserDomainController;
use Illuminate\Support\Facades\Log;

class SchedulerGetDataAD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler Update list Data AD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $domain = app(MngUserDomainController::class);
        $domain->update();
        Log::info('Success Update Scheduler');
    }
}
