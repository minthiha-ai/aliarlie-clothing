<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (OrderItem $orderItem): void {
            $orderItem->adjustStockOnCreate();
        });

        static::updated(function (OrderItem $orderItem): void {
            $orderItem->adjustStockOnUpdate();
        });

        static::saved(function (OrderItem $orderItem): void {
            $order = $orderItem->order;

            if ($order) {
                $order->recalculateTotalAmount();
            }
        });

        static::deleted(function (OrderItem $orderItem): void {
            $orderItem->adjustStockOnDelete();

            $order = $orderItem->order;

            if ($order) {
                $order->recalculateTotalAmount();
            }
        });
    }

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    protected function adjustStockOnCreate(): void
    {
        $stock = $this->productVariant?->stock;

        if ($stock) {
            $stock->decrement('quantity', $this->quantity);
        }
    }

    protected function adjustStockOnUpdate(): void
    {
        $originalVariantId = $this->getOriginal('product_variant_id');
        $originalQuantity = (int) $this->getOriginal('quantity');

        if ($originalVariantId !== $this->product_variant_id) {
            $originalVariant = ProductVariant::with('stock')->find($originalVariantId);
            $originalVariant?->stock?->increment('quantity', $originalQuantity);

            $this->productVariant?->stock?->decrement('quantity', $this->quantity);

            return;
        }

        $delta = $this->quantity - $originalQuantity;

        if ($delta !== 0) {
            $this->productVariant?->stock?->decrement('quantity', $delta);
        }
    }

    protected function adjustStockOnDelete(): void
    {
        $stock = $this->productVariant?->stock;

        if ($stock) {
            $stock->increment('quantity', $this->quantity);
        }
    }
}
