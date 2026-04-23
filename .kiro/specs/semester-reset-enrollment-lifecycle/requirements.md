# Requirements Document

## Introduction

This feature governs the full lifecycle of student enrollment across academic semesters in the school management system. It covers six interconnected areas:

1. **Student Application Form** — removing manual semester/year selection and auto-tagging applications with the currently active semester and academic year.
2. **Semester Reset Logic** — defining exactly what happens to student records when a new semester is activated by the registrar, distinguishing between enrolled and non-enrolled students, and resetting the progress bar "Fill up Form" step to incomplete.
3. **Registrar Archiving** — cleaning up the application request table on semester activation and organizing historical enrollment records into a browsable, read-only archive accessible via an "Archived" button.
4. **Clearance Step** — a new gating step in the enrollment progress bar, placed between form submission and payment, that the registrar must approve before the student can proceed to payment.
5. **Document Persistence Across Semesters** — preserving document upload steps in the progress bar when a student has pending documents from a prior enrollment attempt, while skipping those steps when documents were already verified.
6. **Cashier & Financial Continuity** — preventing duplicate cashier profiles across semesters and carrying forward any outstanding balance into the new semester's total assessment with a clear breakdown.

The system is built in Laravel with Livewire. Relevant tables: `enrollments` (status: Pending, Approved, Enrolled, Rejected, Dropped, Withdrawn), `semesters` (name, academic_year, is_active), `academic_years` (year_name, is_active), `payments` (linked to enrollments via application_id).

---

## Glossary

- **System**: The school management web application built in Laravel/Livewire.
- **Enrollment_Form**: The student-facing multi-step form that creates a record in the `enrollments` table. Also referred to as the "Fill up Form" step in the Progress_Bar.
- **Active_Semester**: The single `semesters` record where `is_active = true` at any given time.
- **Active_Academic_Year**: The single `academic_years` record where `is_active = true` at any given time.
- **Semester_Activation**: The act of a Registrar setting a new semester as the Active_Semester via the `RegistrarSemesterController@activate` endpoint.
- **Academic_Year_Activation**: The act of a Registrar setting an academic year as "Operational" (active) without simultaneously activating a new semester.
- **Non_Enrolled_Student**: A student whose latest enrollment record has a status of Pending, Rejected, Dropped, or Withdrawn.
- **Enrolled_Student**: A student whose latest enrollment record has a status of Enrolled or Approved.
- **Semester_Reset**: The automated process triggered exclusively by Semester_Activation that purges Non_Enrolled_Student records and resets those students to the enrolling stage.
- **Archive**: A read-only collection of enrollment records for Enrolled_Students from past semesters, organized by Academic Year and Semester.
- **Progress_Bar**: The student-facing step indicator showing the sequential stages of the enrollment process.
- **Fill_Up_Form_Step**: The step in the Progress_Bar representing submission of the Enrollment_Form. This step is reset to incomplete (unchecked) on each Semester_Activation.
- **Clearance_Step**: The step in the Progress_Bar placed after the Fill_Up_Form_Step and before the Payment_Step, representing registrar approval of the student's clearance. The student cannot proceed to payment until this step is approved.
- **Online_Documents_Step**: The step in the Progress_Bar representing upload of required online documents.
- **Physical_Documents_Step**: The step in the Progress_Bar representing submission of physical documents confirmed by the registrar.
- **Payment_Step**: The step in the Progress_Bar representing payment at the cashier.
- **Verified_Document**: A document step that has been confirmed as green (approved) in a prior enrollment period.
- **Pending_Document**: A document step that was not yet verified (green) at the time of Semester_Reset.
- **Cashier_Profile**: The set of payment and assessment records associated with a student, identified by their User ID.
- **Outstanding_Balance**: The unpaid portion of a student's total assessment from a previous semester, calculated as (total assessment − total payments confirmed as Paid).
- **Total_Assessment**: The full amount a student owes for a given semester, composed of current semester fees plus any Outstanding_Balance carried forward.
- **Registrar**: A staff member with the `registrar` role who manages semesters, academic years, and enrollment applications.
- **Cashier**: A staff member with the `cashier` role who manages payment assessments and verifies student payments.

---

## Requirements

### Requirement 1: Auto-Tag Applications with Active Semester and Academic Year

**User Story:** As a student, I want the enrollment form to automatically use the current active semester and academic year, so that I do not need to manually select these values and cannot apply under an incorrect period.

#### Acceptance Criteria

1. THE Enrollment_Form SHALL NOT display input fields for semester selection or academic year selection to the student.
2. WHEN a student submits the Enrollment_Form, THE System SHALL retrieve the Active_Semester and Active_Academic_Year and store their values on the new enrollment record.
3. IF no Active_Semester exists at the time of form submission, THEN THE System SHALL display an error message stating "Enrollment is currently unavailable. No active semester has been configured." and SHALL prevent the record from being saved.
4. IF no Active_Academic_Year exists at the time of form submission, THEN THE System SHALL display an error message stating "Enrollment is currently unavailable. No active academic year has been configured." and SHALL prevent the record from being saved.
5. THE Enrollment_Form SHALL display the name of the Active_Semester and Active_Academic_Year as read-only informational text so the student can confirm the period they are applying under.

