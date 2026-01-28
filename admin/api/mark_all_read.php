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
    // Verify table exists first
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
    
    $pdo->query("UPDATE admin_notifications SET is_read = 1 WHERE is_read = 0");
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    error_log("Mark all read error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}