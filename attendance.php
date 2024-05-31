<?php
$date = $_POST['date'];
$student1_name = $_POST['student1_name'];
$student1_attendance = $_POST['student1_attendance'];
$student2_name = $_POST['student2_name'];
$student2_attendance = $_POST['student2_attendance'];
// Add similar lines for other students

// Database connection
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "registration";

// Create a new database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
} else {
    // Insert attendance data for student 1
    $INSERT = "INSERT INTO attendance_data (date, student_name, attendance_status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($INSERT);
    $stmt->bind_param("sss", $date, $student1_name, $student1_attendance);
    $stmt->execute();

    // Add similar code for other students

    // Close the database connection
    $stmt->close();
    $conn->close();
}