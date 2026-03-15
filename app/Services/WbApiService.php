<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WbApiService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.wb.api_url');
        $this->apiKey = config('services.wb.api_key');
    }

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
