<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseDemandService;
use App\Notifications\CourseCapacityAlert;
use Illuminate\Support\Facades\Notification;

class MonitorCourseDemand extends Command
{
    protected $signature = 'app:monitor-course-demand';
    protected $description = 'Monitors course demand and alerts admins if capacity thresholds are reached.';

    public function handle(CourseDemandService $demandService)
    {
        $this->info('Monitoring course demand...');

        $courses = Course::all();
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('No admins found to notify.');
            return;
        }

        foreach ($courses as $course) {
            $percentage = $demandService->getDemandPercentage($course);

            if ($percentage >= 80) {
                $this->info("Alerting for {$course->course_name}: " . round($percentage, 2) . "%");
                Notification::send($admins, new CourseCapacityAlert($course->course_name, $percentage));
            }
        }

        $this->info('Demand monitoring completed.');
    }
}
