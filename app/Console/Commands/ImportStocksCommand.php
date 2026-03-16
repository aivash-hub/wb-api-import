<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportStocksService;

class ImportStocksCommand extends Command
{
    protected $signature = 'wb:import-stocks';

    protected $description = 'Import stocks from WB API';

    public function handle(ImportStocksService $service)
    {
        $this->info('Starting stocks import...');

        $count = $service->import();

        $this->info("Imported {$count} stocks.");
    }
}
