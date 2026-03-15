<?php

namespace App\Mappers;

use App\DTO\OrderDto;

class OrderMapper
{
    public static function toDatabaseArray(OrderDto $dto): array
    {
        return [
            'g_number' => $dto->gNumber,
            'date' => $dto->date,
            'supplier_article' => $dto->supplierArticle,
            'tech_size' => $dto->techSize,
            'barcode' => $dto->barcode,
            'total_price' => $dto->totalPrice,
            'discount_percent' => $dto->discountPercent,
            'warehouse_name' => $dto->warehouseName,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
