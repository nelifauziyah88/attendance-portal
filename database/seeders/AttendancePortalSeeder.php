<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterAttendance;
use App\Models\Invitation;
use App\Models\Prize;
use Illuminate\Support\Facades\Hash;

class AttendancePortalSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@seatrium'],
            [
                'name' => 'Seatrium Administrator',
                'password' => Hash::make('admin'),
            ]
        );

        MasterAttendance::query()
            ->orderBy('id')
            ->pluck('badge_id')
            ->each(fn (string $badgeId) => Invitation::updateOrCreate(['badge_id' => $badgeId]));

        $prizes = [
            ['name' => 'Monarch Polygon 5 Bicycle 27.5inch', 'stock' => 1],
            ['name' => 'Fridge 2 doors (kulkas 2 pintu)', 'stock' => 2],
            ['name' => "TV 40' inch", 'stock' => 1],
            ['name' => 'Washing machine 7kg', 'stock' => 1],
            ['name' => 'Air fryer samono 3.5L', 'stock' => 2],
            ['name' => 'Oven samono 12L', 'stock' => 2],
            ['name' => 'Baseus bass BS2 Lite', 'stock' => 3],
            ['name' => 'Tab Xiaomi Redmi P2 11inch', 'stock' => 2],
            ['name' => 'Blender', 'stock' => 3],
            ['name' => 'Iron', 'stock' => 3],
            ['name' => 'Gas Stove', 'stock' => 2],
            ['name' => 'Compact umbrella (payung lipat)', 'stock' => 5],
            ['name' => 'Tumbler 500ml', 'stock' => 6],
            ['name' => 'Rice cooker 1.8L', 'stock' => 2],
        ];

        foreach ($prizes as $prize) {
            Prize::updateOrCreate(
                ['name' => $prize['name']],
                [
                    'stock' => $prize['stock'],
                    'current_stock' => $prize['stock'],
                    'image' => null,
                ]
            );
        }
    }
}