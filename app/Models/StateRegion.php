<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StateRegion extends Model
{
    protected $fillable = [
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

    public function townships(): HasMany
    {
        return $this->hasMany(Township::class);
    }

    public function deliveryInfos(): HasMany
    {
        return $this->hasMany(DeliveryInfo::class);
    }
}
