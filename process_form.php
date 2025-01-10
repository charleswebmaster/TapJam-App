<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['bank'], $_POST['account'])) {
        // Process withdrawal request
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $bank = htmlspecialchars($_POST['bank']);
        $account = htmlspecialchars($_POST['account']);

        $to = 'withdrawal.tapjam@techex.com.ng';
        $subject = 'Withdrawal Request';
        $message = "Name: $name\nEmail: $email\nPhone: $phone\nBank: $bank\nAccount Number: $account";
        $headers = "From: $email";

        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    } elseif (isset($_POST['referral_code'], $_POST['user_id'])) {
        // Process referral code
        $referral_code = htmlspecialchars($_POST['referral_code']);
        $user_id = htmlspecialchars($_POST['user_id']);

        // Check if referral code exists and is valid
        $sql = "SELECT * FROM users WHERE referral_code = '$referral_code'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Update user's taps
            $bonus_taps = 10000; // Example bonus taps
            $update_sql = "UPDATE users SET taps = taps + $bonus_taps WHERE user_id = '$user_id'";
            if ($conn->query($update_sql) === TRUE) {
                echo "Referral code applied successfully!";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Invalid referral code.";
        }
    } else {
        echo json_encode(["status" => "invalid_request"]);
    }

    $conn->close();
} else {
    echo json_encode(["status" => "invalid_request"]);
}
?>
