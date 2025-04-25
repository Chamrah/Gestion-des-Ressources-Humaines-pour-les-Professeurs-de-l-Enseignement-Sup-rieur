<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Générer 500 employés aléatoires
        Employee::factory()->count(500)->create();

        // Ajouter des employés spécifiques
        $employees = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'email' => 'alice.johnson@example.com',
                'phone_number' => '0612345678',
                'position' => 'Manager',
                'salary' => 75000,
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'email' => 'bob.smith@example.com',
                'phone_number' => '0698765432',
                'position' => 'Developer',
                'salary' => 60000,
            ],
            [
                'first_name' => 'Charlie',
                'last_name' => 'Brown',
                'email' => 'charlie.brown@example.com',
                'phone_number' => '0687654321',
                'position' => 'Designer',
                'salary' => 55000,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
