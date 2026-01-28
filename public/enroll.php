<?php

require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$current_user = ['first_name' => '', 'middle_name' => '', 'last_name' => '', 'email' => ''];
try {
    $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, email FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u) {
        $current_user['first_name'] = $u['first_name'] ?? '';
        $current_user['middle_name'] = $u['middle_name'] ?? '';
        $current_user['last_name'] = $u['last_name'] ?? '';
        $current_user['email'] = $u['email'] ?? '';
        $parts = preg_split('/\s+/', trim($current_user['first_name'] . ' ' . $current_user['middle_name'] . ' ' . $current_user['last_name']));
        $current_user['first_name'] = $parts[0] ?? '';
        if (count($parts) === 1) {
            $current_user['middle_name'] = '';
            $current_user['last_name'] = '';
        } elseif (count($parts) === 2) {
            $current_user['middle_name'] = '';
            $current_user['last_name'] = $parts[1];
        } else {
            $current_user['last_name'] = array_pop($parts);
            array_shift($parts); 
            $current_user['middle_name'] = implode(' ', $parts);
        }
    }
} catch (Exception $e) {
}

$UPLOAD_BASE = __DIR__ . '/../uploads/enrollment_applications'; 
$PAY_UPLOAD_BASE = __DIR__ . '/../uploads/payments'; 
$MAX_FILE_SIZE = 5 * 1024 * 1024; 
$MAX_PROOF_SIZE = 8 * 1024 * 1024; 
$ALLOWED_MIME = [
    'application/pdf',
    'image/jpeg',
    'image/png',
];

if (!defined('FEE_PER_CREDIT')) {
    define('FEE_PER_CREDIT', 500.00); 
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}
$csrf_token = $_SESSION['csrf_token'];

// Calculate base URL for file links
$projectDir = basename(dirname(__DIR__));
$baseUrl = '/' . $projectDir;

function set_flash($msg) {
    $_SESSION['flash'] = $msg;
}
function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $m = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $m;
    }
    return '';
}

$info_msg = get_flash();
$error_msg = '';

function clean_text($s) {
    return trim(mb_substr((string)$s, 0, 2000));
}

function valid_date($d) {
    $t = strtotime($d);
    if ($t === false) return false;
    return date('Y-m-d', $t) === $d || date('Y-m-d', $t) === $d;
}

function compute_age($birth_date) {
    try {
        $b = new DateTime($birth_date);
        $now = new DateTime('now');
        $diff = $now->diff($b);
        return (int)$diff->y;
    } catch (Throwable $e) {
        return null;
    }
}


