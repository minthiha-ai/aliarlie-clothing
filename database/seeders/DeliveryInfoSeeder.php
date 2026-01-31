<?php

namespace Database\Seeders;

use App\Models\DeliveryInfo;
use App\Models\Township;
use Illuminate\Database\Seeder;

class DeliveryInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Township::query()->each(function (Township $township): void {
            DeliveryInfo::query()->firstOrCreate(
                ['township_id' => $township->id],
                [
                    'state_region_id' => $township->state_region_id,
                    'delivery_fees' => 0,
                ]
            );
        });
    }
}
