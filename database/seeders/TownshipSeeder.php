<?php

namespace Database\Seeders;

use App\Models\StateRegion;
use App\Models\Township;
use Illuminate\Database\Seeder;

class TownshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('townships.csv');

        if (! file_exists($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $srCode = $data['SR_Code'] ?? null;
            if (empty($srCode) || empty($data['TS_Code'] ?? null)) {
                continue;
            }

            $stateRegion = StateRegion::query()->where('code', $srCode)->first();
            if (! $stateRegion) {
                continue;
            }

            Township::query()->updateOrCreate(
                [
                    'state_region_id' => $stateRegion->id,
                    'code' => $data['TS_Code'],
                ],
                [
                    'district_code' => $data['D_Code'] ?? null,
                    'name' => $data['TS_Name'] ?? '',
                    'name_mmr' => $data['TS_Name_MMR'] ?? null,
                    'is_active' => (string) ($data['active'] ?? '1') === '1',
                ]
            );
        }

        fclose($handle);
    }
}
