<?php
session_start();

if(isset($_SESSION["user_id"])){
    header("Location: index.html"); // Redirect to your desired page if the user is already logged in
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Database connection
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "registration"; // Replace with your database name
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $dbUsername, $dbPassword);
        $stmt->fetch();

        if (password_verify($password, $dbPassword)) {
            // Password is correct, so start a new session
            $_SESSION["user_id"] = $userId;
            $_SESSION["username"] = $dbUsername;

            // Redirect user to the desired page (e.g., index.html)
            header("Location: index.html");
            exit();
        } else {
            // Password is not valid, display an error message
            $login_err = "Invalid username or password.";
        }
    } else {
        // Username doesn't exist, display an error message
        $login_err = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
