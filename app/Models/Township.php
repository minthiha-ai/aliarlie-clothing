<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Township extends Model
{
    protected $fillable = [
        'state_region_id',
        'district_code',
        'code',
        'name',
        'name_mmr',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function stateRegion(): BelongsTo
    {
        return $this->belongsTo(StateRegion::class);
    }

    public function deliveryInfo(): HasOne
    {
        return $this->hasOne(DeliveryInfo::class);
    }
}
