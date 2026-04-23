<?php

namespace App\Services;

use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DroppedStudentReportService
{
    /**
     * SHS fee constants (from school policy document)
     */
    const SHS_REGISTRATION_FEE    = 1000.00;
    const SHS_MISC_HALF            = 1705.00;   // Half of Miscellaneous Fee
    const SHS_MISC_FULL            = 3410.00;   // Total Miscellaneous Fee
    const SHS_MISC_LAB             = 4610.00;   // Misc + Laboratory Fee
    const SHS_HALF_TOTAL_FEES      = 9210.62;   // Half of Total School Fees (Voucher)

    const COLLEGE_REGISTRATION_FEE = 1000.00;
    const COLLEGE_MISC             = 3410.00;
    const COLLEGE_MISC_LAB         = 4610.00;

    // ── 1. Officially Dropped ─────────────────────────────────────────────────
    public function getOfficiallyDropped(): Collection
    {
        return Enrollment::with(['user', 'payments'])
            ->whereIn('status', ['Dropped', 'Withdrawn'])
            ->get()
            ->map(function ($enrollment) {
                $totalPaid = $enrollment->payments
                    ->where('status', 'Paid')
                    ->sum('amount');

                $penalty = $this->calculateDropCharge($enrollment);

                $lastPayment = $enrollment->payments
                    ->where('status', 'Paid')
                    ->sortByDesc('payment_date')
                    ->first();

                return (object) [
                    'enrollment_id'       => $enrollment->id,
                    'student_id'          => $enrollment->user_id,
                    'name'                => ($enrollment->user->last_name ?? '') . ', ' . ($enrollment->user->first_name ?? ''),
                    'email'               => $enrollment->user->email ?? '',
                    'course'              => $enrollment->course_code,
                    'year_level'          => $enrollment->year_level,
                    'level'               => $enrollment->isSHS() ? 'SHS' : 'College',
                    'voucher_type'        => $enrollment->voucher_type ?? null,
                    'drop_status'         => $enrollment->status,
                    'drop_period'         => $enrollment->drop_period ?? 'Not specified',
                    'drop_date'           => $enrollment->drop_date
                        ? Carbon::parse($enrollment->drop_date)->format('M d, Y')
                        : Carbon::parse($enrollment->updated_at)->format('M d, Y'),
                    'drop_reason'         => $enrollment->drop_reason ?? 'Not specified',
                    'drop_notes'          => $enrollment->drop_notes,
                    'base_tuition'        => (float) ($enrollment->base_tuition ?? 0),
                    'total_paid'          => (float) $totalPaid,
                    'drop_charge'         => $penalty['chargeAmount'],
                    'charge_description'  => $penalty['chargeDescription'],
                    'net_refundable'      => max(0, $totalPaid - $penalty['chargeAmount']),
                    'last_payment_date'   => $lastPayment
                        ? Carbon::parse($lastPayment->payment_date)->format('M d, Y')
                        : 'None',
                ];
            });
    }

    // ── 2. At-Risk / Potential Drops ──────────────────────────────────────────
    public function getAtRiskStudents(int $absenceThreshold = 5, int $paymentGapDays = 30): Collection
    {
        $cutoffDate = Carbon::now()->subDays($paymentGapDays);

        return Enrollment::with(['user', 'payments'])
            ->whereIn('status', ['Enrolled', 'Approved'])
            ->get()
            ->filter(function ($enrollment) use ($absenceThreshold, $cutoffDate) {
                $hasAbsenceRisk = ($enrollment->consecutive_absences ?? 0) >= $absenceThreshold;

                $lastPayment = $enrollment->payments
                    ->where('status', 'Paid')
                    ->sortByDesc('payment_date')
                    ->first();

                $hasPaymentRisk = !$lastPayment ||
                    Carbon::parse($lastPayment->payment_date)->lt($cutoffDate);

                return $hasAbsenceRisk || $hasPaymentRisk;
            })
            ->map(function ($enrollment) use ($paymentGapDays) {
                $lastPayment = $enrollment->payments
                    ->where('status', 'Paid')
                    ->sortByDesc('payment_date')
                    ->first();

                $daysSincePayment = $lastPayment
                    ? (int) Carbon::parse($lastPayment->payment_date)->diffInDays(now())
                    : null;

                $flags = [];
                if (($enrollment->consecutive_absences ?? 0) >= 5) {
                    $flags[] = "{$enrollment->consecutive_absences} consecutive absences";
                }
                if ($daysSincePayment === null) {
                    $flags[] = 'No payment on record';
                } elseif ($daysSincePayment >= $paymentGapDays) {
                    $flags[] = "No payment in {$daysSincePayment} days";
                }

                return (object) [
                    'enrollment_id'        => $enrollment->id,
                    'student_id'           => $enrollment->user_id,
                    'name'                 => ($enrollment->user->last_name ?? '') . ', ' . ($enrollment->user->first_name ?? ''),
                    'email'                => $enrollment->user->email ?? '',
                    'course'               => $enrollment->course_code,
                    'year_level'           => $enrollment->year_level,
                    'level'                => $enrollment->isSHS() ? 'SHS' : 'College',
                    'consecutive_absences' => $enrollment->consecutive_absences ?? 0,
                    'last_payment_date'    => $lastPayment
                        ? Carbon::parse($lastPayment->payment_date)->format('M d, Y')
                        : 'Never',
                    'days_since_payment'   => $daysSincePayment ?? 'N/A',
                    'risk_flags'           => $flags,
                    'total_paid'           => (float) $enrollment->payments->where('status', 'Paid')->sum('amount'),
                ];
            })
            ->values();
    }

    // ── 3. Reason Summary ─────────────────────────────────────────────────────
    public function getDropReasonSummary(): array
    {
        $reasons = Enrollment::whereIn('status', ['Dropped', 'Withdrawn'])
            ->selectRaw('drop_reason, COUNT(*) as count')
            ->groupBy('drop_reason')
            ->pluck('count', 'drop_reason')
            ->toArray();

        $total = array_sum($reasons);

        return collect($reasons)
            ->map(fn($count, $reason) => [
                'reason'     => $reason ?? 'Not specified',
                'count'      => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
            ])
            ->values()
            ->toArray();
    }

    // ── Drop Charge Calculator (School Policy) ────────────────────────────────
    /**
     * Calculate the charge based on the school's official dropping policy.
     *
     * @param  Enrollment  $enrollment
     * @return array{ chargeAmount: float, chargeDescription: string }
     */
    public function calculateDropCharge(Enrollment $enrollment): array
    {
        $isSHS      = $enrollment->isSHS();
        $isVoucher  = !empty($enrollment->voucher_type); // free_tuition or discounted = voucher
        $period     = $enrollment->drop_period ?? 'enrollment_period';
        $baseTuition = (float) ($enrollment->base_tuition ?? 0);

        if ($isSHS) {
            return $isVoucher
                ? $this->shsVoucherCharge($period)
                : $this->shsNonVoucherCharge($period, $baseTuition);
        }

        return $this->collegeCharge($period, $baseTuition);
    }

    /**
     * A. SHS Non-Voucher (Paying) drop charges
     *
     * Period                  | Charge
     * enrollment_period       | Registration Fee (₱1,000)
     * first_quarter           | Misc + Lab Fee (₱1,000)
     * second_quarter          | Misc + Lab Fee (₱4,610)
     * third_quarter           | Misc + Lab + Half Tuition (depends on subjects)
     * final_quarter           | Total Assessment
     */
    private function shsNonVoucherCharge(string $period, float $baseTuition): array
    {
        return match ($period) {
            'enrollment_period' => [
                'chargeAmount'       => self::SHS_REGISTRATION_FEE,
                'chargeDescription'  => 'SHS Non-Voucher — Enrollment Period: Registration Fee only (₱1,000.00).',
            ],
            'first_quarter' => [
                'chargeAmount'       => self::SHS_MISC_HALF,
                'chargeDescription'  => 'SHS Non-Voucher — 1st Quarter: Miscellaneous + Laboratory Fee (₱1,000.00).',
            ],
            'second_quarter' => [
                'chargeAmount'       => self::SHS_MISC_LAB,
                'chargeDescription'  => 'SHS Non-Voucher — 2nd Quarter: Miscellaneous + Laboratory Fee (₱4,610.00).',
            ],
            'third_quarter' => [
                'chargeAmount'       => $baseTuition > 0
                    ? self::SHS_MISC_LAB + ($baseTuition / 2)
                    : self::SHS_MISC_LAB,
                'chargeDescription'  => 'SHS Non-Voucher — 3rd Quarter: Misc + Lab + Half Tuition'
                    . ($baseTuition > 0 ? ' = ₱' . number_format(self::SHS_MISC_LAB + ($baseTuition / 2), 2) . '.' : ' (base tuition not set).'),
            ],
            'final_quarter' => [
                'chargeAmount'       => $baseTuition > 0 ? $baseTuition : self::SHS_MISC_LAB,
                'chargeDescription'  => 'SHS Non-Voucher — Final Quarter: Total Assessment'
                    . ($baseTuition > 0 ? ' = ₱' . number_format($baseTuition, 2) . '.' : ' (base tuition not set).'),
            ],
            default => [
                'chargeAmount'       => self::SHS_REGISTRATION_FEE,
                'chargeDescription'  => 'SHS Non-Voucher — Period not specified. Defaulting to Registration Fee (₱1,000.00).',
            ],
        };
    }

    /**
     * B. SHS Voucher Program / ESC drop charges
     *
     * Period                  | Charge
     * enrollment_period       | Registration Fee (₱1,000)
     * first_quarter           | Half Miscellaneous Fee (₱1,705)
     * second_quarter          | Total Miscellaneous Fee (₱3,410)
     * third_quarter           | Total Misc + Lab Fee (₱4,610)
     * final_quarter           | Half of Total School Fees (₱9,210.62)
     */
    private function shsVoucherCharge(string $period): array
    {
        return match ($period) {
            'enrollment_period' => [
                'chargeAmount'       => self::SHS_REGISTRATION_FEE,
                'chargeDescription'  => 'SHS Voucher/ESC — Enrollment Period: Registration Fee only (₱1,000.00).',
            ],
            'first_quarter' => [
                'chargeAmount'       => self::SHS_MISC_HALF,
                'chargeDescription'  => 'SHS Voucher/ESC — 1st Quarter: Half of Miscellaneous Fee (₱1,705.00).',
            ],
            'second_quarter' => [
                'chargeAmount'       => self::SHS_MISC_FULL,
                'chargeDescription'  => 'SHS Voucher/ESC — 2nd Quarter: Total Miscellaneous Fee (₱3,410.00).',
            ],
            'third_quarter' => [
                'chargeAmount'       => self::SHS_MISC_LAB,
                'chargeDescription'  => 'SHS Voucher/ESC — 3rd Quarter: Total Miscellaneous + Laboratory Fee (₱4,610.00).',
            ],
            'final_quarter' => [
                'chargeAmount'       => self::SHS_HALF_TOTAL_FEES,
                'chargeDescription'  => 'SHS Voucher/ESC — Final Quarter: Half of Total School Fees (₱9,210.62).',
            ],
            default => [
                'chargeAmount'       => self::SHS_REGISTRATION_FEE,
                'chargeDescription'  => 'SHS Voucher/ESC — Period not specified. Defaulting to Registration Fee (₱1,000.00).',
            ],
        };
    }

    /**
     * C. College drop charges
     *
     * Period                  | Charge
     * enrollment_period       | Registration Fee (₱1,000)
     * preliminary             | Miscellaneous Fee (₱1,000)
     * midterm                 | Misc + Lab Fee (₱3,410)
     * pre_final               | Misc + Lab + Half Tuition (depends)
     * final_term              | Total Assessment (depends)
     */
    private function collegeCharge(string $period, float $baseTuition): array
    {
        return match ($period) {
            'enrollment_period' => [
                'chargeAmount'       => self::COLLEGE_REGISTRATION_FEE,
                'chargeDescription'  => 'College — Enrollment Period: Registration Fee only (₱1,000.00).',
            ],
            'preliminary' => [
                'chargeAmount'       => self::COLLEGE_REGISTRATION_FEE,
                'chargeDescription'  => 'College — Preliminary Term: Miscellaneous Fee (₱1,000.00).',
            ],
            'midterm' => [
                'chargeAmount'       => self::COLLEGE_MISC,
                'chargeDescription'  => 'College — Midterm: Miscellaneous Fee (₱3,410.00).',
            ],
            'pre_final' => [
                'chargeAmount'       => $baseTuition > 0
                    ? self::COLLEGE_MISC_LAB + ($baseTuition / 2)
                    : self::COLLEGE_MISC_LAB,
                'chargeDescription'  => 'College — Pre-Final: Misc + Lab + Half Tuition'
                    . ($baseTuition > 0 ? ' = ₱' . number_format(self::COLLEGE_MISC_LAB + ($baseTuition / 2), 2) . '.' : ' (base tuition not set).'),
            ],
            'final_term' => [
                'chargeAmount'       => $baseTuition > 0 ? $baseTuition : self::COLLEGE_MISC_LAB,
                'chargeDescription'  => 'College — Final Term: Total Assessment'
                    . ($baseTuition > 0 ? ' = ₱' . number_format($baseTuition, 2) . '.' : ' (base tuition not set).'),
            ],
            default => [
                'chargeAmount'       => self::COLLEGE_REGISTRATION_FEE,
                'chargeDescription'  => 'College — Period not specified. Defaulting to Registration Fee (₱1,000.00).',
            ],
        };
    }

    /**
     * Get the available drop periods for a given enrollment level.
     */
    public static function getDropPeriods(bool $isSHS): array
    {
        if ($isSHS) {
            return [
                'enrollment_period' => 'Within the Enrollment Period',
                'first_quarter'     => 'Within the 1st Quarter Period',
                'second_quarter'    => 'Within the 2nd Quarter Period',
                'third_quarter'     => 'Within the 3rd Quarter Period',
                'final_quarter'     => 'Within the Final Quarter Period',
            ];
        }

        return [
            'enrollment_period' => 'Within the Enrollment Period',
            'preliminary'       => 'Within the Preliminary Term',
            'midterm'           => 'Within the Midterm',
            'pre_final'         => 'Within the Pre-Final Term',
            'final_term'        => 'Within the Final Term',
        ];
    }
}
