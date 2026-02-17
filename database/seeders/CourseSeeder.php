<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Semester;
use App\Models\AcademicYear;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Create courses
        $courses = [
            ['course_code' => 'ACT', 'course_name' => 'ASSOCIATE IN COMPUTER TECH', 'credits' => 3],
            ['course_code' => 'BSIS', 'course_name' => 'BS INFORMATION SYSTEMS', 'credits' => 3],
            ['course_code' => 'BTVTED', 'course_name' => 'BTV Teacher Education', 'credits' => 3],
            ['course_code' => 'DHRT', 'course_name' => 'HOTEL & RESTAURANT TECH', 'credits' => 3],
            ['course_code' => 'DIT', 'course_name' => 'DIPLOMA INFO TECH', 'credits' => 3],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(
                ['course_code' => $course['course_code']],
                $course
            );
        }

        // Create semesters
        $semesters = [
            ['name' => '1st Semester', 'academic_year' => '2025-2026', 'start_date' => '2025-06-01', 'end_date' => '2025-10-31', 'is_active' => true],
            ['name' => '2nd Semester', 'academic_year' => '2025-2026', 'start_date' => '2025-11-01', 'end_date' => '2026-03-31', 'is_active' => false],
        ];

        foreach ($semesters as $semester) {
            Semester::firstOrCreate(
                ['name' => $semester['name'], 'academic_year' => $semester['academic_year']],
                $semester
            );
        }

        // Create academic years
        $years = [
            ['year_name' => '2025-2026', 'is_active' => true],
            ['year_name' => '2024-2025', 'is_active' => false],
        ];

        foreach ($years as $year) {
            AcademicYear::firstOrCreate(
                ['year_name' => $year['year_name']],
                $year
            );
        }
    }
}
