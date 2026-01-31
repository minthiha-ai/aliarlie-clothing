<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryInfo extends Model
{
    protected $fillable = [
        'state_region_id',
        'township_id',
        'delivery_fees',
    ];

    protected function casts(): array
    {
        return [
            'delivery_fees' => 'decimal:2',
        ];
    }

    public function stateRegion(): BelongsTo
    {
        return $this->belongsTo(StateRegion::class);
    }

    public function township(): BelongsTo
    {
        return $this->belongsTo(Township::class);
    }
}
