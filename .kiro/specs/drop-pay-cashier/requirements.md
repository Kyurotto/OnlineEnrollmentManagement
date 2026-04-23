# Requirements Document

## Introduction

The Drop Pay feature extends the school cashier system's `PaymentManager` Livewire component to support a distinct "Drop Pay" payment mode. When a student is being dropped or withdrawn, the cashier can switch to Drop Pay mode to record payments that are specifically tied to the dropping process. These drop payments are flagged in the database, shown with a special "DROP" badge in the payment history, and included in the registrar's "Total Paid" column on the Dropped Students report page. Additionally, students whose enrollment status is "Dropped" or "Withdrawn" display a "DROPPED" indicator badge in the cashier panel's student header area.

---

## Glossary

- **PaymentManager**: The Livewire component at `app/Livewire/PaymentManager.php` that powers the cashier panel at `/cashier/payments`.
- **Cashier_Panel**: The right-side panel in the PaymentManager UI that shows student payment details, tabs, and the transaction entry section.
- **Payment_Mode**: The default operating mode of the Cashier_Panel, activated by the "Payment" button. The submit button is labeled "Pay".
- **Drop_Pay_Mode**: An alternative operating mode of the Cashier_Panel, activated by the "Drop Pay" button. The submit button is labeled "Dropping Payment".
- **Transaction_Entry**: The bottom section of the Cashier_Panel containing the amount input, reference number input, and the submit button.
- **Drop_Payment**: A payment record created while the Cashier_Panel is in Drop_Pay_Mode. Identified by `is_drop_payment = true` on the `payments` table.
- **Regular_Payment**: A payment record created while the Cashier_Panel is in Payment_Mode. Identified by `is_drop_payment = false` on the `payments` table.
- **DROP_Badge**: A visual indicator (amber/orange badge labeled "DROP") shown next to a transaction in the History tab when that transaction is a Drop_Payment.
- **DROPPED_Badge**: A visual indicator badge shown in the student header area when the student's enrollment status is "Dropped" or "Withdrawn".
- **Voucher_Badge**: The existing "Free Tuition" or "Discounted" badge already displayed in the student header area.
- **DroppedStudentReportService**: The service at `app/Services/DroppedStudentReportService.php` that calculates data for the registrar's dropped students report.
- **Total_Paid**: The sum of all `Paid`-status payments for a dropped/withdrawn student, shown in the registrar's officially dropped table.
- **Enrollment**: A record in the `enrollments` table representing a student's enrollment for a semester. Has a `status` field that can be "Dropped" or "Withdrawn".

---

## Requirements

### Requirement 1: Drop Pay Mode Toggle

**User Story:** As a cashier, I want to switch between Payment mode and Drop Pay mode using dedicated buttons, so that I can clearly distinguish regular payments from drop-related payments.

#### Acceptance Criteria

1. THE Cashier_Panel SHALL display a "Payment" button and a "Drop Pay" button in the student header area when a student is selected.
2. WHEN the cashier clicks the "Payment" button, THE Cashier_Panel SHALL activate Payment_Mode and deactivate Drop_Pay_Mode.
3. WHEN the cashier clicks the "Drop Pay" button, THE Cashier_Panel SHALL activate Drop_Pay_Mode and deactivate Payment_Mode.
4. WHILE Payment_Mode is active, THE Cashier_Panel SHALL visually highlight the "Payment" button as the active selection and render the "Drop Pay" button as inactive.
5. WHILE Drop_Pay_Mode is active, THE Cashier_Panel SHALL visually highlight the "Drop Pay" button as the active selection and render the "Payment" button as inactive.
6. THE Cashier_Panel SHALL ensure that Payment_Mode and Drop_Pay_Mode are mutually exclusive — only one mode SHALL be active at any given time.
7. WHEN a new student is selected from the student list, THE Cashier_Panel SHALL reset to Payment_Mode.

---

### Requirement 2: Drop Pay Mode — Transaction Entry

**User Story:** As a cashier, I want the Transaction Entry section to clearly indicate when I am in Drop Pay mode, so that I do not accidentally record a drop payment as a regular payment.

#### Acceptance Criteria

1. WHILE Payment_Mode is active, THE Transaction_Entry SHALL display a submit button labeled "Pay".
2. WHILE Drop_Pay_Mode is active, THE Transaction_Entry SHALL display a submit button labeled "Dropping Payment".
3. WHILE Drop_Pay_Mode is active, THE Transaction_Entry SHALL display a visual indicator (e.g., amber/orange border or header label) distinguishing it from Payment_Mode.
4. THE Transaction_Entry SHALL retain the same input fields (Amount Paid, Reference No.) in both Payment_Mode and Drop_Pay_Mode.

---

