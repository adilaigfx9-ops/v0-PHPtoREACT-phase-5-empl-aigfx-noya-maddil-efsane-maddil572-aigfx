<?php
/**
 * Cleanup Script - Clear caches, old logs, expired sessions
 * This script can be run manually or via cron job
 * 
 * Usage:
 * - Manual: php cleanup.php
 * - Cron: 0 3 * * * php /path/to/cleanup.php (daily at 3 AM)
 * - Admin Panel: Via manual trigger button
 */

require_once __DIR__ . '/../config/database.php';

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

$results = [
    'cache_cleared' => false,
    'sessions_cleaned' => 0,
    'rate_limits_cleaned' => 0,
    'logs_cleaned' => 0,
    'errors' => []
];

// =====================================================
// CLEAR FILE CACHE
// =====================================================
try {
    $cacheDir = __DIR__ . '/../cache';
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . '/*');
        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file) && unlink($file)) {
                $deleted++;
            }
        }
        $results['cache_cleared'] = true;
        $results['cache_files_deleted'] = $deleted;
    }
} catch (Exception $e) {
    $results['errors'][] = "Cache cleanup error: " . $e->getMessage();
}

// =====================================================
// CLEAN EXPIRED SESSIONS
// =====================================================
try {
    $stmt = $conn->prepare("
        DELETE FROM user_sessions 
        WHERE expires_at < NOW()
    ");
    $stmt->execute();
    $results['sessions_cleaned'] = $stmt->rowCount();
} catch (Exception $e) {
    $results['errors'][] = "Session cleanup error: " . $e->getMessage();
}

// =====================================================
// CLEAN OLD RATE LIMITS
// =====================================================
try {
    $stmt = $conn->prepare("
        DELETE FROM rate_limits 
        WHERE window_start < DATE_SUB(NOW(), INTERVAL 2 HOUR)
    ");
    $stmt->execute();
    $results['rate_limits_cleaned'] = $stmt->rowCount();
} catch (Exception $e) {
    $results['errors'][] = "Rate limit cleanup error: " . $e->getMessage();
}

// =====================================================
// CLEAN OLD ACTIVITY LOGS (older than 90 days)
// =====================================================
try {
    $stmt = $conn->prepare("
        DELETE FROM activity_logs 
        WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)
    ");
    $stmt->execute();
    $results['logs_cleaned'] = $stmt->rowCount();
} catch (Exception $e) {
    $results['errors'][] = "Log cleanup error: " . $e->getMessage();
}

// =====================================================
// CLEAN TRANSLATION CACHE (older than 7 days)
// =====================================================
try {
    $stmt = $conn->prepare("
        DELETE FROM translation_cache 
        WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
    ");
    $stmt->execute();
    $results['translation_cache_cleaned'] = $stmt->rowCount();
} catch (Exception $e) {
    $results['errors'][] = "Translation cache cleanup error: " . $e->getMessage();
}

// =====================================================
// OUTPUT RESULTS
// =====================================================
$results['success'] = empty($results['errors']);
$results['timestamp'] = date('Y-m-d H:i:s');

// If called via web (AJAX), return JSON
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    // CLI output
    echo "Cleanup Complete\n";
    echo "================\n";
    echo "Timestamp: {$results['timestamp']}\n";
    echo "Cache cleared: " . ($results['cache_cleared'] ? 'Yes' : 'No') . "\n";
    echo "Cache files deleted: " . ($results['cache_files_deleted'] ?? 0) . "\n";
    echo "Expired sessions cleaned: {$results['sessions_cleaned']}\n";
    echo "Old rate limits cleaned: {$results['rate_limits_cleaned']}\n";
    echo "Old logs cleaned: {$results['logs_cleaned']}\n";
    echo "Translation cache cleaned: " . ($results['translation_cache_cleaned'] ?? 0) . "\n";
    
    if (!empty($results['errors'])) {
        echo "\nErrors:\n";
        foreach ($results['errors'] as $error) {
            echo "- $error\n";
        }
    }
}
