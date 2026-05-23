<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['is_fully_paid'];

    protected $casts = [
        'is_regular'             => 'boolean',
        'credentials_verified'   => 'boolean',
        'physical_documents_received' => 'boolean',
        'last_audited_at'        => 'datetime',
        'archived_at'            => 'datetime',
        'previous_balance'       => 'decimal:2',
    ];

    /**
     * Map classification_reason to the existing irregular_reason column.
     */
    public function getClassificationReasonAttribute(): ?string
    {
        // Prefer the new column if it exists, fall back to irregular_reason
        return $this->attributes['classification_reason']
            ?? $this->attributes['irregular_reason']
            ?? null;
    }

    public function setClassificationReasonAttribute(?string $value): void
    {
        $this->attributes['classification_reason'] = $value;
    }

    /**
     * Valid classification reasons for irregular students.
     */
    public const CLASSIFICATION_REASONS = [
        'Academic Deficiency'       => 'Academic Deficiency (due to failed grades)',
        'Transferee Credit Gap'     => 'Transferee Credit Gap (missing credentials or unmatched subjects)',
        'Shifter/Bridging'          => 'Shifter/Bridging (changing strands/courses)',
        'Financial Underloading'    => 'Financial Underloading (requesting fewer units due to tuition)',
        'Personal/Health Reasons'   => 'Personal/Health Reasons (requested light load)',
        'Graduating Special Load'   => 'Graduating Special Load (customized final year schedule)',
    ];

    /**
     * SHS-specific classification reasons.
     */
    public const SHS_CLASSIFICATION_REASONS = [
        'Academic Deficiency'       => 'Academic Deficiency (failed/incomplete subjects)',
        'Strand Shifter'            => 'Strand Shifter (changing to a different SHS strand)',
        'Transferee Credit Gap'     => 'Transferee Credit Gap (missing JHS credentials or unmatched subjects)',
        'Financial Underloading'    => 'Financial Underloading (requesting fewer subjects due to tuition)',
        'Personal/Health Reasons'   => 'Personal/Health Reasons (requested light load)',
        'Repeater'                  => 'Repeater (retaking a grade level or subject)',
        'Graduating Special Load'   => 'Graduating Special Load (customized final semester schedule)',
    ];

    /**
     * Run the automated audit to determine Regular vs Irregular status.
     * Returns true if the audit changed the record (so caller can save).
     */
    public function runStatusAudit(): bool
    {
        $changed = false;

        // Rule 1 — Transferee is Irregular by default until credentials are verified
        if ($this->student_type === 'Transferee' && !$this->credentials_verified) {
            if ($this->is_regular !== false || $this->classification_reason !== 'Transferee Credit Gap') {
                $this->is_regular             = false;
                $this->classification_reason  = 'Transferee Credit Gap';
                $this->last_audited_at        = now();
                $changed = true;
            }
            return $changed;
        }

        // Rule 2 — Shifter is Irregular by default
        if ($this->student_type === 'Shifter') {
            if ($this->is_regular !== false || $this->classification_reason !== 'Shifter/Bridging') {
                $this->is_regular             = false;
                $this->classification_reason  = 'Shifter/Bridging';
                $this->last_audited_at        = now();
                $changed = true;
            }
            return $changed;
        }

        // Rule 3 — Grade check: if any grade is Fail / INC / Dropped → Irregular
        // (Grades are stored in the enrollments.grade_remarks field if present,
        //  or you can extend this to a separate grades table later.)
        $hasAcademicDeficiency = $this->hasAcademicDeficiency();
        if ($hasAcademicDeficiency) {
            if ($this->is_regular !== false || $this->classification_reason !== 'Academic Deficiency') {
                $this->is_regular             = false;
                $this->classification_reason  = 'Academic Deficiency';
                $this->last_audited_at        = now();
                $changed = true;
            }
            return $changed;
        }

        // If none of the above triggered, mark as Regular (only if not already set)
        if ($this->is_regular !== true) {
            $this->is_regular             = true;
            $this->classification_reason  = null;
            $this->last_audited_at        = now();
            $changed = true;
        }

        return $changed;
    }

    /**
     * Check if the student has any academic deficiency flags.
     * Extend this when a dedicated grades table is added.
     */
    public function hasAcademicDeficiency(): bool
    {
        // Check grade_remarks column if it exists on this enrollment
        if (isset($this->grade_remarks)) {
            $failKeywords = ['fail', 'inc', 'dropped', 'failed', 'incomplete'];
            foreach ($failKeywords as $keyword) {
                if (str_contains(strtolower((string) $this->grade_remarks), $keyword)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Human-readable classification label.
     */
    public function getClassificationLabelAttribute(): string
    {
        if ($this->is_regular === true) {
            return 'Regular';
        }
        if ($this->is_regular === false) {
            return 'Irregular';
        }
        return 'Not Audited';
    }

    /**
     * Whether this enrollment has any warning flags the registrar should see.
     */
    public function hasWarningFlags(): bool
    {
        // Unverified transferee credentials
        if ($this->student_type === 'Transferee' && !$this->credentials_verified) {
            return true;
        }
        // Academic deficiency
        if ($this->hasAcademicDeficiency()) {
            return true;
        }
        // Missing required documents
        if (!$this->physical_documents_received) {
            return true;
        }
        return false;
    }

    // *** THIS IS THE MISSING PART ***
    public function user()
    {
        // This tells Laravel that "Enrollment" belongs to a "User"
        return $this->belongsTo(User::class, 'user_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'application_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Determine if enrollment is SHS based on course_code
     */
    public function isSHS()
    {
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        return in_array($this->course_code, $shsStrands);
    }

    /**
     * Get enrollment level (shs or college)
     */
    public function getLevel()
    {
        return $this->isSHS() ? 'shs' : 'college';
    }

    /**
     * Get level-specific document fields
     */
    public function getDocumentFields()
    {
        if ($this->isSHS()) {
            return [
                'form_137_path' => 'JHS Report Card (SF9)',
                'sf10_path' => 'SF10 (Permanent Record)',
                'good_moral_path' => 'Certificate of Good Moral',
                'psa_path' => 'PSA Birth Certificate',
                'id_picture_path' => '2x2 ID Portrait'
            ];
        }
        return [
            'form_137_path' => 'Form 137 (Report Card)',
            'good_moral_path' => 'Certificate of Good Moral',
            'psa_path' => 'PSA Birth Certificate',
            'id_picture_path' => '2x2 ID Portrait'
        ];
    }

    /**
     * Check if the enrollment has been fully paid based on assessment and payments.
     */
    public function getIsFullyPaidAttribute(): bool
    {
        $assessment = (float)($this->total_assessment ?? (($this->tuition_fee ?? 0) + ($this->miscellaneous_fee ?? 0)));
        if ($assessment <= 0) {
            // Cannot be fully paid if there's no assessment yet
            return false;
        }

        $paid = $this->payments()->where('status', 'Paid')->sum('amount');
        $discount = (float)($this->cashier_discount ?? 0);
        $prevBalance = (float)($this->previous_balance ?? 0);

        $balance = max(0, ($assessment - $discount + $prevBalance) - $paid);
        return $balance <= 0;
    }

}