---

### Requirement 2: Semester Reset — Non-Enrolled Students

**User Story:** As a registrar, I want the system to automatically reset non-enrolled students when I activate a new semester, so that only students who completed enrollment remain in the active roster and others must re-apply.

#### Acceptance Criteria

1. WHEN Semester_Activation occurs, THE System SHALL identify all enrollment records whose status is one of: Pending, Rejected, Dropped, or Withdrawn.
2. WHEN Semester_Activation occurs, THE System SHALL delete all enrollment records identified in criterion 1.
3. WHEN Semester_Activation occurs, THE System SHALL NOT delete or modify any enrollment record whose status is Enrolled or Approved.
4. WHEN Semester_Activation occurs and a Non_Enrolled_Student's enrollment record has been deleted, THE System SHALL set that student's `users.status` field to "Enrolling" so the student is redirected to the enrollment form on next login.
5. WHEN Semester_Activation occurs, THE System SHALL reset the Fill_Up_Form_Step in the Progress_Bar to incomplete (unchecked) for all students whose records were deleted, requiring them to submit a new Enrollment_Form for the new term.
6. WHEN a student whose status is "Enrolling" accesses the student dashboard, THE System SHALL redirect the student to the Enrollment_Form.
7. WHILE a student's `users.status` is "Enrolling", THE System SHALL NOT display payment or assessment pages to that student.
8. THE System SHALL execute the Semester_Reset within a single database transaction so that a partial failure does not leave the data in an inconsistent state.
9. WHEN Semester_Activation occurs, THE System SHALL log the number of enrollment records deleted and the number of students reset to "Enrolling" status.

---

### Requirement 3: Academic Year Activation Does Not Trigger Reset

**User Story:** As a registrar, I want setting an academic year as "Operational" (active) to have no effect on student enrollment statuses, so that I can establish the year context for future semesters without disrupting students who are finishing their ongoing term.

#### Acceptance Criteria

1. WHEN Academic_Year_Activation occurs without a concurrent Semester_Activation, THE System SHALL NOT delete any enrollment records.
2. WHEN Academic_Year_Activation occurs without a concurrent Semester_Activation, THE System SHALL NOT modify any student's `users.status`.
3. WHEN Academic_Year_Activation occurs without a concurrent Semester_Activation, THE System SHALL NOT reset the Fill_Up_Form_Step or any other step in any student's Progress_Bar.
4. THE System SHALL only trigger the Semester_Reset process when a new semester is explicitly activated via Semester_Activation.
5. Academic_Year_Activation SHALL serve solely as a structural context marker, establishing the academic year under which future semesters will be created, without affecting any active student records.

---

### Requirement 4: Registrar Application Table Cleanup on Semester Activation

**User Story:** As a registrar, I want the application request table to be automatically cleaned up when a new semester is activated, so that I only see current-semester applications and past records are properly archived.

#### Acceptance Criteria

1. WHEN Semester_Activation occurs, THE System SHALL remove from the active application request view all enrollment records belonging to Non_Enrolled_Students (as defined in Requirement 2, criterion 1).
2. WHEN Semester_Activation occurs, THE System SHALL move all enrollment records belonging to Enrolled_Students from the active application request view into the Archive.
3. THE Archive SHALL store each archived enrollment record with its associated Academic Year label and Semester label so records can be grouped and browsed by period.
4. THE System SHALL organize archived records into logical groupings by Academic Year and Semester, each grouping containing the list of Enrolled_Students for that period.
5. THE Registrar SHALL be able to access the Archive from within the Enrolled Students section of the registrar panel via a clearly labeled "Archived" button.
6. WHILE viewing the Archive, THE System SHALL display enrollment records as read-only and SHALL NOT allow the Registrar to edit, approve, reject, or delete archived records.
7. THE Archive SHALL display, for each archived record, at minimum: student name, course/program, year level, semester, academic year, and enrollment status at the time of archiving.

---

### Requirement 5: Archive Data Integrity

**User Story:** As a registrar, I want archived records to be permanently preserved and accurately reflect the state of enrollment at the time of archiving, so that historical data is reliable for auditing and reporting.

#### Acceptance Criteria

1. THE System SHALL preserve archived enrollment records indefinitely and SHALL NOT delete them during subsequent Semester_Activations.
2. WHEN an enrollment record is moved to the Archive, THE System SHALL record the Academic Year and Semester values at the time of archiving on the archived record.
3. THE System SHALL NOT allow archived records to be modified after archiving.
4. IF a student was Enrolled in multiple past semesters, THEN THE Archive SHALL contain a separate record for each semester that student was enrolled, each in the correct period grouping.

---

### Requirement 6: Clearance Step in Enrollment Progress Bar

**User Story:** As a registrar, I want to control a clearance step in the student's enrollment progress bar, so that I can verify a student's standing before they are allowed to proceed to payment.

