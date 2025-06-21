<?php

namespace Database\Seeders;

use App\Models\Therapist;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $therapist = Therapist::create([
                'full_name' => $faker->name,
                'specialty' => $faker->randomElement(['Bekam Basah', 'Bekam Kering', 'Bekam Lintah']),
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);

            User::create([
                'username' => 'terapis' . $i,
                'full_name' => $therapist->full_name,
                'name' => $therapist->full_name,
                'email' => "terapis{$i}@bekam.com",
                'password' => Hash::make('123456'), // default
                'role' => 'terapis',
                'related_id' => $therapist->id,
                'is_active' => 1
            ]);
        }
    }
}
