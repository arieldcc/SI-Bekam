<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $bloodTypes = ['A', 'B', 'AB', 'O'];

        for ($i = 1; $i <= 500; $i++) {
            $patient = Patient::create([
                'full_name' => $faker->name,
                'gender' => $faker->randomElement(['L', 'P']),
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-15 years')->format('Y-m-d'),
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'height' => $faker->numberBetween(140, 190),
                'blood_type' => $faker->randomElement($bloodTypes),
                'disease_history' => $faker->sentence(4),
            ]);

            User::create([
                'username' => 'pasien' . $i,
                'full_name' => $patient->full_name,
                'name' => $patient->full_name,
                'email' => "pasien{$i}@bekam.com",
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'related_id' => $patient->id,
                'is_active' => 1
            ]);
        }
    }
}
