<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

// Collect data securely from POST
$email      = htmlspecialchars($_POST['email']);
$password      = htmlspecialchars($_POST['password']);


// Prepare SQL using prepared statement
$sql = "INSERT INTO UserInfo 
(email, password)
VALUES (?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("❌ SQL Prepare Failed: " . $conn->error);
}

// Bind parameters (use 'd' for salary if it's numeric)
$stmt->bind_param("ss", 
    $email, $password
);

if ($stmt->execute()) {
    echo "<h3>✅ User data saved successfully!</h3>";
    echo "<a href='index.html'><button> Go back </button></a>";
} else {
    echo "<h3>❌ Error: " . $stmt->error . "</h3>";
}

$stmt->close();
$conn->close();
?>
