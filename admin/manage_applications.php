<?php
// public/manage_applications.php
// Admin UI to review enrollment applications and Approve / Reject them.
// Updated: added Delete button to each application card in the list view.
// Requires ../includes/db_connect.php and ../includes/auth.php
// Place this file in public/ and link from your admin dashboard.

require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

// Ensure session
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}
$csrf_token = $_SESSION['csrf_token'];

// Flash helpers
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

// Calculate base URL for file links
// Assuming the project is accessed via http://localhost/ProjectName/admin/...
// We want /ProjectName
$projectDir = basename(dirname(__DIR__));
$baseUrl = '/' . $projectDir;

// Helper
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

// Small helper to map status to label + classes
function status_badge($status) {
    $s = strtolower((string)$status);
    return match($s) {
        'submitted' => ['Submitted', 'bg-yellow-100 text-yellow-800'],
        'approved'  => ['Approved',  'bg-green-100 text-green-800'],
        'rejected'  => ['Rejected',  'bg-red-100 text-red-800'],
        default     => [ucfirst($s), 'bg-gray-100 text-gray-800'],
    };
}

// ---------- Handle POST actions: approve / reject / delete ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['application_id'])) {
    if (empty($_POST['csrf_token']) || !hash_equals($csrf_token, $_POST['csrf_token'])) {
        $error_msg = 'Invalid CSRF token.';
    } else {
        $action = $_POST['action'];
        $appId = (int)$_POST['application_id'];

        try {
            // load application under transaction
            $stmt = $pdo->prepare("SELECT * FROM enrollment_applications WHERE id = ? LIMIT 1 FOR UPDATE");
            $pdo->beginTransaction();
            $stmt->execute([$appId]);
            $app = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$app) {
                throw new Exception('Application not found.');
            }

            $now = date('Y-m-d H:i:s');
            $adminId = $_SESSION['user_id'] ?? null;

            if ($action === 'approve') {
                if ($app['status'] !== 'submitted') {
                    throw new Exception('Only submitted applications can be approved.');
                }

                // Approve: create enrollment rows for each course and mark application approved
                $courseIds = json_decode($app['course_ids'] ?? '[]', true);
                if (!is_array($courseIds)) $courseIds = [];

                // Prepare statements
                $enrollStmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id, enrolled_at) VALUES (?, ?, ?)");
                $check = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ? LIMIT 1");
                $stmtC = $pdo->prepare("SELECT id FROM courses WHERE id = ? LIMIT 1");

                foreach ($courseIds as $cid) {
                    $cid = (int)$cid;
                    // verify course exists (defensive)
                    $stmtC->execute([$cid]);
                    if (!$stmtC->fetch()) continue;
                    $check->execute([$app['user_id'], $cid]);
                    if ($check->fetch()) continue;
                    $enrollStmt->execute([$app['user_id'], $cid, $now]);
                }

                // update application
                $u = $pdo->prepare("UPDATE enrollment_applications SET status = 'approved', processed_at = ?, processed_by = ? WHERE id = ?");
                $u->execute([$now, $adminId, $appId]);

                // Optional: add audit log
                try {
                    $log = $pdo->prepare("INSERT INTO admin_audit_log (admin_id, action, target_table, target_id, details) VALUES (?, 'approve_application', 'enrollment_applications', ?, ?)");
                    $log->execute([$adminId, $appId, json_encode(['approved_at' => $now])]);
                } catch (Throwable $ignore) {
                    // don't break the flow if audit log table is missing
                }

                $pdo->commit();
                set_flash('Application approved and student enrolled.');
            }

            elseif ($action === 'reject') {
                // IMPORTANT: Reject should only change status and not delete the row.
                if ($app['status'] !== 'submitted') {
                    throw new Exception('Only submitted applications can be rejected.');
                }
                $reason = trim((string)($_POST['reject_reason'] ?? ''));

                // Update status to rejected and record processed info
                $u = $pdo->prepare("UPDATE enrollment_applications SET status = 'rejected', processed_at = ?, processed_by = ? WHERE id = ?");
                $u->execute([$now, $adminId, $appId]);

                // Optional: add audit log with reason
                try {
                    $log = $pdo->prepare("INSERT INTO admin_audit_log (admin_id, action, target_table, target_id, details) VALUES (?, 'reject_application', 'enrollment_applications', ?, ?)");
                    $log->execute([$adminId, $appId, json_encode(['rejected_at' => $now, 'reason' => $reason])]);
                } catch (Throwable $ignore) {}

                $pdo->commit();
                set_flash('Application rejected.');
            }

            elseif ($action === 'delete') {
                // Require explicit confirmation flag for delete to avoid accidental deletion
                if (empty($_POST['confirm_delete']) || (string)$_POST['confirm_delete'] !== '1') {
                    $pdo->rollBack();
                    throw new Exception('Delete not confirmed.');
                }

                // Allow deletion only for non-approved applications (to avoid removing enrollments created by approval).
                if (($app['status'] ?? '') === 'approved') {
                    throw new Exception('Cannot delete an approved application. Remove enrollments first, then delete the application.');
                }

                // Null out payments.application_id that reference this application (defensive)
                try {
                    $pdo->prepare("UPDATE payments SET application_id = NULL WHERE application_id = ?")->execute([$appId]);
                } catch (Throwable $ignore) {
                    // continue even if payments update fails
                }

                // Remove uploaded files referenced in files JSON (best-effort)
                $files = json_decode($app['files'] ?? '[]', true) ?: [];
                $deletedFiles = [];
                foreach ($files as $f) {
                    $stored = $f['stored_name'] ?? '';
                    if ($stored) {
                        // Stored paths in code are relative like "uploads/..." — map to filesystem path
                        $candidate = __DIR__ . '/../' . ltrim($stored, '/');
                        if (file_exists($candidate) && is_file($candidate)) {
                            @unlink($candidate);
                            $deletedFiles[] = $candidate;
                        }
                    }
                }

                // Finally delete application row
                $del = $pdo->prepare("DELETE FROM enrollment_applications WHERE id = ?");
                $del->execute([$appId]);

                // Delete associated notification when application is deleted
                try {
                    $notifDel = $pdo->prepare("DELETE FROM admin_notifications WHERE link LIKE ?");
                    $notifDel->execute(['manage_applications.php?id=' . $appId]);
                    error_log("Notification deleted for application ID: " . $appId);
                } catch (Throwable $notifErr) {
                    error_log("Notification deletion error: " . $notifErr->getMessage());
                }

                // Optional: audit log
                try {
                    $log = $pdo->prepare("INSERT INTO admin_audit_log (admin_id, action, target_table, target_id, details) VALUES (?, 'delete_application', 'enrollment_applications', ?, ?)");
                    $log->execute([$adminId, $appId, json_encode(['deleted_at' => $now, 'deleted_files' => $deletedFiles])]);
                } catch (Throwable $ignore) {}

                $pdo->commit();
                set_flash('Application deleted.');
            }

            else {
                $pdo->rollBack();
                $error_msg = 'Unknown action.';
            }

            header('Location: manage_applications.php');
            exit;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error_msg = 'Action failed: ' . $e->getMessage();
        }
    }
}

