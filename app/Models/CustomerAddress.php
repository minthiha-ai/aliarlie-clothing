<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'receiver_name',
        'phone',
        'address',
        'state_region_id',
        'township_id',
        'township',
        'city',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function stateRegion(): BelongsTo
    {
        return $this->belongsTo(StateRegion::class);
    }

    public function townshipRelation(): BelongsTo
    {
        return $this->belongsTo(Township::class, 'township_id');
    }
}