// Check if user already has an approved application
$has_approved_application = false;
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollment_applications WHERE user_id = ? AND status = 'approved'");
    $stmt->execute([$user_id]);
    if ($stmt->fetchColumn() > 0) {
        $has_approved_application = true;
    }
} catch (Exception $e) {
    // ignore
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'record_payment') {
    if (empty($_POST['csrf_token']) || !hash_equals($csrf_token, $_POST['csrf_token'])) {
        $error_msg = 'Invalid CSRF token for payment.';
    } else {
        $amount_raw = str_replace(',', '', trim((string)($_POST['amount'] ?? '')));
        if ($amount_raw === '' || !is_numeric($amount_raw) || (float)$amount_raw <= 0) {
            $error_msg = 'Please enter a valid payment amount greater than zero.';
        } else {
            $amount = round((float)$amount_raw, 2);
        }

        $application_id = null;
        if (!empty($_POST['application_id'])) {
            $application_id = (int)$_POST['application_id'];
        }

        $proof_path = null;
        if ($error_msg === '' && !empty($_FILES['proof']) && is_array($_FILES['proof'])) {
            $file = $_FILES['proof'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                if ($file['size'] > $MAX_PROOF_SIZE) {
                    $error_msg = 'Proof file exceeds maximum size of ' . ($MAX_PROOF_SIZE / 1024 / 1024) . ' MB.';
                } else {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    if (!in_array($mime, $ALLOWED_MIME, true)) {
                        $error_msg = 'Unsupported proof file type. Accepts PDF, JPG, PNG.';
                    } else {
                        $userDir = $PAY_UPLOAD_BASE . '/user_' . (int)$user_id;
                        if (!is_dir($userDir)) @mkdir($userDir, 0755, true);
                        $orig = basename($file['name'] ?? 'proof');
                        $ext = pathinfo($orig, PATHINFO_EXTENSION) ?: 'bin';
                        $stored = sprintf('%s_%s_%s.%s', $user_id, time(), bin2hex(random_bytes(6)), $ext);
                        $dest = $userDir . '/' . $stored;
                        if (!move_uploaded_file($file['tmp_name'], $dest)) {
                            $error_msg = 'Failed to save uploaded proof file.';
                        } else {
                            @chmod($dest, 0644);
                            $proof_path = 'uploads/payments/user_' . (int)$user_id . '/' . $stored;
                        }
                    }
                }
            } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
                $error_msg = 'Error uploading proof file.';
            }
        }

        if ($error_msg === '') {
            try {
                if ($application_id) {
                    try {
                        $pstmt = $pdo->prepare("INSERT INTO payments (application_id, user_id, amount, payment_date, payment_status) VALUES (?, ?, ?, NOW(), 'pending')");
                        $pstmt->execute([$application_id, $user_id, $amount]);
                    } catch (PDOException $ex) {
                        error_log('payments insert with application_id failed: ' . $ex->getMessage());
                        $pstmt = $pdo->prepare("INSERT INTO payments (user_id, amount, payment_date, payment_status) VALUES (?, ?, NOW(), 'pending')");
                        $pstmt->execute([$user_id, $amount]);
                    }
                } else {
                    $pstmt = $pdo->prepare("INSERT INTO payments (user_id, amount, payment_date, payment_status) VALUES (?, ?, NOW(), 'pending')");
                    $pstmt->execute([$user_id, $amount]);
                }
                $payment_id = (int)$pdo->lastInsertId();

                if ($proof_path !== null) {
                    try {
                        $pdo->prepare("UPDATE payments SET proof = ? WHERE id = ?")->execute([$proof_path, $payment_id]);
                    } catch (PDOException $ex) {
                        try {
                            $pdo->prepare("UPDATE payments SET proof_path = ? WHERE id = ?")->execute([$proof_path, $payment_id]);
                        } catch (PDOException $ex2) {
                            error_log('Could not save proof path to payments table: ' . $ex2->getMessage());
                        }
                    }
                }

                set_flash('Payment recorded. Admin will verify and update status.');
                $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
                header('Location: enroll.php#payments');
                exit;
            } catch (PDOException $e) {
                error_log('Payment insert error: ' . $e->getMessage());
                $error_msg = 'Failed to record payment. Please try again later.';
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_application') {
    if ($has_approved_application) {
        $error_msg = 'You already have an approved application. You cannot submit another one.';
    } elseif (empty($_POST['csrf_token']) || !hash_equals($csrf_token, $_POST['csrf_token'])) {
        $error_msg = 'Invalid form submission (CSRF check failed).';
    } else {
        $course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : null;
        if (!$course_id) {
            $error_msg = 'Please select one course to apply for.';
        } else {
            $course_ids = [$course_id];

            $notes = clean_text($_POST['notes'] ?? '');

            $student_first = clean_text($_POST['student_first_name'] ?? '');
            $student_middle = clean_text($_POST['student_middle_name'] ?? '');
            $student_last = clean_text($_POST['student_last_name'] ?? '');
            $student_birth = trim($_POST['student_birth_date'] ?? '');
            $student_birthplace = clean_text($_POST['student_birthplace'] ?? '');
            $student_gender = in_array($_POST['student_gender'] ?? '', ['male','female','other'], true) ? $_POST['student_gender'] : '';

            $addr_house = clean_text($_POST['student_addr_house'] ?? '');
            $addr_street = clean_text($_POST['student_addr_street'] ?? '');
            $addr_barangay = clean_text($_POST['student_addr_barangay'] ?? '');
            $addr_city = clean_text($_POST['student_addr_city'] ?? '');
            $addr_province = clean_text($_POST['student_addr_province'] ?? '');
            $addr_country = clean_text($_POST['student_addr_country'] ?? '');
            $addr_zip = clean_text($_POST['student_addr_zip'] ?? '');

            $student_contact = clean_text($_POST['student_contact'] ?? '');
            $student_email = trim($_POST['student_email'] ?? '');

            $student_religion = clean_text($_POST['student_religion'] ?? '');
            $student_year_level = clean_text($_POST['student_year_level'] ?? '');
            $student_age_input = trim($_POST['student_age'] ?? '');
            $student_age = null;
            if ($student_age_input !== '') {
                $student_age = is_numeric($student_age_input) ? (int)$student_age_input : null;
            } elseif ($student_birth !== '' && valid_date($student_birth)) {
                $student_age = compute_age($student_birth);
            }

            $indigenous_belongs = null;
            if (isset($_POST['indigenous_belongs'])) {
                $val = $_POST['indigenous_belongs'];
                if ($val === 'yes') $indigenous_belongs = true;
                elseif ($val === 'no') $indigenous_belongs = false;
            }
            $indigenous_spec = clean_text($_POST['indigenous_spec'] ?? '');

            $manual_amount_raw = trim((string)($_POST['amount'] ?? ''));
            $manual_amount = null;
            
            if ($manual_amount_raw === '') {
                $error_msg = 'Please enter a down payment amount.';
            } else {
                $manual_amount_norm = str_replace(',', '', $manual_amount_raw);
                if (is_numeric($manual_amount_norm)) {
                    $manual_amount = round((float)$manual_amount_norm, 2);
                    if ($manual_amount < 1000) {
                        $error_msg = 'Down payment must be at least 1000.';
                    }
                } else {
                    $error_msg = 'Please enter a valid numeric amount.';
                }
            }

            if ($error_msg === '' && ($student_first === '' || $student_last === '')) {
                $error_msg = 'Please provide student first and last name.';
            } elseif ($error_msg === '' && ($student_email === '' || !filter_var($student_email, FILTER_VALIDATE_EMAIL))) {
                $error_msg = 'Please provide a valid student email address.';
            } elseif ($error_msg === '' && ($student_birth !== '' && !valid_date($student_birth))) {
                $error_msg = 'Birth date must be in YYYY-MM-DD format.';
            } elseif ($error_msg === '' && ($student_age !== null && ($student_age < 0 || $student_age > 200))) {
                $error_msg = 'Please enter a valid age.';
            }

            $parent_name = clean_text($_POST['parent_name'] ?? '');
            $parent_relation = clean_text($_POST['parent_relation'] ?? '');
            $parent_contact = clean_text($_POST['parent_contact'] ?? '');
            $parent_consent = isset($_POST['parent_consent']) && $_POST['parent_consent'] === '1' ? true : false;
            $parent_lives_with = isset($_POST['parent_lives_with']) && $_POST['parent_lives_with'] === '1' ? true : false;

            $father_name = clean_text($_POST['father_name'] ?? '');
            $mother_maiden_name = clean_text($_POST['mother_maiden_name'] ?? '');
            $legal_guardian_name = clean_text($_POST['legal_guardian_name'] ?? '');
            $guardian_contact = clean_text($_POST['guardian_contact'] ?? '');

            $parent_info = [
                'name' => $parent_name,
                'relation' => $parent_relation,
                'contact' => $parent_contact,
                'consent' => $parent_consent,
                'lives_with' => $parent_lives_with,
                'father_name' => $father_name ?: null,
                'mother_maiden_name' => $mother_maiden_name ?: null,
                'legal_guardian_name' => $legal_guardian_name ?: null,
                'guardian_contact' => $guardian_contact ?: null,
            ];

            $student_info = [
                'first_name' => $student_first,
                'middle_name' => $student_middle ?: null,
                'last_name' => $student_last,
                'birth_date' => $student_birth ?: null,
                'birthplace' => $student_birthplace ?: null,
                'year_level' => $student_year_level ?: null,
                'gender' => $student_gender ?: null,
                'religion' => $student_religion ?: null,
                'age' => $student_age !== null ? (int)$student_age : null,
                'address' => [
                    'house_no' => $addr_house,
                    'street' => $addr_street,
                    'barangay' => $addr_barangay,
                    'city' => $addr_city,
                    'province' => $addr_province,
                    'country' => $addr_country,
                    'zip' => $addr_zip,
                ],
                'contact' => $student_contact,
                'email' => $student_email,
                'indigenous' => [
                    'belongs' => $indigenous_belongs,
                    'specify' => $indigenous_spec,
                ],
            ];

            $uploadedFiles = [];


            // If no error so far, persist application and optionally create payment
            if ($error_msg === '') {
                try {
                    $pdo->beginTransaction();
                    $sql = "INSERT INTO enrollment_applications
                        (user_id, course_ids, year_level, notes, files, parent_info, student_info, status, submitted_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'submitted', NOW())";
                    $stmt = $pdo->prepare($sql);
                    $course_json = json_encode(array_values($course_ids));
                    $files_json = json_encode($uploadedFiles);
                    $parent_json = json_encode($parent_info);
                    $student_json = json_encode($student_info);
                    $stmt->execute([$user_id, $course_json, $student_year_level, $notes, $files_json, $parent_json, $student_json]);

                    // get application id
                    $appId = (int)$pdo->lastInsertId();

                    // Determine payment amount:
                    // - if student supplied a manual amount use that
                    // - otherwise compute based on credits * FEE_PER_CREDIT
                    $fee_amount = null;
                    if ($manual_amount !== null) {
                        $fee_amount = $manual_amount;
                    } else {
                        // compute from course credits
                        $fee_amount = 0.00;
                        if (!empty($course_ids) && is_array($course_ids)) {
                            $cid = (int)$course_ids[0];
                            $stmtC = $pdo->prepare("SELECT credits FROM courses WHERE id = ? LIMIT 1");
                            $stmtC->execute([$cid]);
                            $rowC = $stmtC->fetch(PDO::FETCH_ASSOC);
                            $credits = $rowC ? (int)$rowC['credits'] : 0;
                            $fee_amount = round($credits * FEE_PER_CREDIT, 2);
                        }
                    }

                    // create payment record if fee > 0
                    if (is_numeric($fee_amount) && $fee_amount > 0) {
                        try {
                            // Try to insert with application_id (preferred)
                            $pstmt = $pdo->prepare("INSERT INTO payments (application_id, user_id, amount, payment_date, payment_status) VALUES (?, ?, ?, NOW(), 'pending')");
                            $pstmt->execute([$appId, $user_id, $fee_amount]);
                        } catch (PDOException $payEx) {
                            // If payments.application_id doesn't exist or other schema mismatch, try fallback without application_id
                            error_log('payments insert with application_id failed: ' . $payEx->getMessage());
                            try {
                                $pstmt2 = $pdo->prepare("INSERT INTO payments (user_id, amount, payment_date, payment_status) VALUES (?, ?, NOW(), 'pending')");
                                $pstmt2->execute([$user_id, $fee_amount]);
                            } catch (PDOException $payEx2) {
                                // log and continue (do NOT break the user submission for a payments schema problem)
                                error_log('payments insert fallback failed: ' . $payEx2->getMessage());
                                $info_msg = 'Application saved but payment creation failed; contact support.';
                                set_flash($info_msg);
                            }
                        }
                    }

                    $pdo->commit();

                    // --- ADDED NOTIFICATION LOGIC START ---
                    try {
                        // Ensure admin_notifications table exists
                        $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_notifications` (
                          `id` INT PRIMARY KEY AUTO_INCREMENT,
                          `message` VARCHAR(255) NOT NULL,
                          `link` VARCHAR(255) DEFAULT NULL, 
                          `is_read` TINYINT(1) DEFAULT 0,
                          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )");
                        
                        $notifMsg = "New enrollment application from " . $student_first . " " . $student_last;
                        $notifLink = "manage_applications.php?id=" . $appId;
                        
                        error_log("About to insert notification: Message='$notifMsg', Link='$notifLink'");
                        
                        $notifStmt = $pdo->prepare("INSERT INTO admin_notifications (message, link) VALUES (?, ?)");
                        $result = $notifStmt->execute([$notifMsg, $notifLink]);
                        
                        error_log("Notification insert result: " . ($result ? 'SUCCESS' : 'FAILED'));
                        error_log("Last insert ID: " . $pdo->lastInsertId());
                        
                        // Verify it was inserted
                        $verify = $pdo->query("SELECT COUNT(*) FROM admin_notifications")->fetchColumn();
                        error_log("Total notifications after insert: " . $verify);
                    } catch (Exception $e) {
                        error_log("Notification Error: " . $e->getMessage());
                        error_log("Stack trace: " . $e->getTraceAsString());
                    }
                    // --- ADDED NOTIFICATION LOGIC END ---

                    set_flash('Application submitted successfully. Your application status is "submitted". A payment was created if fees are due.');

                    // rotate CSRF & redirect to payment section so student can pay/view
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
                    header('Location: enroll.php#applications');
                    exit;
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    // remove files we moved
                    foreach ($uploadedFiles as $f) {
                        $p = __DIR__ . '/../' . ltrim($f['stored_name'], '/');
                        if (file_exists($p)) @unlink($p);
                    }
                    error_log('Enrollment application or payment insert error: ' . $e->getMessage());
                    $error_msg = 'Failed to submit application. Error: ' . $e->getMessage();
                }
            }
        }
    }
}

// Fetch available courses
try {
    $stmt = $pdo->query("SELECT id, course_code, course_name, credits FROM courses ORDER BY course_name ASC");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $courses = [];
    error_log('Fetch courses error: ' . $e->getMessage());
    $error_msg = $error_msg ?: 'Could not load courses.';
}

// Fetch user's previous applications (include parent_info and student_info)
try {
    $stmt = $pdo->prepare("SELECT id, course_ids, year_level, notes, files, parent_info, student_info, status, submitted_at, processed_at, processed_by FROM enrollment_applications WHERE user_id = ? ORDER BY submitted_at DESC");
    $stmt->execute([$user_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $applications = [];
    error_log('Fetch applications error: ' . $e->getMessage());
}

// Fetch user's payments for the payments form/listing
$payments = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY payment_date DESC, id DESC");
    $stmt->execute([$user_id]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $payments = [];
    error_log('Fetch payments error: ' . $e->getMessage());
}

// Helper
function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Enrollment Application</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      function confirmApplication() {
        // Check that a single course radio is selected AND required name/email are filled.
        const selectedCourse = document.querySelector('input[name="course_id"]:checked');
        if (!selectedCourse) {
          alert('Please select one course to apply for.');
          return false;
        }
        // basic check for required student name/email fields
        const first = document.querySelector('input[name="student_first_name"]').value.trim();
        const last = document.querySelector('input[name="student_last_name"]').value.trim();
        const email = document.querySelector('input[name="student_email"]').value.trim();
        if (!first || !last) {
          alert('Please provide student first and last name.');
          return false;
        }
        if (!email) {
          alert('Please provide student email address.');
          return false;
        }
        // amount validation client-side
        const amt = document.querySelector('input[name="amount"]').value.trim();
        if (amt === '') {
            alert('Please enter a down payment amount.');
            return false;
        }
        const normalized = amt.replace(/,/g, '');
        if (isNaN(normalized) || Number(normalized) < 1000) {
            alert('Please enter a valid amount of at least 1000.');
            return false;
        }
        return confirm('Submit enrollment application? You will be able to view status on this page.');
      }

      function validatePaymentForm() {
        const amt = document.querySelector('input[name="pay_amount"]').value.trim();
        if (amt === '' || isNaN(amt.replace(/,/g,'')) || Number(amt.replace(/,/g,'')) <= 0) {
          alert('Please enter a valid amount greater than zero.');
          return false;
        }
        return confirm('Record this payment? Admin will verify.');
      }

      // Compute age client-side when birth date changes
      function updateAgeFromDob() {
        const dob = document.querySelector('input[name="student_birth_date"]').value;
        const ageField = document.querySelector('input[name="student_age"]');
        if (!dob) { return; }
        const birth = new Date(dob);
        const now = new Date();
        let age = now.getFullYear() - birth.getFullYear();
        const m = now.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < birth.getDate())) age--;
        if (!isNaN(age) && age >= 0) ageField.value = age;
      }

      // Toggle indigenous specify box
      function toggleIndigenousSpecify() {
        const yes = document.querySelector('input[name="indigenous_belongs"][value="yes"]').checked;
        document.getElementById('indigenous_spec_div').style.display = yes ? 'block' : 'none';
      }

      function updateYearLevelOptions() {
        const selectedCourse = document.querySelector('input[name="course_id"]:checked');
        const yearSelect = document.getElementById('student_year_level');
        if (!selectedCourse || !yearSelect) return;

        const courseCode = selectedCourse.getAttribute('data-code');
        // Courses that only have up to 3rd Year
        const restrictedCourses = ['ACT', 'DIT', 'DHRT'];
        
        // Reset all options first (show everything)
        for (let i = 0; i < yearSelect.options.length; i++) {
            yearSelect.options[i].style.display = '';
            yearSelect.options[i].disabled = false;
        }

        if (restrictedCourses.includes(courseCode)) {
            // Hide 4th Year
            for (let i = 0; i < yearSelect.options.length; i++) {
                if (yearSelect.options[i].value === '4th Year') {
                    yearSelect.options[i].style.display = 'none';
                    yearSelect.options[i].disabled = true;
                    if (yearSelect.value === '4th Year') {
                        yearSelect.value = ''; // Reset selection if it was 4th Year
                    }
                }
            }
        }
      }

      // Call on load to set initial state
      window.addEventListener('DOMContentLoaded', updateYearLevelOptions);
    </script>
    <style>
    body::-webkit-scrollbar{
      display:none;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-6">
        <div>
          <h1 class="text-lg font-semibold text-gray-900">Enrollment Application</h1>
          <p class="text-sm text-gray-500">Submit an application, upload documents and record payments. Provide student and parent/guardian details below.</p>
        </div>
        <div class="space-x-3">
          <a href="dashboard.php" class="text-sm text-gray-600 hover:underline font-medium">←Back to Dashboard</a>
          <a href="logout.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Logout</a>
        </div>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php if ($info_msg): ?>
      <div class="mb-4 rounded-md bg-green-50 border border-green-100 p-4">
        <p class="text-sm text-green-800"><?= h($info_msg) ?></p>
      </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
      <div class="mb-4 rounded-md bg-red-50 border border-red-100 p-4">
        <p class="text-sm text-red-800"><?= h($error_msg) ?></p>
      </div>
    <?php endif; ?>

    <?php if ($has_approved_application): ?>
        <div class="bg-green-50 border border-green-200 rounded-lg p-8 mb-8 text-center shadow-sm">
            <svg class="mx-auto h-16 w-16 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-green-900 mb-2">Application Approved</h3>
            <p class="text-green-700 max-w-md mx-auto">
                Congratulations! Your enrollment application has been approved. You are already enrolled and cannot submit a new application at this time.
            </p>
            <div class="mt-6">
                <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Return to Dashboard
                </a>
            </div>
        </div>
    <?php else: ?>
    <form method="post" enctype="multipart/form-data" onsubmit="return confirmApplication();">
        <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
        <input type="hidden" name="action" value="submit_application">

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Course Selection</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select a course to apply for</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-auto border rounded p-2">
                    <?php foreach ($courses as $c): ?>
                    <label class="inline-flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                        <input type="radio" name="course_id" value="<?= (int)$c['id'] ?>" data-code="<?= h($c['course_code']) ?>" onclick="updateYearLevelOptions()" class="h-4 w-4 text-sky-600 border-gray-300 rounded"
                        <?= ((int)($_POST['course_id'] ?? 0) === (int)$c['id']) ? 'checked' : '' ?> aria-label="<?= h($c['course_code'] . ' - ' . $c['course_name']) ?>">
                        <span class="text-sm">
                        <strong><?= h($c['course_code']) ?></strong> — <?= h($c['course_name']) ?> <span class="text-xs text-gray-500">(<?= (int)$c['credits'] ?> cr)</span>
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs text-gray-500 mt-1">You may apply for only one course per application. To apply for additional courses submit separate applications.</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                <select name="student_year_level" id="student_year_level" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" required>
                    <option value="" hidden>Select Year Level</option>
                    <option value="1st Year" <?= (($_POST['student_year_level'] ?? '') === '1st Year') ? 'selected' : '' ?>>1st Year</option>
                    <option value="2nd Year" <?= (($_POST['student_year_level'] ?? '') === '2nd Year') ? 'selected' : '' ?>>2nd Year</option>
                    <option value="3rd Year" <?= (($_POST['student_year_level'] ?? '') === '3rd Year') ? 'selected' : '' ?>>3rd Year</option>
                    <option value="4th Year" <?= (($_POST['student_year_level'] ?? '') === '4th Year') ? 'selected' : '' ?>>4th Year</option>
                </select>
            </div>

        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Student Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="student_first_name" value="<?= h($_POST['student_first_name'] ?? $current_user['first_name']) ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" required placeholder="Your First Name" readonly/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                    <input type="text" name="student_middle_name" value="<?= h($_POST['student_middle_name'] ?? $current_user['middle_name']) ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Middle Name (optional)" readonly/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="student_last_name" value="<?= h($_POST['student_last_name'] ?? $current_user['last_name']) ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" required placeholder="Your Last Name" readonly/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Birth Date</label>
                    <input onchange="updateAgeFromDob()" type="date" name="student_birth_date" value="<?= h($_POST['student_birth_date'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" min="0" name="student_age" value="<?= h($_POST['student_age'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Age (years)" />
                </div>



                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <div class="mt-1 flex items-center space-x-4">
                    <label class="inline-flex items-center"><input type="radio" name="student_gender" value="male" <?= (($_POST['student_gender'] ?? '') === 'male') ? 'checked' : '' ?> class="h-4 w-4" /> <span class="ml-2 text-sm">Male</span></label>
                    <label class="inline-flex items-center"><input type="radio" name="student_gender" value="female" <?= (($_POST['student_gender'] ?? '') === 'female') ? 'checked' : '' ?> class="h-4 w-4" /> <span class="ml-2 text-sm">Female</span></label>
                    <label class="inline-flex items-center"><input type="radio" name="student_gender" value="other" <?= (($_POST['student_gender'] ?? '') === 'other') ? 'checked' : '' ?> class="h-4 w-4" /> <span class="ml-2 text-sm">Other</span></label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Religion</label>
                    <input type="text" name="student_religion" value="<?= h($_POST['student_religion'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="e.g. Christianity, Islam, Indigenous" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Birthplace</label>
                    <input type="text" name="student_birthplace" value="<?= h($_POST['student_birthplace'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Place of birth (city/province/country)" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" name="student_contact" value="<?= h($_POST['student_contact'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="(000) 000-0000" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="student_email" value="<?= h($_POST['student_email'] ?? $current_user['email']) ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" required placeholder="name@example.com"  readonly/>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Belonging to any Indigenous Peoples (IP) Community / Indigenous Cultural Community?</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center"><input type="radio" name="indigenous_belongs" value="yes" onclick="toggleIndigenousSpecify()" <?= (($_POST['indigenous_belongs'] ?? '') === 'yes') ? 'checked' : '' ?> /> <span class="ml-2 text-sm">Yes</span></label>
                    <label class="inline-flex items-center"><input type="radio" name="indigenous_belongs" value="no" onclick="toggleIndigenousSpecify()" <?= (($_POST['indigenous_belongs'] ?? '') === 'no') ? 'checked' : '' ?> /> <span class="ml-2 text-sm">No</span></label>
                    <div id="indigenous_spec_div" class="ml-4" style="display:<?= (($_POST['indigenous_belongs'] ?? '') === 'yes') ? 'block' : 'none' ?>;">
                    <input type="text" name="indigenous_spec" value="<?= h($_POST['indigenous_spec'] ?? '') ?>" placeholder="If Yes, please specify" class="block w-80 rounded border-gray-300 px-3 py-2 text-sm" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Student Address Information</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Student Address</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="text" name="student_addr_house" value="<?= h($_POST['student_addr_house'] ?? '') ?>" placeholder="House No." class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                    <input type="text" name="student_addr_street" value="<?= h($_POST['student_addr_street'] ?? '') ?>" placeholder="Sitio / Street" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                    <input type="text" name="student_addr_barangay" value="<?= h($_POST['student_addr_barangay'] ?? '') ?>" placeholder="Barangay" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                    <input type="text" name="student_addr_city" value="<?= h($_POST['student_addr_city'] ?? '') ?>" placeholder="Municipality / City" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                    <input type="text" name="student_addr_province" value="<?= h($_POST['student_addr_province'] ?? '') ?>" placeholder="Province" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                    <input type="text" name="student_addr_country" value="<?= h($_POST['student_addr_country'] ?? '') ?>" placeholder="Country" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                    <input type="text" name="student_addr_zip" value="<?= h($_POST['student_addr_zip'] ?? '') ?>" placeholder="ZIP / Postal Code" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" />
                </div>
                <p class="text-xs text-gray-500 mt-1">Fill out your current address. Use the fields provided above (House No., Sitio/Street, Barangay, Municipality/City, Province).</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Parent / Guardian Details</h2>
            <div class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Father's Name</label>
                    <input type="text" name="father_name" value="<?= h($_POST['father_name'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Father's Full Name (Last, Given, Middle)" />
                    </div>
                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Mother's Maiden Name</label>
                    <input type="text" name="mother_maiden_name" value="<?= h($_POST['mother_maiden_name'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Mother's Maiden Name (Last, Given, Middle)" />
                    </div>
                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Legal Guardian's Name</label>
                    <input type="text" name="legal_guardian_name" value="<?= h($_POST['legal_guardian_name'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Legal Guardian (if applicable)" />
                    </div>
                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contact Number</label>
                    <input type="text" name="guardian_contact" value="<?= h($_POST['guardian_contact'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Contact Number for Parent/Guardian" />
                    </div>

                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Primary Parent / Guardian Name</label>
                    <input type="text" name="parent_name" value="<?= h($_POST['parent_name'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="Name (Primary Contact)" />
                    </div>
                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Relation to Student</label>
                    <input type="text" name="parent_relation" value="<?= h($_POST['parent_relation'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="e.g. Mother, Father, Guardian" />
                    </div>
                    <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Primary Parent Contact</label>
                    <input type="text" name="parent_contact" value="<?= h($_POST['parent_contact'] ?? '') ?>" class="mt-1 block w-full rounded border-gray-300 px-3 py-2 text-sm" placeholder="(000) 000-0000" />
                    </div>
                    <div class="flex items-center space-x-3">
                    <label class="inline-flex items-center"><input type="checkbox" name="parent_consent" value="1" <?= (isset($_POST['parent_consent']) ? 'checked' : '') ?> /> <span class="ml-2 text-sm">Consent given</span></label>
                    <label class="inline-flex items-center"><input type="checkbox" name="parent_lives_with" value="1" <?= (isset($_POST['parent_lives_with']) ? 'checked' : '') ?> /> <span class="ml-2 text-sm">Lives with student</span></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Payment</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <div class="border rounded bg-white p-4">
                    <input
                    type="text"
                    name="amount"
                    id="amount"
                    placeholder="e.g. 1500.00"
                    value="<?= isset($_POST['amount']) ? h($_POST['amount']) : '' ?>"
                    class="block w-full text-gray-700 placeholder-gray-400 border-0 focus:outline-none focus:ring-0 text-sm"
                    aria-label="Amount"
                    required
                    />
                    <p class="mt-2 text-xs text-gray-400">Enter the amount for down payment (minimum ₱1000). Admin will verify and mark completed.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-start space-x-3">
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700">Submit Application</button>
          <a href="dashboard.php" class="text-sm text-gray-600 hover:underline">Cancel</a>
        </div>
      </form>
    <?php endif; ?>

    <section id="applications" class="bg-white shadow rounded-lg p-6 mt-7">
      <h3 class="text-lg font-medium text-gray-900 mb-3">Your Applications</h3>

      <?php if (empty($applications)): ?>
        <div class="text-sm text-gray-600">You have not submitted any enrollment applications yet.</div>
      <?php else: ?>
        <div class="space-y-4">
          <?php foreach ($applications as $app): ?>
            <?php
              $files = json_decode($app['files'] ?: '[]', true);
              $courses_applied = json_decode($app['course_ids'] ?: '[]', true);
              $parent_info = json_decode($app['parent_info'] ?: 'null', true);
              $student_info = json_decode($app['student_info'] ?: 'null', true);
            ?>
            <div class="border rounded p-4">
              <div class="flex items-start justify-between">
                <div>
                  <div class="text-sm text-gray-900 font-medium">Application #<?= (int)$app['id'] ?></div>
                  <div class="text-xs text-gray-500">Submitted: <?= h($app['submitted_at']) ?></div>
                </div>
                <div class="text-sm">
                  <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium <?= $app['status'] === 'approved' ? 'bg-green-100 text-green-800' : ($app['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                    <?= h(ucfirst($app['status'])) ?>
                  </span>
                </div>
              </div>

              <div class="mt-3 text-sm text-gray-700">
                <div><strong>Student:</strong>
                  <?php if (!empty($student_info) && is_array($student_info)): ?>
                    <?= h($student_info['first_name'] ?? ''); ?> <?= h($student_info['middle_name'] ?? '') ?> <?= h($student_info['last_name'] ?? ''); ?> — <?= h($student_info['email'] ?? '') ?>
                    <div class="text-xs text-gray-500">
                        DOB: <?= h($student_info['birth_date'] ?? '—') ?> · 
                        Birthplace: <?= h($student_info['birthplace'] ?? '—') ?> · 
                        Gender: <?= h($student_info['gender'] ?? '—') ?> · 
                        Age: <?= h($student_info['age'] ?? '—') ?> · 
                        Year Level: <?= h($student_info['year_level'] ?? '—') ?>
                    </div>
                    <?php
                      $a = $student_info['address'] ?? null;
                      if (!empty($a) && is_array($a)):
                    ?>
                      <div class="text-xs text-gray-500 mt-1">
                        <?= h($a['house_no'] ?? '') ?> <?= h($a['street'] ?? '') ?> <?= h($a['barangay'] ?? '') ?>,
                        <?= h($a['city'] ?? '') ?>, <?= h($a['province'] ?? '') ?> <?= h($a['country'] ?? '') ?> <?= h($a['zip'] ?? '') ?>
                      </div>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-gray-500">Not provided</span>
                  <?php endif; ?>
                </div>

                <div class="mt-2"><strong>Courses:</strong>
                  <?php if (empty($courses_applied)): ?>
                    <span class="text-gray-500">None</span>
                  <?php else: ?>
                    <?php
                      $placeholders = implode(',', array_fill(0, count($courses_applied), '?'));
                      $stmt2 = $pdo->prepare("SELECT course_code, course_name FROM courses WHERE id IN ($placeholders)");
                      $stmt2->execute($courses_applied);
                      $crs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                      $labels = array_map(fn($r)=> h($r['course_code']) . ' — ' . h($r['course_name']), $crs);
                    ?>
                    <?= implode(', ', $labels) ?>
                  <?php endif; ?>
                </div>

                <div class="mt-2"><strong>Year Level:</strong> <?= h($app['year_level'] ?? $student_info['year_level'] ?? '—') ?></div>

                <?php if (!empty($app['notes'])): ?>
                  <div class="mt-2"><strong>Notes:</strong> <?= h($app['notes']) ?></div>
                <?php endif; ?>

                <?php if (!empty($parent_info) && is_array($parent_info)): ?>
                  <div class="mt-2">
                    <strong>Parent / Guardian:</strong>
                    <div class="text-sm text-gray-700">
                      <?php if (!empty($parent_info['father_name'])): ?><div><strong>Father:</strong> <?= h($parent_info['father_name']) ?></div><?php endif; ?>
                      <?php if (!empty($parent_info['mother_maiden_name'])): ?><div><strong>Mother (maiden):</strong> <?= h($parent_info['mother_maiden_name']) ?></div><?php endif; ?>
                      <?php if (!empty($parent_info['legal_guardian_name'])): ?><div><strong>Legal Guardian:</strong> <?= h($parent_info['legal_guardian_name']) ?></div><?php endif; ?>

                      <?php if (!empty($parent_info['name'])): ?>
                        <div class="mt-1"><?= h($parent_info['name']) ?> <?php if (!empty($parent_info['relation'])): ?>(<?= h($parent_info['relation']) ?>)<?php endif; ?></div>
                      <?php endif; ?>

                      <?php if (!empty($parent_info['guardian_contact'])): ?>
                        <div class="text-xs text-gray-500">Contact: <?= h($parent_info['guardian_contact']) ?></div>
                      <?php elseif (!empty($parent_info['contact'])): ?>
                        <div class="text-xs text-gray-500">Contact: <?= h($parent_info['contact']) ?></div>
                      <?php endif; ?>   

                      <div class="text-xs text-gray-500 mt-1">
                        Consent: <?= (!empty($parent_info['consent']) ? 'Yes' : 'No') ?> · Lives with student: <?= (!empty($parent_info['lives_with']) ? 'Yes' : 'No') ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($files) && is_array($files)): ?>
                  <div class="mt-2">
                    <strong>Requirements Submitted:</strong>
                    <ul class="list-disc list-inside text-sm text-gray-600 mt-1">
                      <?php foreach ($files as $f): ?>
                        <li>
                          <span class="font-medium"><?= h($f['type'] ?? 'Document') ?>:</span>
                          <a href="<?= h($baseUrl . '/' . $f['stored_name']) ?>" target="_blank" class="text-sky-600 hover:underline"><?= h($f['original_name']) ?></a>
                          <span class="text-xs text-gray-400">(<?= round(($f['size'] ?? 0) / 1024) ?> KB)</span>
                            <?php
                                $isImage = false;
                                if (isset($f['mime']) && strpos($f['mime'], 'image/') === 0) {
                                    $isImage = true;
                                } elseif (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f['original_name'] ?? '')) {
                                    $isImage = true;
                                }
                            ?>
                            <?php if ($isImage): ?>
                                <div class="mt-1 mb-2">
                                    <img src="<?= h($baseUrl . '/' . $f['stored_name']) ?>" alt="Preview" class="max-w-[200px] rounded border shadow-sm">
                                </div>
                            <?php endif; ?>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>
              </div>

              <?php if (!empty($app['processed_at'])): ?>
                <div class="mt-3 text-xs text-gray-500">Processed at: <?= h($app['processed_at']) ?> by <?= h($app['processed_by'] ?? 'system') ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="bg-white border-t mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center lg:px-8 py-6 text-sm text-gray-500">
      © <?= date('Y') ?> Your Institution
    </div>
  </footer>
</body>
</html>