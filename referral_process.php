<?php

// Database connection details
$servername = "localhost";
$username = "charulpd_tapjamref";
$password = "Insta@=+{}{?G4224";
$dbname = "charulpd_tapjamref";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_GET['ref'];
$taps = $_GET['taps'];

// Update the user's tap count
$sql = "UPDATE users SET tap_count = tap_count + $taps WHERE user_id = '$user_id'";

if ($conn->query($sql) === TRUE) {
    echo "Tap count updated successfully";
} else {
    echo "Error updating tap count: " . $conn->error;
}

$conn->close();
?>