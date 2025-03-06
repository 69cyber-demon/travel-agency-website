<?php
session_start();

// Database configuration
$host   = "localhost";
$dbname = "bekery";
$dbuser = "root";    // Change as needed
$dbpass = "";        // Change as needed

// Create a connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $fname            = trim($_POST['fname']);
    $mname            = trim($_POST['mname']);
    $sname            = trim($_POST['sname']);
    $email            = trim($_POST['email']);
    $username         = trim($_POST['username']);
    $gender           = trim($_POST['gender']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation: Check required fields
    if (empty($fname) || empty($sname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM userdata WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Username or email already exists. Please choose another.";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO userdata (fname, mname, sname, email, username, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fname, $mname, $sname, $email, $username, $gender, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
