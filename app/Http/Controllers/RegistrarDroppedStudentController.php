<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Services\DroppedStudentReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RegistrarDroppedStudentController extends Controller
{
    public function __construct(private DroppedStudentReportService $service) {}

    public function index()
    {
        $officiallyDropped = $this->service->getOfficiallyDropped();
        $atRiskStudents    = $this->service->getAtRiskStudents(absenceThreshold: 5, paymentGapDays: 30);
        $reasonSummary     = $this->service->getDropReasonSummary();

        $droppedCount  = $officiallyDropped->count();
        $withdrawnCount = $officiallyDropped->where('drop_status', 'Withdrawn')->count();
        $atRiskCount   = $atRiskStudents->count();
        $totalPenalties = $officiallyDropped->sum('drop_penalty');

        return view('registrar.dropped.index', compact(
            'officiallyDropped',
            'atRiskStudents',
            'reasonSummary',
            'droppedCount',
            'withdrawnCount',
            'atRiskCount',
            'totalPenalties'
        ));
    }

    public function markDropped(Request $request, int $enrollmentId)
    {
        $request->validate([
            'drop_reason'  => 'required|in:Financial,Personal,Transfer,Academic,Health,Other',
            'drop_period'  => 'required|string',
            'drop_notes'   => 'nullable|string|max:500',
            'base_tuition' => 'nullable|numeric|min:0',
        ]);

        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->status       = 'Dropped';
        $enrollment->drop_date    = Carbon::today();
        $enrollment->drop_reason  = $request->drop_reason;
        $enrollment->drop_period  = $request->drop_period;
        $enrollment->drop_notes   = $request->drop_notes;
        if ($request->filled('base_tuition')) {
            $enrollment->base_tuition = $request->base_tuition;
        }
        $enrollment->save();

        // Calculate and show the charge
        $penalty = $this->service->calculateDropCharge($enrollment);

        return back()->with('success',
            "Student marked as Dropped ({$request->drop_period}). " .
            "Charge: ₱" . number_format($penalty['chargeAmount'], 2) . ". " .
            $penalty['chargeDescription']
        );
    }

    public function markWithdrawn(Request $request, int $enrollmentId)
    {
        $request->validate([
            'drop_reason'  => 'required|in:Financial,Personal,Transfer,Academic,Health,Other',
            'drop_period'  => 'required|string',
            'drop_notes'   => 'nullable|string|max:500',
            'base_tuition' => 'nullable|numeric|min:0',
        ]);

        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->status      = 'Withdrawn';
        $enrollment->drop_date   = Carbon::today();
        $enrollment->drop_reason = $request->drop_reason;
        $enrollment->drop_period = $request->drop_period;
        $enrollment->drop_notes  = $request->drop_notes;
        if ($request->filled('base_tuition')) {
            $enrollment->base_tuition = $request->base_tuition;
        }
        $enrollment->save();

        $penalty = $this->service->calculateDropCharge($enrollment);

        return back()->with('success',
            "Student marked as Withdrawn ({$request->drop_period}). " .
            "Charge: ₱" . number_format($penalty['chargeAmount'], 2) . "."
        );
    }

    public function restore(int $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->status      = 'Enrolled';
        $enrollment->drop_date   = null;
        $enrollment->drop_reason = null;
        $enrollment->drop_notes  = null;
        $enrollment->save();

        return back()->with('success', 'Student enrollment restored to Enrolled.');
    }

    public function getPenaltyPreview(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'base_tuition'  => 'nullable|numeric|min:0',
        ]);

        $enrollment  = Enrollment::findOrFail($request->enrollment_id);
        $baseTuition = (float) ($request->base_tuition ?? $enrollment->base_tuition ?? 0);
        $penalty     = $this->service->calculateDropPenalty(
            $enrollment->created_at,
            now(),
            $baseTuition
        );

        return response()->json($penalty);
    }
}
