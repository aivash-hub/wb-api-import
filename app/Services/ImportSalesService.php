<?php

namespace App\Services;

use App\Models\Sale;
use Carbon\Carbon;

class ImportSalesService
{
    public function __construct(
        private WbApiService $wbApiService
    ) {}

    public function import(): int
    {
        $page = 1;
        $limit = 500;
        $imported = 0;

        $dateFrom = Carbon::now()->subDays(30)->toDateString();
        $dateTo = Carbon::now()->toDateString();

        do {

            try {
                $response = $this->wbApiService->getSales(
                    $dateFrom,
                    $dateTo,
                    $page,
                    $limit
                );
            } catch (\Throwable $e) {
                logger()->error('WB API sales import failed', [
                    'page' => $page,
                    'error' => $e->getMessage(),
                ]);

                break;
            }

            $sales = $response['data'] ?? [];

            if (empty($sales)) {
                break;
            }

            $rows = [];

            foreach ($sales as $sale) {
                $rows[] = [
                    'g_number' => $sale['g_number'] ?? null,
                    'date' => $sale['date'] ?? null,
                    'supplier_article' => $sale['supplier_article'] ?? null,
                    'tech_size' => $sale['tech_size'] ?? null,
                    'barcode' => $sale['barcode'] ?? null,
                    'total_price' => $sale['total_price'] ?? null,
                    'discount_percent' => $sale['discount_percent'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Sale::upsert(
                $rows,
                ['g_number'],
                [
                    'date',
                    'supplier_article',
                    'tech_size',
                    'barcode',
                    'total_price',
                    'discount_percent',
                    'updated_at',
                ]
            );

            $imported += count($rows);

            $page++;

        } while (true);

        return $imported;
    }
}
