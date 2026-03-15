<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use App\DTO\OrderDto;
use App\Mappers\OrderMapper;

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
            try {
                $response = $this->wbApiService->getOrders(
                    $dateFrom,
                    $dateTo,
                    $page,
                    $limit
                );
            } catch (\Throwable $e) {
                logger()->error('WB API import failed', [
                    'page' => $page,
                    'error' => $e->getMessage(),
                ]);

                break;
            }

            $orders = $response['data'] ?? [];

            if (empty($orders)) {
                break;
            }

            $rows = [];

            foreach ($orders as $order) {
                $dto = OrderDto::fromArray($order);

                $rows[] = OrderMapper::toDatabaseArray($dto);
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
