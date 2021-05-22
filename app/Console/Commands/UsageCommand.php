<?php

namespace App\Console\Commands;

use App\Events\SchedulerEvent;
use App\Http\Controllers\SystemInfoController;
use Illuminate\Console\Command;
use Illuminate\Support\Str;


class UsageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $info = new SystemInfoController();
        $cpu = $info->getCpuUsage();
        $memory = $info->getMemoryUsage();
        $text =['cpu' => $cpu, 'ram' => $memory['usage']];
        event(new SchedulerEvent($text));

    }
}
