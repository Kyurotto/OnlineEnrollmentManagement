<?php

namespace App\Services;

/**
 * Service class to handle installment payment calculations
 * Splits downpayment into three phases: Prelim, Midterm, and Final
 */
class InstallmentCalculator
{
    /**
     * Calculate installment breakdown for a given total amount
     * 
     * @param float $totalAmount Total downpayment amount
     * @param string $type Distribution type - 'equal' or 'weighted'
     * @return array Array with Prelim, Midterm, and Final installments
     */
    public static function calculateInstallments($totalAmount, $type = 'equal')
    {
        $totalAmount = (float)$totalAmount;

        if ($type === 'weighted') {
            // Weighted distribution (Prelim 30%, Midterm 30%, Final 40%)
            return [
                'Prelim' => round($totalAmount * 0.30, 2),
                'Midterm' => round($totalAmount * 0.30, 2),
                'Final' => round($totalAmount * 0.40, 2),
            ];
        } else {
            // Equal distribution (roughly 33.33% each)
            $perInstallment = round($totalAmount / 3, 2);
            $remaining = $totalAmount - ($perInstallment * 2);

            return [
                'Prelim' => $perInstallment,
                'Midterm' => $perInstallment,
                'Final' => $remaining, // Adjust final to account for rounding
            ];
        }
    }

    /**
     * Get the due date for each installment phase
     * 
     * @param string $enrollmentStartDate Semester start date
     * @return array Array with due dates for each installment
     */
    public static function getInstallmentDueDates($enrollmentStartDate = null)
    {
        if (!$enrollmentStartDate) {
            $enrollmentStartDate = now();
        } else {
            $enrollmentStartDate = \Carbon\Carbon::parse($enrollmentStartDate);
        }

        return [
            'Prelim' => $enrollmentStartDate->copy()->addWeeks(4)->format('Y-m-d'), // Due at 4th week
            'Midterm' => $enrollmentStartDate->copy()->addWeeks(8)->format('Y-m-d'), // Due at 8th week
            'Final' => $enrollmentStartDate->copy()->addWeeks(13)->format('Y-m-d'), // Due at 13th week
        ];
    }

    /**
     * Check if payment amount matches an installment type
     * 
     * @param float $amount Payment amount
     * @param array $installments Installment breakdown
     * @return string|null Matched installment type or null
     */
    public static function detectInstallmentType($amount, $installments)
    {
        $amount = (float)$amount;
        $tolerance = 0.01; // Allow small rounding differences

        foreach (['Prelim', 'Midterm', 'Final'] as $type) {
            if (abs($installments[$type] - $amount) < $tolerance) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Calculate total down payment as percentage of tuition
     * Default: 25% of total assessment as downpayment
     * 
     * @param float $totalAssessment Total tuition + misc fees
     * @param float $percentageOfDownpayment Percentage to calculate downpayment (0-100)
     * @return float Downpayment amount
     */
    public static function calculateDownpaymentFromAssessment($totalAssessment, $percentageOfDownpayment = 25)
    {
        return round(($totalAssessment * $percentageOfDownpayment) / 100, 2);
    }

    /**
     * Get installment schedule as formatted string
     * 
     * @param float $totalAmount Total downpayment
     * @param string $type Distribution type
     * @return string Formatted installment schedule
     */
    public static function getInstallmentSchedule($totalAmount, $type = 'equal')
    {
        $installments = self::calculateInstallments($totalAmount, $type);
        $dueDates = self::getInstallmentDueDates();

        $schedule = "**INSTALLMENT PAYMENT SCHEDULE**\n";
        $schedule .= "Total Down Payment: ₱" . number_format($totalAmount, 2) . "\n\n";

        foreach (['Prelim', 'Midterm', 'Final'] as $phase) {
            $schedule .= "$phase: ₱" . number_format($installments[$phase], 2);
            $schedule .= " (Due: {$dueDates[$phase]})\n";
        }

        return $schedule;
    }
}
