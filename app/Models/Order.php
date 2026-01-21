<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_code',
        'payment_id',
        'payment_proof_photo',
        'payment_method',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function recalculateTotalAmount(): void
    {
        $total = $this->items()
            ->selectRaw('COALESCE(SUM(price * quantity), 0) as total')
            ->value('total');

        $this->update([
            'total_amount' => $total,
        ]);
    }

    /**
     * Recalculate totals after bulk updates where model events are bypassed.
     *
     * @param  array<int, int>  $orderIds
     */
    public static function recalculateTotalsForOrders(array $orderIds): void
    {
        static::query()
            ->whereIn('id', $orderIds)
            ->each(function (Order $order): void {
                $order->recalculateTotalAmount();
            });
    }
}
