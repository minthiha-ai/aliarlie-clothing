<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_order',
        'image',
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
