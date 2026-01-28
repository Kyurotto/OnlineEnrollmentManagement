<?php
// public/admin_dashboard.php
// Admin Dashboard (updated)
// - More robust admin name resolution (session + DB fallback)
// - Safer session handling
// - Improved try_count implementation (PDO + mysqli support)
// - Added submitted applications count
// - More resilient recent-logs reader (accepts several common column names)
// - Minor UI tweaks: shows Submitted Applications and Applicants counts
//
// Requires: ../includes/db_connect.php (PDO $pdo or mysqli $conn) and ../includes/auth.php
// Place in public/ (or admin/) and protect via require_admin() as shown below.

require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

// Ensure session started
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

// Resolve admin display name: prefer session full name, then username, then try DB lookup by id
$adminName = '';
if (!empty($_SESSION['user']['name'])) {
    $adminName = htmlspecialchars($_SESSION['user']['name']);
} elseif (!empty($_SESSION['username'])) {
    $adminName = htmlspecialchars($_SESSION['username']);
} elseif (!empty($_SESSION['user_id']) && isset($pdo) && $pdo instanceof PDO) {
    try {
        $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, username FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([(int)$_SESSION['user_id']]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            $fullName = trim($r['first_name'] . ' ' . ($r['middle_name'] ? $r['middle_name'] . ' ' : '') . $r['last_name']);
            $adminName = htmlspecialchars($fullName ?: $r['username']);
        }
    } catch (Throwable $e) {
        // ignore and continue with blank adminName
        error_log('Admin name lookup failed: ' . $e->getMessage());
    }
}

// Helper: attempt multiple count queries and return first successful integer or null
function try_count(array $queries) {
    global $pdo, $conn;
    foreach ($queries as $sql) {
        try {
            if (!empty($pdo) && $pdo instanceof PDO) {
                $stmt = $pdo->query($sql);
                if ($stmt !== false) {
                    $val = $stmt->fetchColumn();
                    if ($val !== false && $val !== null) {
                        return (int)$val;
                    }
                }
            } elseif (!empty($conn) && $conn instanceof mysqli) {
                if ($result = $conn->query($sql)) {
                    $row = $result->fetch_row();
                    if ($row !== null && isset($row[0])) {
                        return (int)$row[0];
                    }
                }
            }
        } catch (Throwable $e) {
            // try next query
            continue;
        }
    }
    return null;
}

// Prepare realistic queries for each metric
$activeCoursesQueries = [
    "SELECT COUNT(*) FROM courses",
    // alternate: some schemas may include active flag
    "SELECT COUNT(*) FROM courses WHERE IFNULL(active,1)=1"
];

$studentCountQueries = [
    "SELECT COUNT(*) FROM users WHERE role = 'student'"
];

$pendingPaymentsQueries = [
    "SELECT COUNT(*) FROM payments WHERE payment_status = 'pending'",
    "SELECT COUNT(*) FROM payments WHERE status = 'pending'",
    "SELECT COUNT(*) FROM payments"
];

$completedPaymentsQueries = [
    "SELECT COUNT(*) FROM payments WHERE payment_status = 'completed'",
    "SELECT COUNT(*) FROM payments WHERE status = 'completed'",
    "SELECT COUNT(*) FROM payments"
];

$submittedApplicationsQueries = [
    "SELECT COUNT(*) FROM enrollment_applications WHERE status = 'submitted'",
    "SELECT COUNT(*) FROM enrollment_applications"
];

// run counts
$activeCourses = try_count($activeCoursesQueries);
$studentCount = try_count($studentCountQueries);
$pendingPayments = try_count($pendingPaymentsQueries);
$completedPayments = try_count($completedPaymentsQueries);
$submittedApplications = try_count($submittedApplicationsQueries);

// Applicants count (distinct users who submitted applications)
function count_applicants() {
    global $pdo, $conn;
    try {
        if (!empty($pdo) && $pdo instanceof PDO) {
            $stmt = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM enrollment_applications");
            return (int)$stmt->fetchColumn();
        } elseif (!empty($conn) && $conn instanceof mysqli) {
            $res = $conn->query("SELECT COUNT(DISTINCT user_id) FROM enrollment_applications");
            $r = $res->fetch_row();
            return (int)$r[0];
        }
    } catch (Throwable $e) {
        error_log('count_applicants error: ' . $e->getMessage());
    }
    return null;
}
$applicantsCount = count_applicants();

