<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class ImportOrdersService
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
            $response = $this->wbApiService->getOrders(
                $dateFrom,
                $dateTo,
                $page,
                $limit
            );

            $orders = $response['data'] ?? [];

            if (empty($orders)) {
                break;
            }

            $rows = [];

            foreach ($orders as $order) {
                $rows[] = [
                    'g_number' => $order['g_number'] ?? null,
                    'date' => isset($order['date'])
                        ? Carbon::parse($order['date'])
                        : null,
                    'supplier_article' => $order['supplier_article'] ?? null,
                    'tech_size' => $order['tech_size'] ?? null,
                    'barcode' => $order['barcode'] ?? null,
                    'total_price' => $order['total_price'] ?? null,
                    'discount_percent' => $order['discount_percent'] ?? null,
                    'warehouse_name' => $order['warehouse_name'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Order::upsert(
                $rows,
                ['g_number'], // уникальный ключ
                [
                    'date',
                    'supplier_article',
                    'tech_size',
                    'barcode',
                    'total_price',
                    'discount_percent',
                    'warehouse_name',
                    'updated_at',
                ]
            );

            $imported += count($rows);

            $page++;

        } while (true);

        return $imported;
    }
}
