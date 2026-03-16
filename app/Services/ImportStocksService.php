<?php

namespace App\Services;

use App\Models\Stock;
use Carbon\Carbon;

class ImportStocksService
{
    public function __construct(
        private WbApiService $wbApiService
    ) {}

    public function import(): int
    {
        $page = 1;
        $limit = 500;
        $imported = 0;

        $dateFrom = Carbon::now()->toDateString();

        do {

            try {
                $response = $this->wbApiService->getStocks(
                    $dateFrom,
                    $page,
                    $limit
                );
            } catch (\Throwable $e) {
                logger()->error('WB API stocks import failed', [
                    'page' => $page,
                    'error' => $e->getMessage(),
                ]);

                break;
            }

            $stocks = $response['data'] ?? [];

            if (empty($stocks)) {
                break;
            }

            $rows = [];

            foreach ($stocks as $stock) {
                $rows[] = [
                    'date' => $stock['date'] ?? null,
                    'supplier_article' => $stock['supplier_article'] ?? null,
                    'tech_size' => $stock['tech_size'] ?? null,
                    'barcode' => $stock['barcode'] ?? null,
                    'quantity' => $stock['quantity'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Stock::upsert(
                $rows,
                ['barcode'],
                [
                    'date',
                    'supplier_article',
                    'tech_size',
                    'quantity',
                    'updated_at',
                ]
            );

            $imported += count($rows);

            $page++;

        } while (true);

        return $imported;
    }
}
