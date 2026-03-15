<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WbApiService
{
    private string $baseUrl = 'http://109.73.206.144:6969/api';
    private string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function getOrders(
        string $dateFrom,
        string $dateTo,
        int $page = 1,
        int $limit = 500
    ): array {
        $response = Http::retry(3, 100)
            ->timeout(10)
            ->get("{$this->baseUrl}/orders", [
                'page' => $page,
                'limit' => $limit,
                'key' => $this->apiKey,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
            ]);

        return $response->json();
    }
}
