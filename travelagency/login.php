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
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username exists
    $stmt = $conn->prepare("SELECT id, password FROM userdata WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.html");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Username does not exist.";
    }
    $stmt->close();
}

$conn->close();
?>