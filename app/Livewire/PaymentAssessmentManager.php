<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.cashier')]
class PaymentAssessmentManager extends Component
{
    public $level; // 'shs' or 'college'
    public $tuitionFee = 0;
    public $miscellaneousFees = 0;
    public $successMessage = '';

    public function mount()
    {
        // Determine level from route
        if (request()->routeIs('cashier.assessment.shs')) {
            $this->level = 'shs';
        } elseif (request()->routeIs('cashier.assessment.college')) {
            $this->level = 'college';
        }

        // Load existing assessment from cache
        $cacheKey = 'payment_assessment_' . $this->level;
        $assessment = Cache::get($cacheKey, [
            'tuitionFee' => 0,
            'miscellaneousFees' => 0,
        ]);

        $this->tuitionFee = $assessment['tuitionFee'] ?? 0;
        $this->miscellaneousFees = $assessment['miscellaneousFees'] ?? 0;
    }

    public function saveAssessment()
    {
        $this->validate([
            'tuitionFee' => 'required|numeric|min:0',
            'miscellaneousFees' => 'required|numeric|min:0',
        ]);

        $cacheKey = 'payment_assessment_' . $this->level;

        Cache::put($cacheKey, [
            'tuitionFee' => $this->tuitionFee,
            'miscellaneousFees' => $this->miscellaneousFees,
        ], now()->addYears(1)); // Cache for 1 year

        $this->successMessage = 'Payment assessment for ' . strtoupper($this->level) . ' saved successfully!';
        $this->dispatch('assessmentUpdated');

        // Clear message after 3 seconds
        $this->js('setTimeout(() => { $wire.successMessage = ""; }, 3000)');
    }

    public function render()
    {
        return view('livewire.cashier-payment-assessment-manager');
    }
}
