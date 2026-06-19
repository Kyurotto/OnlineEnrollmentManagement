<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\AcademicYear;

class CourseDemandService
{
    /**
     * Get the total capacity for a course in the currently active academic year.
     */
    public function getTotalCapacity(Course $course): int
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return 0;
        }

        return Section::where('course_id', $course->id)
            ->where('academic_year', $activeYear->year_name)
            ->sum('capacity');
    }

    /**
     * Get the current number of applications/enrollments for a course in the currently active academic year.
     */
    public function getCurrentDemand(Course $course): int
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return 0;
        }

        // We count all students who have a record for this course in the current year,
        // regardless of whether they are 'Pending', 'Approved', or 'Enrolled'.
        return Enrollment::where('course_id', $course->id)
            ->where('year_level', 'like', '%' . $activeYear->year_name . '%')
            ->count();
    }

    /**
     * Calculate the demand percentage for a course.
     */
    public function getDemandPercentage(Course $course): float
    {
        $capacity = $this->getTotalCapacity($course);
        if ($capacity === 0) {
            return 0.0;
        }

        $demand = $this->getCurrentDemand($course);
        return ($demand / $capacity) * 100;
    }

    /**
     * Get the demand status label based on percentage.
     */
    public function getDemandStatus(float $percentage): string
    {
        if ($percentage >= 90) {
            return 'Critical';
        }
        if ($percentage >= 70) {
            return 'Warning';
        }
        return 'Normal';
    }

    /**
     * Get all courses with their demand metrics.
     */
    public function getDemandReport()
    {
        $courses = Course::all();
        $report = [];

        foreach ($courses as $course) {
            $capacity = $this->getTotalCapacity($course);
            $demand = $this->getCurrentDemand($course);
            $percentage = $this->getDemandPercentage($course);

            $report[] = [
                'course' => $course,
                'capacity' => $capacity,
                'demand' => $demand,
                'percentage' => round($percentage, 2),
                'status' => $this->getDemandStatus($percentage),
            ];
        }

        return $report;
    }
}
