<?php

namespace App\Console\Commands;

use App\Models\Heroes\Backup;
use Illuminate\Console\Command;

class ClearBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:backup';

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
        Backup::truncate();
    }
}
