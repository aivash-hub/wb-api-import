<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImportIncomesService;

class ImportIncomesCommand extends Command
{
    protected $signature = 'wb:import-incomes';

    protected $description = 'Import incomes from WB API';

    public function handle(ImportIncomesService $service)
    {
        $this->info('Starting incomes import...');

        $count = $service->import();

        $this->info("Imported {$count} incomes.");
    }
}
