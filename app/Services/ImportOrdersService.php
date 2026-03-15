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

        do {
            $orders = $this->wbApiService->getOrders($page, $limit);

            foreach ($orders['data'] ?? [] as $order) {
                Order::create([
                    'g_number' => $order['g_number'] ?? null,
                    'date' => isset($order['date']) ? Carbon::parse($order['date']) : null,
                    'supplier_article' => $order['supplier_article'] ?? null,
                    'tech_size' => $order['tech_size'] ?? null,
                    'barcode' => $order['barcode'] ?? null,
                    'total_price' => $order['total_price'] ?? null,
                    'discount_percent' => $order['discount_percent'] ?? null,
                    'warehouse_name' => $order['warehouse_name'] ?? null,
                ]);

                $imported++;
            }

            $page++;

        } while (!empty($orders['data']));

        return $imported;
    }
}
