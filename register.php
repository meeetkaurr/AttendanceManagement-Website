<?php
$firstName = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];

// Check if passwords match
if ($password !== $repassword) {
    echo "Passwords do not match";
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Database connection
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "registration";

// Create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
} else {
    $SELECT = "SELECT email FROM register WHERE email = ? LIMIT 1";
    $INSERT = "INSERT INTO register (username, email, password) VALUES (?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($SELECT);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $rnum = $stmt->num_rows;

    if ($rnum == 0) {
        $stmt->close();

        // Prepare the INSERT statement
        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("sss", $firstName, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "New record inserted successfully";
        } else {
            echo "Error while inserting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Someone already registered using this email";
        $stmt->close();
        $conn->close();
    }
}
?>