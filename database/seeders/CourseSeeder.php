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
            // College Programs
            ['course_code' => 'ACT', 'course_name' => 'ASSOCIATE IN COMPUTER TECH', 'credits' => 3, 'type' => 'program'],
            ['course_code' => 'BSIS', 'course_name' => 'BS INFORMATION SYSTEMS', 'credits' => 3, 'type' => 'program'],
            ['course_code' => 'BTVTED', 'course_name' => 'BTV Teacher Education', 'credits' => 3, 'type' => 'program'],
            ['course_code' => 'DHRT', 'course_name' => 'HOTEL & RESTAURANT TECH', 'credits' => 3, 'type' => 'program'],
            ['course_code' => 'DIT', 'course_name' => 'DIPLOMA INFO TECH', 'credits' => 3, 'type' => 'program'],

            // Senior High Strands
            ['course_code' => 'ABM', 'course_name' => 'Accountancy, Business and Management', 'credits' => 3, 'type' => 'strand'],
            ['course_code' => 'STEM', 'course_name' => 'Science, Technology, Engineering, and Mathematics', 'credits' => 3, 'type' => 'strand'],
            ['course_code' => 'HUMSS', 'course_name' => 'Humanities and Social Sciences', 'credits' => 3, 'type' => 'strand'],
            ['course_code' => 'GAS', 'course_name' => 'General Academic Strand', 'credits' => 3, 'type' => 'strand'],
            ['course_code' => 'HE', 'course_name' => 'Home Economics', 'credits' => 3, 'type' => 'strand'],
            ['course_code' => 'ICT', 'course_name' => 'Information and Communications Technology', 'credits' => 3, 'type' => 'strand'],

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
