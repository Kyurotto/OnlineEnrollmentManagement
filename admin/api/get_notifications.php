<?php
// Ensure session is started before any output
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db_connect.php';

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

try {
    // Verify table exists
    $tableCheckStmt = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'school' AND TABLE_NAME = 'admin_notifications'");
    $tableExists = $tableCheckStmt->fetchColumn() !== false;
    
    if (!$tableExists) {
        error_log("admin_notifications table does not exist, creating it...");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_notifications` (
          `id` INT PRIMARY KEY AUTO_INCREMENT,
          `message` VARCHAR(255) NOT NULL,
          `link` VARCHAR(255) DEFAULT NULL, 
          `is_read` TINYINT(1) DEFAULT 0,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }
    
    // Debug: Check total notifications count
    $totalCount = (int)$pdo->query("SELECT COUNT(*) FROM admin_notifications")->fetchColumn();
    error_log("Total notifications in DB: " . $totalCount);
    
    // Get unread notifications
    $stmt = $pdo->query("SELECT *, 
        CASE 
            WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, created_at, NOW()), 'm ago')
            WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, created_at, NOW()), 'h ago')
            ELSE CONCAT(TIMESTAMPDIFF(DAY, created_at, NOW()), 'd ago')
        END as time_ago
        FROM admin_notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5");
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Unread notifications returned: " . count($notifications));

    $count = (int)$pdo->query("SELECT COUNT(*) FROM admin_notifications WHERE is_read = 0")->fetchColumn();
    
    error_log("Unread count: " . $count);
    error_log("Response: " . json_encode(['notifications' => $notifications, 'unread_count' => $count, 'total_count' => $totalCount]));
    
    echo json_encode(['notifications' => $notifications, 'unread_count' => $count, 'total_count' => $totalCount]);
} catch (Exception $e) {
    error_log("Notification fetch error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}