// Recent logs: try several likely table names and flexible columns
function fetch_logs($limit = 10) {
    global $pdo, $conn;
    $tables = ['system_logs', 'logs', 'audit_logs', 'admin_logs'];
    foreach ($tables as $table) {
        // try many common column names in SELECT so we don't fail if a column is missing
        $sql = "SELECT
                    COALESCE(id, '') AS id,
                    COALESCE(created_at, created_on, time, '') AS created_at,
                    COALESCE(level, severity, '') AS level,
                    COALESCE(message, msg, text, '') AS message
                FROM `$table`
                ORDER BY COALESCE(created_at, created_on, time) DESC
                LIMIT " . (int)$limit;
        try {
            if (!empty($pdo) && $pdo instanceof PDO) {
                $stmt = $pdo->query($sql);
                if ($stmt !== false) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($rows)) return [$table, $rows];
                }
            } elseif (!empty($conn) && $conn instanceof mysqli) {
                if ($result = $conn->query($sql)) {
                    $rows = [];
                    while ($r = $result->fetch_assoc()) $rows[] = $r;
                    if (!empty($rows)) return [$table, $rows];
                }
            }
        } catch (Throwable $e) {
            // try next table
            continue;
        }
    }
    return [null, []];
}

list($logsTable, $recentLogs) = fetch_logs(10);

