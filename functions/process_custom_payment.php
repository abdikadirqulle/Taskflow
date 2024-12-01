<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $user_id = $_SESSION['user_id'];
    $plan_type = $data['plan'] ?? '';
    $payment_type = $data['payment_type'] ?? '';
    $payment_details = $data['payment_details'] ?? [];
    
    // Validate the payment details
    if ($payment_type === 'mastercard') {
        if (empty($payment_details['card_number']) || 
            empty($payment_details['holder_name']) || 
            empty($payment_details['expiry']) || 
            empty($payment_details['cvv'])) {
            echo json_encode(['success' => false, 'message' => 'Missing card details']);
            exit;
        }
    } else if ($payment_type === 'evc') {
        if (empty($payment_details['phone_number']) || 
            empty($payment_details['account_name'])) {
            echo json_encode(['success' => false, 'message' => 'Missing EVC details']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid payment type']);
        exit;
    }
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Create payment record
        $stmt = $conn->prepare("INSERT INTO payments (user_id, plan_type, payment_type, amount, status) VALUES (?, ?, ?, ?, 'completed')");
        $amount = ($plan_type === 'pro') ? 9.99 : 24.99;
        $stmt->execute([$user_id, $plan_type, $payment_type, $amount]);
        
        // Update user's plan
        $stmt = $conn->prepare("UPDATE users SET plan_type = ?, subscription_date = NOW() WHERE id = ?");
        $stmt->execute([$plan_type, $user_id]);
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Payment processed successfully',
            'plan' => $plan_type
        ]);
        
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Payment processing failed. Please try again.'
        ]);
    }
}
?>
