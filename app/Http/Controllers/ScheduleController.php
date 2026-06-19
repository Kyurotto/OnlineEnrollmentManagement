<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Employee;

class ScheduleController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $level = $request->query('level');
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];

        $schedulesQuery = Schedule::with(['enrollment.user', 'employee']);
        $enrollmentsQuery = Enrollment::with('user')->where('status', '!=', 'Pending');

        if ($level) {
            $schedulesQuery->whereHas('enrollment', function($query) use ($level, $shsStrands) {
                if ($level === 'college') {
                    $query->whereNotIn('course_code', $shsStrands)->orWhereNull('course_code');
                } else {
                    $query->whereIn('course_code', $shsStrands);
                }
            });

            if ($level === 'college') {
                $enrollmentsQuery->where(function($q) use ($shsStrands) {
                    $q->whereNotIn('course_code', $shsStrands)->orWhereNull('course_code');
                });
            } else {
                $enrollmentsQuery->whereIn('course_code', $shsStrands);
            }
        }

        $schedules = $schedulesQuery->get();
        $enrollments = $enrollmentsQuery->get();
        $instructors = Employee::all();

        return view('schedules.index', compact('schedules', 'enrollments', 'level', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.room' => 'nullable|string',
            'schedules.*.employee_id' => 'nullable|exists:employees,id',
        ]);

        foreach ($validated['schedules'] as $scheduleData) {
            Schedule::create([
                'enrollment_id' => $validated['enrollment_id'],
                'employee_id' => $scheduleData['employee_id'] ?? null,
                'day_of_week' => $scheduleData['day_of_week'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'room' => $scheduleData['room'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Schedules added successfully.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'room' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $schedule->update([
            'employee_id' => $validated['employee_id'] ?? null,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room' => $validated['room'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Schedule deleted successfully.');
    }
}
