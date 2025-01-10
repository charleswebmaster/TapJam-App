<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phoneNumber = htmlspecialchars(trim($_POST['phoneNumber']));
    $withdrawalMethod = htmlspecialchars(trim($_POST['withdrawalMethod']));
    $bankName = htmlspecialchars(trim($_POST['bankName']));
    $accountNumber = htmlspecialchars(trim($_POST['accountNumber']));
    $paypalEmail = htmlspecialchars(trim($_POST['paypalEmail']));
    $amount = htmlspecialchars(trim($_POST['amount']));

    // Basic validation
    if (empty($fullName) || empty($email) || empty($phoneNumber) || empty($withdrawalMethod) || empty($amount)) {
        echo "All required fields must be filled out.";
        exit;
    }

    // Ensure the amount is a valid number and at least $10
    if (!is_numeric($amount) || $amount < 10) {
        echo "Withdrawal amount must be at least $10.";
        exit;
    }

    // Additional validation for email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Ensure either bank details or PayPal email is provided based on the withdrawal method
    if ($withdrawalMethod === 'bank' && (empty($bankName) || empty($accountNumber))) {
        echo "Bank details are required for bank withdrawals.";
        exit;
    }
    if ($withdrawalMethod === 'paypal' && empty($paypalEmail)) {
        echo "PayPal email is required for PayPal withdrawals.";
        exit;
    }

    // Perform withdrawal processing (e.g., send email, update records, etc.)
    $to = "withdrawal.tapjam@techex.com.ng";
    $subject = "Withdrawal Request";
    $message = "Full Name: $fullName\nEmail: $email\nPhone Number: $phoneNumber\n";
    if ($withdrawalMethod === 'bank') {
        $message .= "Bank Name: $bankName\nAccount Number: $accountNumber\n";
    } else {
        $message .= "PayPal Email: $paypalEmail\n";
    }
    $message .= "Amount: $amount";
    $headers = "From: no-reply@techex.com.ng";

    if (mail($to, $subject, $message, $headers)) {
        echo "Withdrawal request sent successfully.";
    } else {
        echo "Failed to send withdrawal request.";
    }
} else {
    echo "Invalid request method.";
}
?>
