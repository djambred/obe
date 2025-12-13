<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample students (manual seeding)
        if (Student::count() === 0) {
            Student::create([
                'nim' => '20250001',
                'name' => 'Mahasiswa Satu',
                'email' => 'mhs1@example.com',
                'phone' => '081234567890',
                'enrollment_year' => 2025,
                'class_group' => 'A',
                'status' => 'Aktif',
                'gender' => 'L',
            ]);
            Student::create([
                'nim' => '20250002',
                'name' => 'Mahasiswa Dua',
                'email' => 'mhs2@example.com',
                'phone' => '081234567891',
                'enrollment_year' => 2025,
                'class_group' => 'B',
                'status' => 'Aktif',
                'gender' => 'P',
            ]);
        }
    }
}

