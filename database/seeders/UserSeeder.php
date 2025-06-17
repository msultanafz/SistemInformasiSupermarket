<?php

namespace Database\Seeders;

use App\Models\User; // Pastikan ini di-import, agar bisa menggunakan model User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Penting: Import Facade Hash untuk mengenkripsi password

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sultan@mart.com'],
            [
                'name' => 'Sultan A Fz',
                'password' => Hash::make('200705'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@toko.com'],
            [
                'name' => 'Kasir JKW',
                'password' => Hash::make('kasir123'),
                'role' => 'cashier'
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir2@toko.com'],
            [
                'name' => 'Kasir WOWO',
                'password' => Hash::make('kasir456'),
                'role' => 'cashier'
            ]
        );
    }
}
