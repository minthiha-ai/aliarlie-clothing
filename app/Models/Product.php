<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'is_active',
        'instock',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'is_active' => 'boolean',
            'instock' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * Recalculate instock from variant stock quantities.
     * Product is in stock if at least one variant has stock quantity > 0.
     */
    public function recalculateInstock(): void
    {
        $hasStock = $this->variants()
            ->whereHas('stock', fn ($q) => $q->where('quantity', '>', 0))
            ->exists();

        $this->forceFill(['instock' => $hasStock])->saveQuietly();
    }
}
