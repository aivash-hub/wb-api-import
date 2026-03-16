<?php

namespace App\Services;

use App\Models\Income;
use Carbon\Carbon;

class ImportIncomesService
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
                $response = $this->wbApiService->getIncomes(
                    $dateFrom,
                    $dateTo,
                    $page,
                    $limit
                );
            } catch (\Throwable $e) {
                logger()->error('WB API incomes import failed', [
                    'page' => $page,
                    'error' => $e->getMessage(),
                ]);

                break;
            }

            $incomes = $response['data'] ?? [];

            if (empty($incomes)) {
                break;
            }

            $rows = [];

            foreach ($incomes as $income) {
                $rows[] = [
                    'income_id' => $income['income_id'] ?? null,
                    'date' => $income['date'] ?? null,
                    'supplier_article' => $income['supplier_article'] ?? null,
                    'barcode' => $income['barcode'] ?? null,
                    'total_price' => $income['total_price'] ?? null,
                    'quantity' => $income['quantity'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Income::upsert(
                $rows,
                ['income_id'],
                [
                    'date',
                    'supplier_article',
                    'barcode',
                    'total_price',
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
