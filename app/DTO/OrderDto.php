<?php

namespace App\DTO;

use Carbon\Carbon;

class OrderDto
{
    public function __construct(
        public ?string $gNumber,
        public ?Carbon $date,
        public ?string $supplierArticle,
        public ?string $techSize,
        public ?string $barcode,
        public ?float $totalPrice,
        public ?int $discountPercent,
        public ?string $warehouseName
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            gNumber: $data['g_number'] ?? null,
            date: isset($data['date']) ? Carbon::parse($data['date']) : null,
            supplierArticle: $data['supplier_article'] ?? null,
            techSize: $data['tech_size'] ?? null,
            barcode: $data['barcode'] ?? null,
            totalPrice: $data['total_price'] ?? null,
            discountPercent: $data['discount_percent'] ?? null,
            warehouseName: $data['warehouse_name'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'g_number' => $this->gNumber,
            'date' => $this->date,
            'supplier_article' => $this->supplierArticle,
            'tech_size' => $this->techSize,
            'barcode' => $this->barcode,
            'total_price' => $this->totalPrice,
            'discount_percent' => $this->discountPercent,
            'warehouse_name' => $this->warehouseName,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