### Requirement 3: Drop Payment Record Creation

**User Story:** As a cashier, I want drop payments to be stored with a distinguishing flag, so that they can be identified separately from regular payments in reports and history.

#### Acceptance Criteria

1. THE payments table SHALL contain a boolean column `is_drop_payment` with a default value of `false`.
2. WHEN the cashier submits a payment while in Payment_Mode, THE PaymentManager SHALL create a Payment record with `is_drop_payment = false`.
3. WHEN the cashier submits a payment while in Drop_Pay_Mode, THE PaymentManager SHALL create a Payment record with `is_drop_payment = true`.
4. THE PaymentManager SHALL apply the same validation rules (amount > 0, student selected) to Drop_Payment creation as to Regular_Payment creation.
5. WHEN a Drop_Payment is created successfully, THE PaymentManager SHALL refresh the student's balance and payment history.
6. WHEN a Drop_Payment is created successfully, THE PaymentManager SHALL display a success message confirming the drop payment was processed.

---

### Requirement 4: Drop Pay Mode — Shared Tab Content

**User Story:** As a cashier, I want to see the same fee assessment, balance, and history information in Drop Pay mode as in Payment mode, so that I have full context when processing a drop payment.

#### Acceptance Criteria

1. WHILE Drop_Pay_Mode is active, THE Cashier_Panel SHALL display the same three tabs: "Payment Assessment", "Balance", and "History".
2. WHILE Drop_Pay_Mode is active, THE Cashier_Panel SHALL display the same fee breakdown (Tuition, Miscellaneous Fees, discount) under the Payment Assessment tab.
3. WHILE Drop_Pay_Mode is active, THE Cashier_Panel SHALL display the same current balance under the Balance tab.
4. WHILE Drop_Pay_Mode is active, THE Cashier_Panel SHALL display the full payment history (including both Regular_Payments and Drop_Payments) under the History tab.

---

### Requirement 5: DROP Badge in Payment History

**User Story:** As a cashier, I want drop payments to be visually marked in the payment history, so that I can quickly identify which transactions were processed as drop payments.

#### Acceptance Criteria

1. WHEN the History tab is displayed and a transaction is a Drop_Payment (`is_drop_payment = true`), THE Cashier_Panel SHALL render a "DROP" badge next to the transaction entry.
2. WHEN the History tab is displayed and a transaction is a Regular_Payment (`is_drop_payment = false`), THE Cashier_Panel SHALL NOT render a "DROP" badge for that transaction.
3. THE DROP_Badge SHALL be visually distinct (e.g., amber/orange color) and labeled "DROP" in uppercase.
4. THE DROP_Badge SHALL appear alongside the existing transaction status badge (e.g., "Paid", "Pending") without replacing it.

---

### Requirement 6: DROPPED Indicator on Student Header

**User Story:** As a cashier, I want to see a "DROPPED" badge on the student header when a student's enrollment is dropped or withdrawn, so that I am immediately aware of the student's status before processing any payment.

#### Acceptance Criteria

1. WHEN a student is selected and the student's latest enrollment `status` is "Dropped" or "Withdrawn", THE Cashier_Panel SHALL display a "DROPPED" badge in the student header area.
2. WHEN a student is selected and the student's latest enrollment `status` is neither "Dropped" nor "Withdrawn", THE Cashier_Panel SHALL NOT display the "DROPPED" badge.
3. THE DROPPED_Badge SHALL appear alongside the existing Voucher_Badge (Free Tuition / Discounted) without replacing it.
4. THE DROPPED_Badge SHALL be visually distinct from the Voucher_Badge (e.g., red or rose color, labeled "DROPPED" in uppercase).
5. WHEN the enrollment status changes (e.g., after a restore action), THE Cashier_Panel SHALL update the DROPPED_Badge visibility upon the next student selection.

---

### Requirement 7: Total Paid Includes Drop Payments in Registrar Report

**User Story:** As a registrar, I want the "Total Paid" column in the officially dropped students table to include drop payments, so that I have an accurate picture of all money collected from a dropped student.

#### Acceptance Criteria

1. WHEN the DroppedStudentReportService calculates `total_paid` for a dropped or withdrawn student, THE DroppedStudentReportService SHALL include all Payment records with `status = 'Paid'` regardless of the `is_drop_payment` flag.
2. THE DroppedStudentReportService SHALL NOT exclude Drop_Payments from the `total_paid` calculation.
3. WHEN a student has only Drop_Payments and no Regular_Payments, THE DroppedStudentReportService SHALL correctly report the sum of those Drop_Payments as `total_paid`.
4. WHEN a student has both Regular_Payments and Drop_Payments, THE DroppedStudentReportService SHALL report the combined sum as `total_paid`.
