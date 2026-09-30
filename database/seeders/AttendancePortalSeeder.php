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
        // 1. Buat Account Admin
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

        // 3. Seed Master Hadiah
        Prize::insert([
            ['name' => 'Smart TV 55 Inch', 'stock' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sepeda Listrik', 'stock' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smartphone Galaxy A55', 'stock' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Voucher Belanja Rp 500rb', 'stock' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}