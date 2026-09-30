<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeMasterSeeder extends Seeder
{
    private const TOTAL = 990;

    private const DEPARTMENTS = [
        'Engineering' => ['Manager', 'Project Engineer', 'Design Engineer', 'Piping Engineer', 'Structural Engineer'],
        'Production' => ['Welder', 'Fitter', 'Production Supervisor', 'Rigger'],
        'HSE' => ['Manager', 'HSE Officer', 'Safety Supervisor', 'Environmental Officer'],
        'QA/QC' => ['QC Inspector', 'QA Engineer', 'NDT Technician'],
        'Procurement' => ['Buyer', 'Procurement Officer', 'Logistics Coordinator'],
        'Finance' => ['Manager', 'Accountant', 'Financial Analyst', 'Finance Officer'],
        'Human Resources' => ['HR Generalist', 'Recruitment Officer', 'Payroll Officer'],
        'IT' => ['Manager', 'IT Support', 'System Administrator', 'Software Engineer'],
        'Operations' => ['Operations Officer', 'Planner', 'Yard Coordinator'],
    ];

    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');
        $faker->seed(2026);

        $rows = [];

        for ($number = 1; $number <= self::TOTAL; $number++) {
            $badgeId = sprintf('EMP-%03d', $number);
            $name = $faker->firstName().' '.$faker->lastName();
            $department = $faker->randomElement(array_keys(self::DEPARTMENTS));

            $rows[] = [
                'badge_id' => $badgeId,
                'name' => $name,
                'department' => $department,
                'position' => $faker->randomElement(self::DEPARTMENTS[$department]),
                'project' => $faker->randomElement(['Gala Dinner', 'Shipyard Renewal', 'Offshore Platform']),
                'company' => 'Seatrium',
                'is_manager' => $faker->boolean(12),
            ];
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('master_attendance')->insertOrIgnore($chunk);
        }
    }
}
