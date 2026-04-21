# Installment Payment System Implementation Guide

## Overview
This implementation provides an automatic downpayment calculation system that splits tuition payments into three installment phases: **Prelim**, **Midterm**, and **Final**.

## Features
- ♦ Automatic downpayment calculation (% of total assessment)
- ♦ Two distribution methods: **Equal** (33.33% each) or **Weighted** (30%, 30%, 40%)
- ♦ Automatic due date calculation for each phase
- ♦ Payment tracking and history
- ♦ Installment status monitoring
- ♦ Database schema with installment fields

## Components Created

### 1. **Migration File**
**Location:** `database/migrations/2026_04_21_000000_add_installment_fields_to_payments_table.php`

**New Fields Added:**
- `installment_type` (enum): Prelim, Midterm, Final, or Full Payment
- `down_payment_total` (decimal): Total downpayment amount reference
- `is_installment` (boolean): Flag to identify installment payments

**Run Migration:**
```bash
php artisan migrate
```

### 2. **InstallmentCalculator Service**
**Location:** `app/Services/InstallmentCalculator.php`

**Key Methods:**
- `calculateInstallments($totalAmount, $type)` - Splits amount into 3 installments
- `calculateDownpaymentFromAssessment($totalAssessment, $percentage)` - Calculates downpayment
- `getInstallmentDueDates($startDate)` - Returns due dates for each phase
- `getInstallmentSchedule($totalAmount, $type)` - Returns formatted schedule

**Example Usage:**
```php
use App\Services\InstallmentCalculator;

// Calculate installments for ₱10,000 downpayment
$installments = InstallmentCalculator::calculateInstallments(10000, 'equal');
// Returns: ['Prelim' => 3333.33, 'Midterm' => 3333.33, 'Final' => 3333.34]

// Calculate downpayment from total assessment
$downpayment = InstallmentCalculator::calculateDownpaymentFromAssessment(50000, 25);
// Returns: 12500 (25% of 50,000)

// Get due dates
$dueDates = InstallmentCalculator::getInstallmentDueDates();
// Returns: ['Prelim' => '2026-05-19', 'Midterm' => '2026-06-02', 'Final' => '2026-06-16']
```

### 3. **Payment Model Updates**
**Location:** `app/Models/Payment.php`

**New Methods:**
- `scopeInstallments($query)` - Filter installment payments
- `scopeFullPayments($query)` - Filter full payments
- `scopeByInstallmentType($query, $type)` - Filter by phase
- `getEnrollmentInstallments($enrollmentId)` - Get all installments for enrollment

**Example Usage:**
```php
// Get all installment payments for an enrollment
$installments = Payment::getEnrollmentInstallments($enrollmentId);

// Get only Prelim payments
$prelimPayments = Payment::byInstallmentType('Prelim')->get();

// Get all installment payments
$allInstallments = Payment::installments()->get();
```

### 4. **InstallmentPaymentManager Component**
**Location:** `app/Livewire/InstallmentPaymentManager.php`

**Features:**
- Configure downpayment percentage
- Choose distribution type (equal/weighted)
- Display 3-phase installment breakdown
- Record payments for each phase
- Track payment history
- Show remaining balance

**Usage in Blade:**
```blade
<livewire:installment-payment-manager :enrollmentId="$enrollmentId" :studentId="$studentId" />
```

### 5. **Installment Payment View**
**Location:** `resources/views/livewire/installment-payment-manager.blade.php`

Visual dashboard showing:
- ✓ Configuration panel
- ✓ Total assessment & downpayment summary
- ✓ 3-phase installment cards
- ✓ Payment history table
- ✓ Summary statistics

## Integration Steps

### Step 1: Create Services Directory (if not exists)
```bash
mkdir -p app/Services
```

### Step 2: Run Migration
```bash
php artisan migrate
```

### Step 3: Add to PaymentManager Integration (Optional)
In your `PaymentManager.php`, you can add:

```php
use App\Services\InstallmentCalculator;

public function getInstallmentBreakdown()
{
    $downpayment = InstallmentCalculator::calculateDownpaymentFromAssessment(
        $this->totalAssessment, 
        25 // 25% downpayment
    );
    
    $this->installments = InstallmentCalculator::calculateInstallments($downpayment, 'equal');
    $this->dueDates = InstallmentCalculator::getInstallmentDueDates();
}
```

### Step 4: Display In Payment Form
Add installment option when recording payment:

```blade
<div class="mb-4">
    <label class="block text-sm font-medium">Payment Type</label>
    <select wire:model="paymentType">
        <option value="">Full Payment</option>
        <option value="Prelim">Prelim Installment</option>
        <option value="Midterm">Midterm Installment</option>
        <option value="Final">Final Installment</option>
    </select>
</div>
```

## Configuration Examples

### Example 1: Equal Distribution (Default)
```
Total Down Payment: ₱12,500
- Prelim:  ₱4,166.67 (Due: Week 4)
- Midterm: ₱4,166.67 (Due: Week 8)
- Final:   ₱4,166.66 (Due: Week 13)
```

### Example 2: Weighted Distribution
```
Total Down Payment: ₱12,500
- Prelim:  ₱3,750 (30%, Due: Week 4)
- Midterm: ₱3,750 (30%, Due: Week 8)
- Final:   ₱5,000 (40%, Due: Week 13)
```

### Example 3: Custom Downpayment Percentage
```
Total Assessment: ₱50,000
Downpayment %: 20% → ₱10,000 (split into 3)
Downpayment %: 50% → ₱25,000 (split into 3)
```

## Database Schema

```sql
-- New columns in payments table:
- installment_type VARCHAR(20) -- 'Prelim', 'Midterm', 'Final', 'Full Payment'
- down_payment_total DECIMAL(10,2) -- Reference to total downpayment
- is_installment BOOLEAN -- Flag for installment payments
```

## Payment Query Examples

```php
// Get all prelim installments
$prelimPayments = Payment::byInstallmentType('Prelim')->get();

// Get paid installments for a student
$paidInstallments = Payment::where('user_id', $studentId)
    ->installments()
    ->where('status', 'Paid')
    ->get();

// Get pending installments
$pendingInstallments = Payment::where('is_installment', true)
    ->where('status', 'Pending')
    ->get();

// Calculate total paid for enrollment
$totalPaid = Payment::where('application_id', $enrollmentId)
    ->installments()
    ->sum('amount');
```

## Customization

### Change Downpayment Percentage
Modify the default in `InstallmentPaymentManager.php`:
```php
public $downpaymentPercentage = 30; // Change from 25 to 30%
```

### Change Due Date Formula
Edit `getInstallmentDueDates()` in `InstallmentCalculator.php`:
```php
'Prelim' => $enrollmentStartDate->copy()->addWeeks(3)->format('Y-m-d'), // 3rd week instead
```

### Add Custom Distribution Types
Extend `calculateInstallments()` method:
```php
public static function calculateInstallments($totalAmount, $type = 'equal')
{
    // ... existing code ...
    
    if ($type === 'custom') {
        return [
            'Prelim' => round($totalAmount * 0.25, 2),
            'Midterm' => round($totalAmount * 0.35, 2),
            'Final' => round($totalAmount * 0.40, 2),
        ];
    }
}
```

## Next Steps

1. ✅ Run database migration
2. ✅ Integrate component into payment flow
3. ✅ Test with sample student enrollment
4. ✅ Configure downpayment percentage per academic level
5. ✅ Create admin dashboard to view all installments
6. ✅ Add automatic notifications for due dates

---
**Note:** Adjust assessment calculation logic in `InstallmentPaymentManager::loadEnrollmentData()` based on your actual enrollment data structure.
