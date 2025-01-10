<?php
$servername = "localhost";
$username = "charulpd_tapjamref";
$password = "Insta@=+{}{?G4224";
$dbname = "charulpd_tapjamref";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_GET['user_id'];

// Check if the user already has a referral code
$sql = "SELECT referral_code FROM referrals WHERE referrer_id='$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $refCode = $row['referral_code'];
    echo json_encode(['refCode' => $refCode]);
} else {
    $refCode = "TJ" . bin2hex(random_bytes(4)); // Generate a unique referral code

    $sql = "INSERT INTO referrals (referrer_id, referral_code) VALUES ('$userId', '$refCode')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['refCode' => $refCode]);
    } else {
        echo json_encode(['error' => 'Error generating referral code: ' . $conn->error]);
    }
}

$conn->close();
?>