// If view_id is provided, load that single application for the detail panel
$view_app = null;
$view_id = isset($_GET['view_id']) ? (int)$_GET['view_id'] : 0;
if ($view_id > 0) {
    try {
        $stmtV = $pdo->prepare("SELECT ea.*, u.username, u.first_name, u.middle_name, u.last_name, u.email FROM enrollment_applications ea LEFT JOIN users u ON ea.user_id = u.id WHERE ea.id = ? LIMIT 1");
        $stmtV->execute([$view_id]);
        $view_app = $stmtV->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($view_app) {
            // decode json fields for convenience in the template
            $view_app['student_info'] = json_decode($view_app['student_info'] ?? '{}', true) ?: [];
            $view_app['files'] = json_decode($view_app['files'] ?? '[]', true) ?: [];
            $view_app['course_ids_arr'] = json_decode($view_app['course_ids'] ?? '[]', true) ?: [];

            // fetch processed_by name if present
            $view_app['processed_by_name'] = null;
            if (!empty($view_app['processed_by'])) {
                $stmtPB = $pdo->prepare("SELECT username, first_name, middle_name, last_name FROM users WHERE id = ? LIMIT 1");
                $stmtPB->execute([(int)$view_app['processed_by']]);
                $pb = $stmtPB->fetch(PDO::FETCH_ASSOC);
                if ($pb) {
                    $pbName = trim($pb['first_name'] . ' ' . ($pb['middle_name'] ? $pb['middle_name'] . ' ' : '') . $pb['last_name']);
                    $view_app['processed_by_name'] = $pbName ?: $pb['username'];
                }
            }
        }
    } catch (Exception $e) {
        $view_app = null;
        error_log('Fetch view application error: ' . $e->getMessage());
        $error_msg = $error_msg ?: 'Could not load application details.';
    }
}

