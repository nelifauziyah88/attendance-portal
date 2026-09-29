<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeMasterSeeder extends Seeder
{
    private const TOTAL = 990;

    private const DEPARTMENTS = [
        'Engineering' => ['Project Engineer', 'Design Engineer', 'Piping Engineer', 'Structural Engineer'],
        'Production' => ['Welder', 'Fitter', 'Production Supervisor', 'Rigger'],
        'HSE' => ['HSE Officer', 'Safety Supervisor', 'Environmental Officer'],
        'QA/QC' => ['QC Inspector', 'QA Engineer', 'NDT Technician'],
        'Procurement' => ['Buyer', 'Procurement Officer', 'Logistics Coordinator'],
        'Finance' => ['Accountant', 'Financial Analyst', 'Finance Officer'],
        'Human Resources' => ['HR Generalist', 'Recruitment Officer', 'Payroll Officer'],
        'IT' => ['IT Support', 'System Administrator', 'Software Engineer'],
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
                'email' => $this->emailFor($name, $badgeId),
            ];
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('users')->insertOrIgnore($chunk);
        }

        $this->fillMissingEmails();
    }

    private function fillMissingEmails(): void
    {
        DB::table('users')
            ->whereNull('email')
            ->orderBy('id')
            ->get(['id', 'badge_id', 'name'])
            ->each(fn (object $user) => DB::table('users')
                ->where('id', $user->id)
                ->update(['email' => $this->emailFor($user->name, $user->badge_id)]));
    }

    private function emailFor(string $name, string $badgeId): string
    {
        return Str::slug($name, '.').'.'.strtolower(str_replace('-', '', $badgeId)).'@example.com';
    }
}
