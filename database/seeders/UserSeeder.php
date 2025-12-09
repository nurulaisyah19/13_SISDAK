<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'id_ormawa' => null,
        ]);

        // Buat user ormawa
        User::create([
            'username' => 'ormawa1',
            'password' => Hash::make('ormawa123'),
            'role' => 'ormawa',
            'id_ormawa' => null, // Sesuaikan dengan id_ormawa yang ada di tabel ormawas
        ]);
    }
}
