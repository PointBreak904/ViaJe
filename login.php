<?php
include 'db.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo "Connection failed";
    exit();
}

// Ensure required fields are set
if (!isset($_POST['mobile_number'], $_POST['password'])) {
    http_response_code(400); // Bad Request
    echo "Invalid Input";
    exit();
}

// Escape and sanitize inputs
$mobile_number = $conn->real_escape_string($_POST['mobile_number']);
$password = $conn->real_escape_string($_POST['password']);

// Check if the user exists with the provided mobile number
$sql = "SELECT * FROM users WHERE mobile_number = '$mobile_number'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();
    $hashedPassword = $user['password'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
        // Login successful
        $userId = $user['id']; // Retrieve the user ID
        http_response_code(200); // OK
        echo "$userId"; // Return the user ID
    } else {
        // Invalid password
        http_response_code(401); // Unauthorized
        echo "invalid_password";
    }
} else {
    // Mobile number not found
    http_response_code(404); // Not Found
    echo "mobile_not_found";
}

$conn->close();
?>
