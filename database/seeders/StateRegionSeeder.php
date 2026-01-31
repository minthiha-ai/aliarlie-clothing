<?php

namespace Database\Seeders;

use App\Models\StateRegion;
use Illuminate\Database\Seeder;

class StateRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('state_regions.csv');

        if (! file_exists($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if (empty($data['SR_Code'] ?? null)) {
                continue;
            }

            StateRegion::query()->updateOrCreate(
                ['code' => $data['SR_Code']],
                [
                    'name' => $data['SR_Name'] ?? '',
                    'name_mmr' => $data['SR_Name_MMR'] ?? null,
                    'is_active' => (string) ($data['active'] ?? '1') === '1',
                ]
            );
        }

        fclose($handle);
    }
}
