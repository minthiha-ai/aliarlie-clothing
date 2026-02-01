<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'quantity',
        'reserved',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reserved' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Stock $stock): void {
            $variant = $stock->productVariant;
            if ($variant) {
                $variant->update(['is_active' => $stock->quantity > 0]);
                $variant->product?->recalculateInstock();
            }
        });

        static::deleted(function (Stock $stock): void {
            $stock->productVariant?->product?->recalculateInstock();
        });
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
