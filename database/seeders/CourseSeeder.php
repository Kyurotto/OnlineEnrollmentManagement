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
            [
                'course_code' => 'ABM', 
                'course_name' => 'Accountancy, Business and Management', 
                'description' => 'Focuses on the basic concepts of financial management, business management, and corporate operations.',
                'credits' => 3, 
                'type' => 'shs',
                'track' => 'ACAD'
            ],
            [
                'course_code' => 'STEM', 
                'course_name' => 'Science, Technology, Engineering, and Mathematics', 
                'description' => 'Designed to prepare students who express keen interest and inclination to pursue college degrees in science and math.',
                'credits' => 3, 
                'type' => 'shs',
                'track' => 'ACAD'
            ],
            [
                'course_code' => 'HUMSS', 
                'course_name' => 'Humanities and Social Sciences', 
                'description' => 'For those who are considering taking up journalism, communication arts, liberal arts, education, and other social science-related courses.',
                'credits' => 3, 
                'type' => 'shs',
                'track' => 'ACAD'
            ],
            [
                'course_code' => 'GAS', 
                'course_name' => 'General Academic Strand', 
                'description' => 'Ideal for students who are undecided on which strand to take.',
                'credits' => 3, 
                'type' => 'shs',
                'track' => 'ACAD'
            ],
            [
                'course_code' => 'HE', 
                'course_name' => 'Home Economics', 
                'description' => 'Developed for students who aim to find immediate employment after graduation.',
                'credits' => 3, 
                'type' => 'shs',
                'track' => 'TVL'
            ],
            [
                'course_code' => 'ICT', 
                'course_name' => 'Information and Communications Technology', 
                'description' => 'Provides students with the skills and knowledge needed to pursue a career in the technology industry.',
                'credits' => 3, 
                'type' => 'shs',
                'track' => 'TVL'
            ],

        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['course_code' => $course['course_code']],
                $course
            );
        }

        // Create semesters
        $semesters = [
            ['name' => '1ST SEMESTER', 'academic_year' => '2025-2026', 'start_date' => '2025-06-01', 'end_date' => '2025-10-31', 'is_active' => true],
            ['name' => '2ND SEMESTER', 'academic_year' => '2025-2026', 'start_date' => '2025-11-01', 'end_date' => '2026-03-31', 'is_active' => false],
        ];

        foreach ($semesters as $semester) {
            Semester::updateOrCreate(
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