// Helper to render a safe integer or placeholder
function render_count($v) {
    return ($v === null) ? '—' : number_format((int)$v);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              DEFAULT: '#1f2937',
              accent: '#0ea5a4'
            }
          }
        }
      }
    }
  </script>
  <style>
    body::-webkit-scrollbar{ display:none; }
  </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <div class="flex items-center space-x-3">
          <div class="h-10 w-10 rounded-md bg-brand p-2 text-white flex items-center justify-center font-bold">AD</div>
          <div>
            <h1 class="text-xl font-semibold">Admin Dashboard</h1>
            <p class="text-sm text-gray-500">Manage courses, students and payments</p>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <div class="relative inline-block text-left mr-2">
              <button id="notif-btn" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                  </svg>
                  <span id="notif-badge" class="hidden absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">0</span>
              </button>

              <div id="notif-menu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl border z-50 overflow-hidden">
                  <div class="bg-gray-800 px-4 py-2 text-white font-bold text-xs uppercase tracking-wider">Alerts Center</div>
                  <div id="notif-list" class="max-h-60 overflow-y-auto">
                      <p class="p-4 text-xs text-gray-400 text-center">No new alerts</p>
                  </div>
                  <button id="mark-read-btn" class="w-full text-center py-2 text-[10px] font-bold text-blue-600 hover:bg-gray-50 border-t uppercase">Mark all as read</button>
              </div>
          </div>

          <?php if ($adminName): ?>
            <div class="text-right">
              <p class="text-sm text-gray-600">Signed in as</p>
              <p class="font-medium"><?php echo $adminName; ?></p>
            </div>
          <?php endif; ?>

          <a href="../public/logout.php"
             class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
             aria-label="Logout">Logout</a>
        </div>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <section class="mb-6">
      <div class="rounded-lg bg-white shadow p-6">
        <h2 class="text-2xl font-semibold mb-2">Welcome, Administrator</h2>
        <p class="text-gray-600">Use the controls below to manage the system.</p>
      </div>
    </section>

    <section aria-label="Admin actions">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="manage_courses.php" class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Manage Courses</h3>
              <p class="mt-2 text-sm text-gray-500">Create, edit or remove course offerings.</p>
            </div>
            <div class="ml-4 flex-shrink-0">
              <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.4 18.4A9 9 0 1112 3v3"></path>
              </svg>
            </div>
          </div>
        </a>

        <a href="manage_students.php" class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Manage Students</h3>
              <p class="mt-2 text-sm text-gray-500">View and update student records and enrollments.</p>
            </div>
            <div class="ml-4 flex-shrink-0">
              <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20H4v-2a4 4 0 014-4h1"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a4 4 0 100-8 4 4 0 000 8z"></path>
              </svg>
            </div>
          </div>
        </a>

        <a href="manage_payments.php" class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Manage Payments</h3>
              <p class="mt-2 text-sm text-gray-500">Approve refunds, view transactions and resolve issues.</p>
            </div>
            <div class="ml-4 flex-shrink-0">
              <svg class="h-10 w-10 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-3.866 0-7 1.79-7 4v2a2 2 0 002 2h10a2 2 0 002-2v-2c0-2.21-3.134-4-7-4z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8V6m0 0a4 4 0 110 8"></path>
              </svg>
            </div>
          </div>
        </a>

        <a href="manage_applications.php" class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Manage Applications</h3>
              <p class="mt-2 text-sm text-gray-500">Review, accept, or decline user applications.</p>
            </div>
            <div class="ml-4 flex-shrink-0">
              <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z"></path>
              </svg>
            </div>
          </div>
        </a>
      </div>
    </section>

    <section class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="rounded-lg bg-white p-6 shadow col-span-2">
        <h3 class="text-lg font-medium mb-4">Overview</h3>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
          <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Active Courses</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900"><?php echo render_count($activeCourses); ?></p>
          </div>
          <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Students</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900"><?php echo render_count($studentCount); ?></p>
          </div>
          <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Pending Payments</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900"><?php echo render_count($pendingPayments); ?></p>
          </div>
          <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Completed Payments</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900"><?php echo render_count($completedPayments); ?></p>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="p-4 bg-white rounded border">
            <p class="text-sm text-gray-500">Submitted Applications</p>
            <p class="mt-2 text-xl font-semibold"><?php echo render_count($submittedApplications); ?></p>
          </div>
          <div class="p-4 bg-white rounded border">
            <p class="text-sm text-gray-500">Applicants</p>
            <p class="mt-2 text-xl font-semibold"><?php echo render_count($applicantsCount); ?></p>
          </div>
          <div class="p-4 bg-white rounded border">
            <p class="text-sm text-gray-500">Recent Logs</p>
            <p class="mt-2 text-xl font-semibold"><?php echo (empty($recentLogs) ? '—' : count($recentLogs)); ?></p>
          </div>
        </div>

        <?php if (!empty($recentLogs)): ?>
          <div class="mt-6">
            <h4 class="text-md font-medium mb-2">Recent logs (<?php echo htmlspecialchars($logsTable); ?>)</h4>
            <div class="max-h-48 overflow-auto bg-gray-50 rounded p-3 text-sm">
              <ul class="space-y-2">
                <?php foreach ($recentLogs as $row): ?>
                  <li class="border-l-4 border-gray-200 pl-3">
                    <div class="flex justify-between items-start">
                      <div class="pr-4">
                        <div class="text-xs text-gray-500"><?php echo isset($row['created_at']) ? htmlspecialchars($row['created_at']) : ''; ?> <?php echo isset($row['level']) && $row['level'] !== '' ? ' · ' . htmlspecialchars($row['level']) : ''; ?></div>
                        <div class="text-sm text-gray-800"><?php echo isset($row['message']) ? htmlspecialchars(mb_strimwidth($row['message'], 0, 200, '...')) : '[no message]'; ?></div>
                      </div>
                      <div class="text-xs text-gray-400"><?php echo isset($row['id']) && $row['id'] !== '' ? '#'.htmlspecialchars($row['id']) : ''; ?></div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>

      </div>

      <div class="rounded-lg bg-white p-6 shadow">
        <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
        <div class="flex flex-col gap-3">
          <a href="manage_courses.php" class="w-full inline-flex items-center justify-between px-4 py-2 border rounded-md bg-brand text-white hover:opacity-95">Add / Edit Courses <span class="text-sm opacity-80">→</span></a>
          <a href="manage_students.php" class="w-full inline-flex items-center justify-between px-4 py-2 border rounded-md bg-brand text-white hover:opacity-95">Search Students <span class="text-sm opacity-80">→</span></a>
          <a href="manage_payments.php" class="w-full inline-flex items-center justify-between px-4 py-2 border rounded-md bg-brand text-white hover:opacity-95">Payment Queue <span class="text-sm opacity-80">→</span></a>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-white border-t">
    <div class="max-w-7xl mx-auto px-4 text-center sm:px-6 lg:px-8 py-4 text-sm text-gray-500">
      © <?php echo date('Y'); ?> Your Institution — Admin Panel
    </div>
  </footer>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const btn = document.getElementById('notif-btn');
      const menu = document.getElementById('notif-menu');
      const list = document.getElementById('notif-list');
      const badge = document.getElementById('notif-badge');
      const markReadBtn = document.getElementById('mark-read-btn');

      btn.onclick = () => menu.classList.toggle('hidden');
      window.onclick = (e) => { 
          if (!btn.contains(e.target) && !menu.contains(e.target)) menu.classList.add('hidden'); 
      };

      function updateNotifications() {
          fetch('api/get_notifications.php')
              .then(res => res.json())
              .then(data => {
                  console.log('Notifications data:', data);
                  
                  if (data.error) {
                      console.error('Notification error:', data.error);
                      list.innerHTML = '<p class="p-4 text-xs text-red-500 text-center">Error loading notifications</p>';
                      return;
                  }
                  
                  if (data.unread_count > 0) {
                      badge.innerText = data.unread_count > 9 ? '9+' : data.unread_count;
                      badge.classList.remove('hidden');
                  } else {
                      badge.classList.add('hidden');
                  }

                  if (data.notifications && data.notifications.length > 0) {
                      list.innerHTML = data.notifications.map(n => `
                          <a href="${n.link}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                              <div class="text-[10px] text-gray-400 mb-1">${n.time_ago}</div>
                              <div class="text-xs text-gray-700 font-semibold">${n.message}</div>
                          </a>
                      `).join('');
                  } else {
                      list.innerHTML = '<p class="p-4 text-xs text-gray-400 text-center">No new alerts</p>';
                  }
              })
              .catch(err => {
                  console.error('Fetch error:', err);
                  list.innerHTML = '<p class="p-4 text-xs text-red-500 text-center">Connection error</p>';
              });
      }

      markReadBtn.onclick = () => {
          fetch('api/mark_all_read.php').then(() => updateNotifications());
      };

      updateNotifications();
      setInterval(updateNotifications, 30000); // Check every 30 seconds
  });
  </script>
</body>
</html>