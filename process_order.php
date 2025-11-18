<?php
// Configuration: REPLACE with your actual database credentials
$host = "localhost";     // e.g., 'localhost'
$user = "root";     // e.g., 'root'
$pass = "";     // e.g., 'password'
$db_name = "FOODIFY"; // e.g., 'foodify_db'

// Set response header to JSON
header('Content-Type: application/json');

// Strict check for POST request and required data integrity
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    !empty($_POST['item_name']) &&
    !empty($_POST['quantity']) &&
    !empty($_POST['customer_name'])
) {
    
    // 1. Sanitize and retrieve data from the AJAX request
    $item_name = filter_var($_POST['item_name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $customer_name = filter_var($_POST['customer_name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

    // 2. Establish database connection
    $conn = new mysqli($host, $user, $pass, $db_name);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit();
    }

    // 3. Prepare and execute the SQL statement (using prepared statements for security)
    $stmt = $conn->prepare("INSERT INTO orders (item_name, quantity, customer_name, phone, delivery_address) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'SQL prepare failed: ' . $conn->error]);
        $conn->close();
        exit();
    }
    
    // Bind parameters: (s=string, i=integer, s=string, s=string, s=string)
    $stmt->bind_param("sisss", $item_name, $quantity, $customer_name, $phone, $address);

    if ($stmt->execute()) {
        // Success response
        echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
    } else {
        // Failure response
        echo json_encode(['success' => false, 'message' => 'Error placing order: ' . $stmt->error]);
    }

    // 4. Close connections
    $stmt->close();
    $conn->close();

} else {
    // If not a valid POST request (e.g., direct browser access or missing data)
    echo json_encode(['success' => false, 'message' => 'Invalid or missing data in request. Order not processed.']);
}

?>