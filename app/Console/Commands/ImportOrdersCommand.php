<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportOrdersService;

class ImportOrdersCommand extends Command
{
    protected $signature = 'wb:import-orders';

    protected $description = 'Import orders from WB API';

    public function handle(ImportOrdersService $importOrdersService)
    {
        $this->info('Starting orders import...');

        $count = $importOrdersService->import();

        $this->info("Imported {$count} orders.");
    }
}
