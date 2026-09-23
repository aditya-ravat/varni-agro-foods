<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Chamber;
use App\Models\Facility;
use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class FacilitiesSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'code' => 'VAR-MOR', 'name' => 'Varni Cold Storage – Morbi', 'slug' => 'morbi',
                'tagline' => 'Gujarat\'s largest potato cold storage cluster',
                'description' => 'Our flagship facility with 25,000 MT capacity, dedicated to potato, onion and seasonal vegetables.',
                'address_line1' => 'Plot No. 14, GIDC Phase-II', 'city' => 'Morbi', 'state' => 'Gujarat',
                'pincode' => '363641', 'latitude' => 22.8252, 'longitude' => 70.8378,
                'phone' => '+91 99999 11111', 'email' => 'morbi@varniagrofoods.com',
                'whatsapp' => '+919999911111',
                'opening_hours' => ['mon-sat' => '08:00–20:00', 'sun' => '08:00–14:00'],
                'total_capacity_mt' => 25000,
                'gstin' => '24AAAAA0000A1Z5',
                'seo_title' => 'Cold Storage in Morbi, Gujarat | Varni Agro Foods',
                'seo_description' => 'FSSAI-certified cold storage in Morbi with 25,000 MT capacity for potato, onion and vegetables. 24×7 temperature monitoring.',
                'is_published' => true, 'sort_order' => 1,
            ],
            [
                'code' => 'VAR-AHM', 'name' => 'Varni Cold Storage – Ahmedabad', 'slug' => 'ahmedabad',
                'tagline' => 'Multi-temperature cold chain for west India',
                'description' => 'A multi-zone facility with 18,000 MT capacity covering fruits, dairy, frozen and pharma chambers.',
                'address_line1' => 'Sarkhej-Bavla Highway, Sanand', 'city' => 'Ahmedabad', 'state' => 'Gujarat',
                'pincode' => '382170', 'latitude' => 22.9925, 'longitude' => 72.4148,
                'phone' => '+91 99999 22222', 'email' => 'ahmedabad@varniagrofoods.com',
                'whatsapp' => '+919999922222',
                'opening_hours' => ['mon-sat' => '07:00–22:00', 'sun' => '08:00–18:00'],
                'total_capacity_mt' => 18000,
                'seo_title' => 'Cold Storage in Ahmedabad | Varni Agro Foods',
                'seo_description' => 'Multi-temperature cold storage in Ahmedabad — fruits, dairy, frozen and pharma chambers with GDP-grade monitoring.',
                'is_published' => true, 'sort_order' => 2,
            ],
            [
                'code' => 'VAR-NSK', 'name' => 'Varni Cold Storage – Nashik', 'slug' => 'nashik',
                'tagline' => 'Onion & grape specialist',
                'description' => 'Dedicated low-humidity chambers for onions and grapes serving Maharashtra exporters.',
                'address_line1' => 'MIDC Sinnar', 'city' => 'Nashik', 'state' => 'Maharashtra',
                'pincode' => '422103', 'latitude' => 19.8482, 'longitude' => 73.9950,
                'phone' => '+91 99999 33333', 'email' => 'nashik@varniagrofoods.com',
                'whatsapp' => '+919999933333',
                'opening_hours' => ['mon-sat' => '08:00–20:00'],
                'total_capacity_mt' => 12000,
                'seo_title' => 'Cold Storage in Nashik | Varni Agro Foods',
                'seo_description' => 'Specialised onion and grape cold storage in Nashik with controlled humidity and 24×7 temperature monitoring.',
                'is_published' => true, 'sort_order' => 3,
            ],
        ];

        foreach ($facilities as $data) {
            $facility = Facility::updateOrCreate(['code' => $data['code']], $data);

            // Blocks
            $blockA = Block::firstOrCreate(
                ['facility_id' => $facility->id, 'code' => 'A'],
                ['name' => 'Block A', 'sort_order' => 1]
            );
            $blockB = Block::firstOrCreate(
                ['facility_id' => $facility->id, 'code' => 'B'],
                ['name' => 'Block B', 'sort_order' => 2]
            );

            // Chambers
            $chambers = [
                ['code' => 'C-01', 'name' => 'Chamber 01 (Cold Room)', 'type' => 'cold_room', 'tmin' => -1, 'tmax' => 4, 'cap' => 5000, 'block' => $blockA],
                ['code' => 'C-02', 'name' => 'Chamber 02 (Cold Room)', 'type' => 'cold_room', 'tmin' => -1, 'tmax' => 4, 'cap' => 5000, 'block' => $blockA],
                ['code' => 'F-01', 'name' => 'Freezer 01', 'type' => 'freezer', 'tmin' => -25, 'tmax' => -18, 'cap' => 2000, 'block' => $blockB],
                ['code' => 'CA-01', 'name' => 'CA Chamber 01', 'type' => 'ca', 'tmin' => 0, 'tmax' => 3, 'cap' => 3000, 'block' => $blockB],
            ];

            foreach ($chambers as $c) {
                $chamber = Chamber::firstOrCreate(
                    ['facility_id' => $facility->id, 'code' => $c['code']],
                    [
                        'block_id' => $c['block']->id,
                        'name' => $c['name'],
                        'type' => $c['type'],
                        'temp_min_c' => $c['tmin'],
                        'temp_max_c' => $c['tmax'],
                        'humidity_min_pct' => 85,
                        'humidity_max_pct' => 95,
                        'gross_capacity_mt' => $c['cap'],
                        'net_capacity_mt' => $c['cap'] * 0.85,
                        'status' => 'active',
                    ]
                );

                // Sample storage locations: 4 rows × 3 levels × 5 bays = 60 locations
                if ($chamber->locations()->count() === 0) {
                    foreach (range(1, 4) as $row) {
                        foreach (range(1, 3) as $level) {
                            foreach (range(1, 5) as $bay) {
                                $code = sprintf('%s-%s-R%d-L%d-B%02d', $facility->code, $chamber->code, $row, $level, $bay);
                                StorageLocation::create([
                                    'chamber_id' => $chamber->id,
                                    'code' => $code,
                                    'row' => $row,
                                    'level' => $level,
                                    'position' => $bay,
                                    'capacity_units' => 100,
                                    'used_units' => 0,
                                    'status' => 'empty',
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
