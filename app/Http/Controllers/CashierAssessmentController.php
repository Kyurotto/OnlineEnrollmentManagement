<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Course;

class CashierAssessmentController extends Controller
{
    /**
     * All itemized fee keys used in the assessment form.
     * The order matches the school's official fee schedule.
     */
    private const FEE_ITEMS = [
        'registrationFee',
        'guidanceFee',
        'trainingMaterials',
        'handbook',
        'mailingFee',
        'medicalDental',
        'studentId',
        'socioCultural',
        'insurance',
        'schoolPublication',
        'studentDevelopment',
        'libraryFee',
        'energyFee',
        'physicalFacilities',
        'researchInnovation',
        'internetFee',
        'audioVisual',
        'itDevelopment',
        'laboratoryFee',
        'tuitionFee',
    ];

    /**
     * Miscellaneous-category keys (summed into miscellaneousFees for backwards compat).
     */
    private const MISC_KEYS = [
        'guidanceFee',
        'trainingMaterials',
        'handbook',
        'mailingFee',
        'medicalDental',
        'studentId',
        'socioCultural',
        'insurance',
        'schoolPublication',
        'studentDevelopment',
        'libraryFee',
        'energyFee',
        'physicalFacilities',
        'researchInnovation',
        'internetFee',
        'audioVisual',
        'itDevelopment',
    ];

    public function showSHS(Request $request)
    {
        return $this->showAssessment('shs', $request);
    }

    public function showCollege(Request $request)
    {
        return $this->showAssessment('college', $request);
    }

    private function showAssessment($level, Request $request)
    {
        $program = $request->get('program', 'all');
        $yearLevel = $request->get('yearLevel', 'all');

        $programs = Course::where('type', $level === 'shs' ? 'shs' : 'program')->get();

        $yearLevels = $this->getYearLevels($level, $program);

        // Reset year level if current selection is no longer valid
        if ($yearLevel !== 'all' && !in_array($yearLevel, $yearLevels)) {
            $yearLevel = 'all';
        }

        $cacheKey = "payment_assessment_{$level}_{$program}_{$yearLevel}";
        $assessment = Cache::get($cacheKey);

        if (!$assessment && $yearLevel !== 'all') {
            // Try program-wide default (e.g., ICT All Levels)
            $assessment = Cache::get("payment_assessment_{$level}_{$program}_all");
        }

        if (!$assessment && $program !== 'all') {
            // Try level-wide default (e.g., All Strands Grade 11)
            $assessment = Cache::get("payment_assessment_{$level}_all_{$yearLevel}");
        }

        if (!$assessment) {
            // Fallback to global if specific not found
            $globalKey = "payment_assessment_{$level}_all_all";
            $assessment = Cache::get($globalKey, [
                'tuitionFee' => 0,
                'miscellaneousFees' => 0,
            ]);
        }

        // Extract all fee items with defaults
        $feeData = [];
        foreach (self::FEE_ITEMS as $key) {
            $feeData[$key] = $assessment[$key] ?? 0;
        }

        // Legacy compatibility: if only old-style data exists, put the lump sum in the first misc field
        $tuitionFee = $feeData['tuitionFee'] ?: ($assessment['tuitionFee'] ?? 0);
        $miscellaneousFees = $assessment['miscellaneousFees'] ?? 0;
        $registrationFee = $feeData['registrationFee'] ?? 0;
        $laboratoryFee = $feeData['laboratoryFee'] ?? 0;

        return view('cashier.assessment.index', compact(
            'level',
            'program',
            'yearLevel',
            'programs',
            'yearLevels',
            'tuitionFee',
            'miscellaneousFees',
            'feeData',
            'assessment'
        ));
    }

    public function store(Request $request, $level)
    {
        $rules = [
            'program' => 'required|string',
            'yearLevel' => 'required|string',
        ];

        // Validate each fee item
        foreach (self::FEE_ITEMS as $key) {
            $rules[$key] = 'nullable|numeric|min:0';
        }

        $request->validate($rules);

        $program = $request->program;
        $yearLevel = $request->yearLevel;

        $cacheKey = "payment_assessment_{$level}_{$program}_{$yearLevel}";

        // Build the data array with all itemized fees
        $data = [];
        foreach (self::FEE_ITEMS as $key) {
            $data[$key] = (float) ($request->$key ?? 0);
        }

        // Auto-sum miscellaneous fees for backwards compatibility with PaymentManager
        $miscSum = 0;
        foreach (self::MISC_KEYS as $key) {
            $miscSum += $data[$key];
        }

        // Store both itemized AND the legacy summed fields
        $data['tuitionFee'] = $data['tuitionFee'] ?? 0;
        $data['miscellaneousFees'] = $miscSum + ($data['registrationFee'] ?? 0) + ($data['laboratoryFee'] ?? 0);

        Cache::put($cacheKey, $data, now()->addYears(1));

        return back()->with('success', 'Assessment for ' . strtoupper($program) . ' ' . $yearLevel . ' saved successfully!');
    }

    private function getYearLevels($level, $program)
    {
        if ($level === 'shs') {
            return ['11', '12'];
        }

        $levels = ['1', '2', '3', '4'];

        if ($program === 'ACT') {
            $levels = ['1', '2'];
        } elseif (in_array($program, ['DIT', 'DHRT'])) {
            $levels = ['1', '2', '3'];
        }

        return $levels;
    }
}