#### Acceptance Criteria

1. THE Progress_Bar SHALL include a Clearance_Step positioned after the Fill_Up_Form_Step and before the Payment_Step.
2. WHEN a student submits the Enrollment_Form, THE System SHALL set the Clearance_Step to pending (yellow/incomplete) status.
3. WHILE the Clearance_Step is not approved, THE System SHALL display the Payment_Step as locked (grey) and SHALL NOT allow the student to access the payment page.
4. WHEN the Registrar views a student's application in the application view, THE System SHALL display a control to update the student's Clearance_Step status.
5. WHEN the Registrar approves the Clearance_Step for a student, THE System SHALL set the Clearance_Step to approved (green) status and unlock the Payment_Step for that student.
6. WHEN the Clearance_Step is approved (green), THE System SHALL allow the student to proceed to the Payment_Step.
7. THE System SHALL NOT allow a student to bypass the Clearance_Step to reach the Payment_Step by any means.

---

### Requirement 7: Document Persistence Across Semester Resets

**User Story:** As a student, I want my previously verified documents to be recognized when I re-enroll in a new semester, so that I do not have to re-upload documents that were already approved.

#### Acceptance Criteria

1. WHEN Semester_Activation occurs and a student has at least one Pending_Document (Online_Documents_Step or Physical_Documents_Step not yet verified as green) from their deleted enrollment record, THE System SHALL retain those document steps as the first steps in the student's Progress_Bar when they begin re-enrollment.
2. WHEN Semester_Activation occurs and a student's Online_Documents_Step and Physical_Documents_Step were both Verified_Documents (green) in the deleted enrollment record, THE System SHALL start the student's Progress_Bar at the Fill_Up_Form_Step for the new semester, skipping the document upload steps.
3. THE System SHALL determine document persistence status at the time of Semester_Reset, based on the state of the enrollment record being deleted.
4. WHEN a student with Pending_Documents re-enrolls, THE System SHALL require the student to complete the outstanding document steps before proceeding to the Fill_Up_Form_Step.
5. THE System SHALL NOT require a student to re-upload documents that were already verified (green) in a prior enrollment period.

---

### Requirement 8: Cashier Profile Uniqueness Across Semesters

**User Story:** As a cashier, I want the system to use a student's existing cashier profile when they re-enroll in a new semester, so that payment history is consolidated and no duplicate profiles are created.

#### Acceptance Criteria

1. WHEN a student submits a new Enrollment_Form for a new semester, THE System SHALL look up the student's existing payment records using the student's `user_id` as the unique identifier.
2. THE System SHALL NOT create a duplicate cashier profile or duplicate payment assessment record for a student who already has payment history in the system.
3. WHEN a new enrollment record is created for a returning student, THE System SHALL associate new payment records with the same `user_id` as all prior payment records for that student.

---

### Requirement 9: Outstanding Balance Carry-Forward

**User Story:** As a cashier, I want the system to automatically calculate and carry forward any outstanding balance from a student's previous semester into their new total assessment, so that unpaid amounts are not lost between semesters.

#### Acceptance Criteria

1. WHEN a new enrollment record is created for a student who has a prior enrollment record with an Outstanding_Balance greater than zero, THE System SHALL calculate the Outstanding_Balance as: (sum of all payment records with status "Paid" for the prior enrollment) subtracted from (the total assessment amount stored on the prior enrollment record).
2. WHEN a new enrollment record is created for a student with an Outstanding_Balance, THE System SHALL store the Outstanding_Balance amount on the new enrollment record in a dedicated `carried_balance` field.
3. THE Total_Assessment for the new enrollment SHALL equal the sum of the current semester fees and the `carried_balance`.
4. WHEN a cashier views a student's payment assessment for the current semester, THE System SHALL display the current semester fees and the carried-over balance as two separate line items, clearly labeled "Current Semester Fees" and "Previous Outstanding Balance".
5. IF a student has no Outstanding_Balance from a prior semester, THEN THE System SHALL set `carried_balance` to zero and display only the current semester fees in the assessment breakdown.
6. THE System SHALL recalculate the Outstanding_Balance at the time the new enrollment record is created, using the most recent prior enrollment record for that student.

---

### Requirement 10: Semester Activation Confirmation

**User Story:** As a registrar, I want to be warned before activating a new semester about the consequences of the action, so that I do not accidentally trigger a reset.

#### Acceptance Criteria

1. WHEN the Registrar initiates Semester_Activation, THE System SHALL display a confirmation dialog listing: the number of Non_Enrolled_Students whose records will be deleted, the number of Enrolled_Students whose records will be archived, and a warning that this action cannot be undone.
2. WHEN the Registrar confirms the activation in the dialog, THE System SHALL proceed with Semester_Activation and the Semester_Reset.
3. WHEN the Registrar cancels the dialog, THE System SHALL NOT perform any changes to semester, enrollment, or student records.
4. AFTER Semester_Activation completes, THE System SHALL display a summary notification to the Registrar showing: the new Active_Semester name, the count of records deleted, and the count of records archived.
