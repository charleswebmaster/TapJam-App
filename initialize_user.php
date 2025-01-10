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

// Get user_id from GET parameter
$user_id = $_GET['user_id'];

// Prepare SQL statement
$sql = "SELECT taps FROM users WHERE user_id = ?";

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($taps);

// Fetch the result
if ($stmt->fetch()) {
    // Return JSON response
    echo json_encode(array("taps" => $taps));
} else {
    // User not found or other error handling
    echo json_encode(array("error" => "User not found"));
}

$stmt->close();
$conn->close();

?>