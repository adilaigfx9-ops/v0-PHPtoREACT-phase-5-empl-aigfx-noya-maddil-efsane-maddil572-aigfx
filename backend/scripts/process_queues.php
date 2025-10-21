<?php
/**
 * Process Email and WhatsApp Queues
 * This script can be run manually or via cron job
 * 
 * Usage:
 * - Manual: php process_queues.php
 * - Cron: 0 * * * * php /path/to/process_queues.php
 * - Admin Panel: Via manual trigger button
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/EmailService.php';

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

$results = [
    'emails_processed' => 0,
    'whatsapp_processed' => 0,
    'telegram_processed' => 0,
    'errors' => []
];

// =====================================================
// PROCESS EMAIL QUEUE
// =====================================================
try {
    // Get pending email campaigns
    $stmt = $conn->prepare("
        SELECT * FROM email_campaigns 
        WHERE status = 'pending' 
        AND scheduled_at <= NOW() 
        LIMIT 50
    ");
    $stmt->execute();
    $emailCampaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($emailCampaigns as $campaign) {
        try {
            // Update status to sending
            $updateStmt = $conn->prepare("UPDATE email_campaigns SET status = 'sending' WHERE id = ?");
            $updateStmt->execute([$campaign['id']]);
            
            // Here you would integrate with SendGrid or your email service
            // For now, just mark as sent
            $updateStmt = $conn->prepare("
                UPDATE email_campaigns 
                SET status = 'sent', sent_at = NOW() 
                WHERE id = ?
            ");
            $updateStmt->execute([$campaign['id']]);
            
            $results['emails_processed']++;
        } catch (Exception $e) {
            $results['errors'][] = "Email {$campaign['id']}: " . $e->getMessage();
            
            // Mark as failed
            $updateStmt = $conn->prepare("UPDATE email_campaigns SET status = 'failed' WHERE id = ?");
            $updateStmt->execute([$campaign['id']]);
        }
    }
} catch (Exception $e) {
    $results['errors'][] = "Email queue error: " . $e->getMessage();
}

// =====================================================
// PROCESS WHATSAPP QUEUE
// =====================================================
try {
    // Get queued WhatsApp messages
    $stmt = $conn->prepare("
        SELECT * FROM whatsapp_messages 
        WHERE status = 'queued' 
        LIMIT 50
    ");
    $stmt->execute();
    $whatsappMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($whatsappMessages as $message) {
        try {
            // Update status to sending
            $updateStmt = $conn->prepare("UPDATE whatsapp_messages SET status = 'sending' WHERE id = ?");
            $updateStmt->execute([$message['id']]);
            
            // Here you would integrate with WhatsApp Business API
            // For now, just mark as sent
            $updateStmt = $conn->prepare("
                UPDATE whatsapp_messages 
                SET status = 'sent', sent_at = NOW() 
                WHERE id = ?
            ");
            $updateStmt->execute([$message['id']]);
            
            $results['whatsapp_processed']++;
        } catch (Exception $e) {
            $results['errors'][] = "WhatsApp {$message['id']}: " . $e->getMessage();
            
            // Mark as failed
            $updateStmt = $conn->prepare("UPDATE whatsapp_messages SET status = 'failed' WHERE id = ?");
            $updateStmt->execute([$message['id']]);
        }
    }
} catch (Exception $e) {
    $results['errors'][] = "WhatsApp queue error: " . $e->getMessage();
}

// =====================================================
// PROCESS TELEGRAM QUEUE
// =====================================================
try {
    // Get queued Telegram notifications
    $stmt = $conn->prepare("
        SELECT * FROM telegram_notifications 
        WHERE status = 'queued' 
        LIMIT 50
    ");
    $stmt->execute();
    $telegramNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($telegramNotifications as $notification) {
        try {
            // Here you would integrate with Telegram Bot API
            // For now, just mark as sent
            $updateStmt = $conn->prepare("
                UPDATE telegram_notifications 
                SET status = 'sent', sent_at = NOW() 
                WHERE id = ?
            ");
            $updateStmt->execute([$notification['id']]);
            
            $results['telegram_processed']++;
        } catch (Exception $e) {
            $results['errors'][] = "Telegram {$notification['id']}: " . $e->getMessage();
            
            // Mark as failed
            $updateStmt = $conn->prepare("UPDATE telegram_notifications SET status = 'failed' WHERE id = ?");
            $updateStmt->execute([$notification['id']]);
        }
    }
} catch (Exception $e) {
    $results['errors'][] = "Telegram queue error: " . $e->getMessage();
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
    echo "Queue Processing Complete\n";
    echo "========================\n";
    echo "Timestamp: {$results['timestamp']}\n";
    echo "Emails processed: {$results['emails_processed']}\n";
    echo "WhatsApp processed: {$results['whatsapp_processed']}\n";
    echo "Telegram processed: {$results['telegram_processed']}\n";
    
    if (!empty($results['errors'])) {
        echo "\nErrors:\n";
        foreach ($results['errors'] as $error) {
            echo "- $error\n";
        }
    }
}