// Support optional filtering of applications by user_id (shows all applications submitted by a student)
$filter_user = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Fetch applications (filter by user if requested). Show all statuses when filtered by user.
try {
    if ($filter_user > 0) {
        $stmt = $pdo->prepare("SELECT ea.id, ea.user_id, ea.submitted_at, ea.status, ea.processed_at, ea.processed_by, ea.student_info, ea.parent_info, ea.files, ea.course_ids, ea.year_level, ea.notes, u.username, u.first_name, u.middle_name, u.last_name
                               FROM enrollment_applications ea
                               LEFT JOIN users u ON ea.user_id = u.id
                               WHERE ea.user_id = ?
                               ORDER BY ea.submitted_at DESC");
        $stmt->execute([$filter_user]);
    } else {
        // Default: show submitted applications first
        $stmt = $pdo->prepare("SELECT ea.id, ea.user_id, ea.submitted_at, ea.status, ea.processed_at, ea.processed_by, ea.student_info, ea.parent_info, ea.files, ea.course_ids, ea.year_level, ea.notes, u.username, u.first_name, u.middle_name, u.last_name
                               FROM enrollment_applications ea
                               LEFT JOIN users u ON ea.user_id = u.id
                               ORDER BY ea.submitted_at DESC");
        $stmt->execute();
    }
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $applications = [];
    $error_msg = $error_msg ?: 'Could not load applications.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Manage Applications</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function confirmApprove() {
      return confirm('Approve this application and enroll the student in the selected course(s)?');
    }
    function confirmReject() {
      return confirm('Reject this application?');
    }
    function confirmDelete() {
      return confirm('Delete this application? This cannot be undone.');
    }
  </script>
  <style>
    body::-webkit-scrollbar{
      display:none;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
<nav class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center space-x-4">
          <a href="dashboard.php" class="text-lg font-semibold text-gray-900">Admin Panel</a>
          <a href="dashboard.php" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
          <a href="manage_courses.php" class="text-sm text-brand-600">Courses</a>
          <a href="manage_students.php" class="text-sm text-gray-600 hover:text-gray-900">Students</a>
          <a href="manage_payments.php" class="text-sm text-gray-600 hover:text-gray-900">Payments</a>
          <a href="manage_applications.php" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Application</a>
        </div>
        <div class="flex items-center space-x-3">
          <a href="../public/logout.php" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700">Logout</a>
        </div>
      </div>
    </div>
  </nav>
  <main class="max-w-6xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold">Enrollment Applications</h1>
        <?php if ($filter_user > 0): ?>
          <?php
            // show student's name if available (take from first application if present)
            $studentName = '';
            foreach ($applications as $a) {
                $name = trim(($a['first_name'] ?? '') . ' ' . ($a['middle_name'] ? $a['middle_name'] . ' ' : '') . ($a['last_name'] ?? ''));
                if (!empty($name)) { $studentName = $name; break; }
            }
          ?>
          <div class="text-sm text-gray-500">Showing all applications for <?= $studentName ? h($studentName) : 'student ID ' . (int)$filter_user ?> — <a href="manage_applications.php" class="text-sky-600 hover:underline">clear filter</a></div>
        <?php else: ?>
          <div class="text-sm text-gray-500">Showing recent applications. Use "View all" to see every application by a student.</div>
        <?php endif; ?>
      </div>
    </header>

    <?php if ($info_msg): ?>
      <div class="mb-4 p-4 bg-green-50 border border-green-100 rounded text-green-800"><?= h($info_msg) ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
      <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded text-red-800"><?= h($error_msg) ?></div>
    <?php endif; ?>

    <?php if ($view_app): ?>
      <!-- Detail panel for a single application -->
      <section class="mb-6 bg-white rounded shadow p-4">
        <div class="flex items-start justify-between">
          <div>
            <div class="text-sm text-gray-500">Application #<?= (int)$view_app['id'] ?> · Submitted: <?= h($view_app['submitted_at']) ?></div>
            <?php
                $viewName = trim(($view_app['first_name'] ?? '') . ' ' . ($view_app['middle_name'] ? $view_app['middle_name'] . ' ' : '') . ($view_app['last_name'] ?? ''));
            ?>
            <h2 class="text-xl font-semibold mt-1"><?= h($view_app['student_info']['first_name'] ?? $viewName ?: $view_app['username'] ?? 'Unknown') ?> <?= h($view_app['student_info']['last_name'] ?? '') ?></h2>
            <div class="text-sm text-gray-600 mt-1"><?= h($view_app['student_info']['email'] ?? $view_app['email'] ?? '') ?></div>
          </div>

          <!-- Top-right controls: Back, (optional) Delete, View all -->
          <div class="text-right space-x-2">
            <a href="manage_applications.php" class="inline-flex items-center px-3 py-1 text-sm rounded bg-gray-100 hover:bg-gray-200">Back to list</a>

            <?php if (($view_app['status'] ?? '') !== 'approved'): ?>
              <form method="post" onsubmit="return confirmDelete();" class="inline-block">
                <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                <input type="hidden" name="application_id" value="<?= (int)$view_app['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="inline-flex items-center px-3 py-1 text-sm rounded bg-gray-800 text-white hover:bg-gray-900">Delete</button>
              </form>
            <?php else: ?>
              <span class="inline-flex items-center px-3 py-1 text-sm rounded bg-gray-50 text-gray-500">Approved — cannot delete</span>
            <?php endif; ?>

            <a href="manage_applications.php?user_id=<?= (int)$view_app['user_id'] ?>" class="inline-flex items-center px-3 py-1 text-sm rounded bg-gray-100 hover:bg-gray-200">View all</a>
          </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <h4 class="font-medium">Courses</h4>
            <ul class="text-sm text-gray-700 mt-2 space-y-1">
              <?php
                $courseIds = $view_app['course_ids_arr'] ?? [];
                if (empty($courseIds)):
              ?>
                <li>—</li>
              <?php else:
                  // fetch course labels safely
                  $placeholders = implode(',', array_fill(0, count($courseIds), '?'));
                  $courses = [];
                  if (count($courseIds) > 0) {
                      $stmtC = $pdo->prepare("SELECT id, course_code, course_name FROM courses WHERE id IN ($placeholders)");
                      $stmtC->execute($courseIds);
                      $courses = $stmtC->fetchAll(PDO::FETCH_ASSOC);
                  }
                  foreach ($courses as $c):
              ?>
                <li><?= h($c['course_code']) ?> — <?= h($c['course_name']) ?></li>
              <?php endforeach; endif; ?>
            </ul>
          </div>

          <div>
            <h4 class="font-medium">Student details</h4>
            <div class="text-sm text-gray-700 mt-2">
              <div>Birth date: <?= h($view_app['student_info']['birth_date'] ?? '—') ?></div>
              <div>Contact: <?= h($view_app['student_info']['contact'] ?? '—') ?></div>
              <div>Birthplace: <?= h($view_app['student_info']['birthplace'] ?? '—') ?></div>
              <div class="mt-2">Religion: <?= h($view_app['student_info']['religion'] ?? '—') ?></div>
              <div>Age: <?= h($view_app['student_info']['age'] ?? '—') ?></div>
            </div>
          </div>

          <div>
            <h4 class="font-medium">Documents</h4>
            <ul class="list-disc list-inside text-sm text-gray-600 mt-1">
              <?php if (empty($view_app['files'])): ?>
                <li>—</li>
              <?php else: ?>
                <?php foreach ($view_app['files'] as $f): ?>
                  <li>
                    <span class="font-medium"><?= h($f['type'] ?? 'Document') ?>:</span>
                    <?php if (!empty($f['stored_name'])): ?>
                      <a href="<?= h($baseUrl . '/' . $f['stored_name']) ?>" target="_blank" rel="noopener" class="text-sky-600 hover:underline"><?= h($f['original_name'] ?? 'file') ?></a>
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
                    <?php else: ?>
                      <?= h($f['original_name'] ?? 'file') ?>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </div>
        </div>

        <div class="mt-4 flex gap-3">
          <?php if (($view_app['status'] ?? '') === 'submitted'): ?>
            <form method="post" onsubmit="return confirmApprove();" class="inline">
              <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
              <input type="hidden" name="application_id" value="<?= (int)$view_app['id'] ?>">
              <input type="hidden" name="action" value="approve">
              <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve & Enroll</button>
            </form>

            <form method="post" onsubmit="return confirmReject();" class="inline">
              <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
              <input type="hidden" name="application_id" value="<?= (int)$view_app['id'] ?>">
              <input type="hidden" name="action" value="reject">
              <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Reject</button>
            </form>

            <form method="post" onsubmit="return confirmDelete();" class="inline">
              <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
              <input type="hidden" name="application_id" value="<?= (int)$view_app['id'] ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="confirm_delete" value="1">
              <button type="submit" class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">Delete</button>
            </form>
          <?php else: ?>
            <span class="inline-flex items-center px-3 py-1 rounded bg-gray-100 text-sm text-gray-700">No actions (status: <?= h($view_app['status']) ?>)</span>
          <?php endif; ?>

          <!-- View all by student -->
          <a href="manage_applications.php?user_id=<?= (int)$view_app['user_id'] ?>" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 text-sm hover:bg-gray-200">View all applications by student</a>
        </div>

        <?php if (!empty($view_app['processed_at'])): ?>
          <div class="mt-3 text-xs text-gray-500">Processed at: <?= h($view_app['processed_at']) ?><?php if (!empty($view_app['processed_by_name'])) echo ' by ' . h($view_app['processed_by_name']); ?></div>
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <!-- Replaced applications list view with card-style "Your Applications" layout -->
    <section id="applications" class="bg-white shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-3">Your Applications</h3>

      <?php if (empty($applications)): ?>
        <div class="text-sm text-gray-600">You have not submitted any enrollment applications yet.</div>
      <?php else: ?>
        <div class="space-y-4">
          <?php
            // Prepare a small statement to resolve processed_by -> name for list entries
            $userStmt = $pdo->prepare("SELECT first_name, middle_name, last_name, username FROM users WHERE id = ? LIMIT 1");
          ?>
          <?php foreach ($applications as $app): ?>
            <?php
              $files = json_decode($app['files'] ?: '[]', true);
              $courses_applied = json_decode($app['course_ids'] ?: '[]', true);
              $parent_info = json_decode($app['parent_info'] ?: '[]', true) ?: [];
              $student_info = json_decode($app['student_info'] ?: '[]', true) ?: [];
              // resolve processed_by name if possible
              $processed_by_display = $app['processed_by'] ?? null;
              if (!empty($app['processed_by'])) {
                  try {
                      $userStmt->execute([(int)$app['processed_by']]);
                      $u = $userStmt->fetch(PDO::FETCH_ASSOC);
                      if ($u) {
                          $uName = trim($u['first_name'] . ' ' . ($u['middle_name'] ? $u['middle_name'] . ' ' : '') . $u['last_name']);
                          $processed_by_display = $uName ?: $u['username'];
                      }
                  } catch (Throwable $ignore) {}
              }
            ?>
            <div class="border rounded p-4">
              <div class="flex items-start justify-between">
                <div>
                  <div class="text-sm text-gray-900 font-medium">Application #<?= (int)$app['id'] ?></div>
                  <div class="text-xs text-gray-500">Submitted: <?= h($app['submitted_at']) ?></div>
                </div>

                <div class="flex items-center space-x-2">
                  <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium <?= ($app['status'] === 'approved' ? 'bg-green-100 text-green-800' : ($app['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                    <?= h(ucfirst((string)$app['status'])) ?>
                  </span>

                  <?php if (($app['status'] ?? '') === 'submitted'): ?>
                    <form method="post" onsubmit="return confirmApprove();" class="inline-block">
                      <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                      <input type="hidden" name="application_id" value="<?= (int)$app['id'] ?>">
                      <input type="hidden" name="action" value="approve">
                      <button type="submit" class="inline-flex items-center px-2 py-1 text-sm rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
                    </form>

                    <form method="post" onsubmit="return confirmReject();" class="inline-block">
                      <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                      <input type="hidden" name="application_id" value="<?= (int)$app['id'] ?>">
                      <input type="hidden" name="action" value="reject">
                      <button type="submit" class="inline-flex items-center px-2 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700">Reject</button>
                    </form>

                    <!-- Delete button for non-approved applications (list/card view) -->
                    <form method="post" onsubmit="return confirmDelete();" class="inline-block">
                      <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                      <input type="hidden" name="application_id" value="<?= (int)$app['id'] ?>">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="confirm_delete" value="1">
                      <button type="submit" class="inline-flex items-center px-2 py-1 text-sm rounded bg-gray-800 text-white hover:bg-gray-900">Delete</button>
                    </form>
                  <?php else: ?>
                    <?php if (($app['status'] ?? '') !== 'approved'): ?>
                      <!-- For non-approved but processed (e.g. rejected), allow delete too -->
                      <form method="post" onsubmit="return confirmDelete();" class="inline-block">
                        <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                        <input type="hidden" name="application_id" value="<?= (int)$app['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="confirm_delete" value="1">
                        <button type="submit" class="inline-flex items-center px-2 py-1 text-sm rounded bg-gray-800 text-white hover:bg-gray-900">Delete</button>
                      </form>
                    <?php else: ?>
                      <span class="text-xs text-gray-500">Processed</span>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>

              <div class="mt-3 text-sm text-gray-700">
                <div><strong>Student:</strong>
                  <?php if (!empty($student_info) && is_array($student_info)): ?>
                    <?= h($student_info['first_name'] ?? '') ?> <?= h($student_info['middle_name'] ?? '') ?> <?= h($student_info['last_name'] ?? '') ?> — <?= h($student_info['email'] ?? '') ?>
                    <div class="text-xs text-gray-500">DOB: <?= h($student_info['birth_date'] ?? '—') ?> · Birthplace: <?= h($student_info['birthplace'] ?? '—') ?> · Gender: <?= h($student_info['gender'] ?? '—') ?> · Age: <?= h($student_info['age'] ?? '—') ?></div>
                    <?php $a = $student_info['address'] ?? null; if (!empty($a) && is_array($a)): ?>
                      <div class="text-xs text-gray-500 mt-1">
                        <?= h($a['house_no'] ?? '') ?> <?= h($a['street'] ?? '') ?> <?= h($a['barangay'] ?? '') ?>, <?= h($a['city'] ?? '') ?>, <?= h($a['province'] ?? '') ?> <?= h($a['country'] ?? '') ?> <?= h($a['zip'] ?? '') ?>
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
                      // fetch course labels for this application
                      $labels = [];
                      $courseIdsFiltered = array_map('intval', array_values($courses_applied));
                      if (count($courseIdsFiltered) > 0) {
                          $placeholders = implode(',', array_fill(0, count($courseIdsFiltered), '?'));
                          try {
                              $stmt2 = $pdo->prepare("SELECT course_code, course_name FROM courses WHERE id IN ($placeholders)");
                              $stmt2->execute($courseIdsFiltered);
                              $crs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                              $labels = array_map(fn($r)=> h($r['course_code']) . ' — ' . h($r['course_name']), $crs);
                          } catch (Throwable $ignore) {
                              // ignore failures retrieving course names
                          }
                      }
                    ?>
                    <?= $labels ? implode(', ', $labels) : '<span class="text-gray-500">None</span>' ?>
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
                      <div class="text-xs text-gray-500 mt-1"> Consent: <?= (!empty($parent_info['consent']) ? 'Yes' : 'No') ?> · Lives with student: <?= (!empty($parent_info['lives_with']) ? 'Yes' : 'No') ?> </div>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($files)): ?>
                  <div class="mt-2"><strong>Documents:</strong>
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
                <div class="mt-3 text-xs text-gray-500">Processed at: <?= h($app['processed_at']) ?> by <?= h($processed_by_display ?? 'system') ?></div>
              <?php endif; ?>

            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

  </main>
  <footer class="bg-white border-t mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center lg:px-8 py-6 text-sm text-gray-500">
      © <?= date('Y') ?> Your Institution — Admin Panel
    </div>
  </footer>
</body>
</html>