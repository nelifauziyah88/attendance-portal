<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Prize;
use Illuminate\Support\Facades\Hash;

class AttendancePortalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Account Admin
        User::updateOrCreate(
            ['badge_id' => 'ADMIN001'],
            [
                'name'       => 'Seatrium Administrator',
                'email'      => 'admin@seatrium.com',
                'password'   => Hash::make('password123'),
                'department' => 'IT Department',
                'position'   => 'System Administrator',
                'role'       => 'ADMIN',
            ]
        );

        // 2. Generate 990 Employee Dummy & Masukkan ke Invitations
        for ($i = 1; $i <= 990; $i++) {
            $badgeId = 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT);

            // Simpan ke DB Master Users
            User::updateOrCreate(
                ['badge_id' => $badgeId],
                [
                    'name'       => "Karyawan $i",
                    'email'      => "employee$i@seatrium.com",
                    'password'   => Hash::make('password123'),
                    'department' => 'Department ' . rand(1, 5),
                    'position'   => 'Staff',
                    'role'       => 'EMPLOYEE',
                ]
            );

            // Daftarkan ke DB Lokal Invitations
            Invitation::updateOrCreate(['badge_id' => $badgeId]);
        }

        // 3. Seed Master Hadiah
        Prize::insert([
            ['name' => 'Smart TV 55 Inch', 'stock' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sepeda Listrik', 'stock' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smartphone Galaxy A55', 'stock' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Voucher Belanja Rp 500rb', 'stock' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}