<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportSalesService;

class ImportSalesCommand extends Command
{
    protected $signature = 'wb:import-sales';

    protected $description = 'Import sales from WB API';

    public function handle(ImportSalesService $service)
    {
        $this->info('Starting sales import...');

        $count = $service->import();

        $this->info("Imported {$count} sales.");
    }
}
