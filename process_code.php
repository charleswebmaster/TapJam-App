<?php
$servername = "localhost";
$username = "charulpd_tapjamref";
$password = "Insta@=+{}{?G4224";
$dbname = "charulpd_tapjamref";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'];
$refCode = $data['refCode'];

// Check if the referral code has already been used by the current user
$checkUsedQuery = "SELECT * FROM used_referrals WHERE user_id='$userId' AND referral_code='$refCode'";
$result = $conn->query($checkUsedQuery);

if ($result->num_rows > 0) {
    echo json_encode(['error' => 'You have already used this referral code']);
} else {
    // Verify if the referral code exists and get the referrer_id
    $verifyQuery = "SELECT referrer_id FROM referrals WHERE referral_code='$refCode'";
    $verifyResult = $conn->query($verifyQuery);

    if ($verifyResult->num_rows > 0) {
        $row = $verifyResult->fetch_assoc();
        $referrerId = $row['referrer_id'];

        if ($referrerId == $userId) {
            echo json_encode(['error' => 'You cannot use your own referral code']);
        } else {
            // Update the tap counts for both the referrer and the new user
            $conn->query("UPDATE users SET taps = taps + 50000 WHERE user_id='$referrerId'");
            $conn->query("INSERT INTO users (user_id, taps) VALUES ('$userId', 50000) ON DUPLICATE KEY UPDATE taps = taps + 50000");

            // Mark the referral code as used by the current user
            $conn->query("INSERT INTO used_referrals (user_id, referral_code) VALUES ('$userId', '$refCode')");

            $referrerTaps = $conn->query("SELECT taps FROM users WHERE user_id='$referrerId'")->fetch_assoc()['taps'];
            $newUserTaps = $conn->query("SELECT taps FROM users WHERE user_id='$userId'")->fetch_assoc()['taps'];

            echo json_encode(['success' => true, 'referrerTaps' => $referrerTaps, 'newUserTaps' => $newUserTaps]);
        }
    } else {
        echo json_encode(['error' => 'Invalid referral code']);
    }
}

$conn->close();
?>