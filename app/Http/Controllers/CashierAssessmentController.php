<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Course;

class CashierAssessmentController extends Controller
{
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

        if (!$assessment) {
            // Fallback to global if specific not found
            $globalKey = "payment_assessment_{$level}_all_all";
            $assessment = Cache::get($globalKey, [
                'tuitionFee' => 0,
                'miscellaneousFees' => 0,
            ]);
        }

        $tuitionFee = $assessment['tuitionFee'] ?? 0;
        $miscellaneousFees = $assessment['miscellaneousFees'] ?? 0;
        $discountPercentage = $assessment['discountPercentage'] ?? 0;
        $discountAmount = $assessment['discountAmount'] ?? 0;

        return view('cashier.assessment.index', compact(
            'level', 'program', 'yearLevel', 'programs', 'yearLevels', 'tuitionFee', 'miscellaneousFees', 'discountPercentage', 'discountAmount'
        ));
    }

    public function store(Request $request, $level)
    {
        $request->validate([
            'program' => 'required|string',
            'yearLevel' => 'required|string',
            'tuitionFee' => 'required|numeric|min:0',
            'miscellaneousFees' => 'required|numeric|min:0',
            'discountPercentage' => 'nullable|numeric|min:0|max:100',
            'discountAmount' => 'nullable|numeric|min:0',
        ]);

        $program = $request->program;
        $yearLevel = $request->yearLevel;

        $cacheKey = "payment_assessment_{$level}_{$program}_{$yearLevel}";

        Cache::put($cacheKey, [
            'tuitionFee' => $request->tuitionFee,
            'miscellaneousFees' => $request->miscellaneousFees,
            'discountPercentage' => $request->discountPercentage ?? 0,
            'discountAmount' => $request->discountAmount ?? 0,
        ], now()->addYears(1));

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
