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
        // Contoh 1: Membuat user admin utama
        User::create([
            'name' => 'Sultan A Fz',
            'email' => 'sultan@mart.com',
            'password' => Hash::make('200705'),
            'role' => 'admin'
        ]);

        // Contoh 2: Membuat user kasir (jika ada)
        User::create([
            'name' => 'Kasir JKW',
            'email' => 'kasir@toko.com',
            'password' => Hash::make('kasir123'),
            'role' => 'cashier'
        ]);
    }
}
