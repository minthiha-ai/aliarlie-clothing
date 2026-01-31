<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = [
        'address_1_title',
        'address_1_text',
        'address_2_title',
        'address_2_text',
        'email',
        'phone',
        'social_facebook',
        'social_pinterest',
        'social_twitter',
        'social_instagram',
    ];
}
