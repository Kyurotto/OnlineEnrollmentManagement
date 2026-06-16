<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\CourseDemandService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CourseCapacityAlert; // Using similar logic or a dedicated Report notification

class SendCourseDemandReport extends Command
{
    protected $signature = 'app:send-course-demand-report';
    protected $description = 'Sends a daily summary of course demand to administrators.';

    public function handle(CourseDemandService $demandService)
    {
        $this->info('Generating daily course demand report...');

        $reportData = $demandService->getDemandReport();
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('No admins found to receive the report.');
            return;
        }

        // In a real app, we would use a dedicated Mail class.
        // For this implementation, we can send a consolidated notification or email.

        $reportSummary = "";
        foreach($reportData as $item) {
            $reportSummary .= "{$item['course']->course_name} ({$item['course']->course_code}): {$item['percentage']}% - {$item['status']}\n";
        }

        // We will use a simple Mail send for the summary
        Mail::raw("Daily Course Demand Report\n\n" . $reportSummary, function ($message) use ($admins) {
            $message->to($admins->pluck('email'))
                    ->subject('Daily Course Demand Report');
        });

        $this->info('Daily demand report sent to administrators.');
    }
}
