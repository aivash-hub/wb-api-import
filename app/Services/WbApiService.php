<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WbApiService
{
    private string $baseUrl = 'http://109.73.206.144:6969/api';
    private string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function getOrders(int $page = 1, int $limit = 500): array
    {
        $response = Http::get("{$this->baseUrl}/orders", [
            'page' => $page,
            'limit' => $limit,
            'key' => $this->apiKey,
            'dateFrom' => '2000-01-01',
        ]);

        return $response->json();
    }
}
