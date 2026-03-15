<?php

namespace App\Services;

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
        return $this->request('/orders', [
            'page' => $page,
            'limit' => $limit,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    private function request(string $endpoint, array $params): array
    {
        $response = Http::retry(3, 100)
            ->timeout(10)
            ->get("{$this->baseUrl}{$endpoint}", array_merge(
                $params,
                ['key' => $this->apiKey]
            ));

        if (!$response->successful()) {
            throw new \RuntimeException(
                'WB API request failed with status ' . $response->status()
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \RuntimeException('WB API returned invalid JSON');
        }

        return $data;
    }
}
