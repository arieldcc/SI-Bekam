<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin (tidak terkait ke data lain)
        User::create([
            'username'   => 'admin',
            'full_name'  => 'Administrator',
            'name'       => 'Administrator',
            'email'      => 'admin@bekam.com',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'related_id' => null,
        ]);

        // 2. Terapis (buat dulu profil di tabel therapists)
        $therapist = Therapist::create([
            'full_name'    => 'Ustadz Hadi',
            'specialty'    => 'Bekam Kepala',
            'phone_number' => '081234567890',
            'address'      => 'Jl. Klinik No. 1',
        ]);

        User::create([
            'username'   => 'terapis1a',
            'full_name'  => 'Ustadz Hadi',
            'name'       => 'Ustadz Hadi',
            'email'      => 'hadi@bekam.com',
            'password'   => Hash::make('terapis123'),
            'role'       => 'terapis',
            'related_id' => $therapist->id,
        ]);

        // 3. Pasien (buat dulu profil di tabel patients)
        $patient = Patient::create([
            'full_name'     => 'Ahmad Pasien',
            'gender'        => 'L',
            'date_of_birth' => '1995-05-10',
            'phone_number'  => '089876543210',
            'address'       => 'Jl. Sehat No. 5',
        ]);

        User::create([
            'username'   => 'pasien1a',
            'full_name'  => 'Ahmad Pasien',
            'name'       => 'Ahmad Pasien',
            'email'      => 'ahmad@bekam.com',
            'password'   => Hash::make('pasien123'),
            'role'       => 'pasien',
            'related_id' => $patient->id,
        ]);
    }
}
