<?php

namespace Database\Seeders;

use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class ContactInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactInfo::query()->firstOrCreate(
            ['id' => 1],
            [
                'address_1_title' => 'AliarLIE Store',
                'address_1_text' => 'PO Box 16122 Collins Street West Victoria 8007 Australia',
                'address_2_title' => 'Store 2',
                'address_2_text' => '8134 Budd Rd Terre Haute, In 3548',
                'email' => 'info@aliarlie.com',
                'phone' => '+354-354-4861',
                'social_facebook' => '#',
                'social_pinterest' => '#',
                'social_twitter' => '#',
                'social_instagram' => '#',
            ]
        );
    }
}
