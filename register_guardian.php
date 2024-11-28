<?php
include 'db.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo "Connection failed";
    exit();
}

// Ensure required fields are set
if (!isset($_POST['user_id'], $_POST['mobile_number'], $_POST['firstName'], $_POST['lastName'])) {
    http_response_code(400); // Bad Request
    echo "Invalid Input";
    exit();
}

// Escape and sanitize inputs
$user_id = $conn->real_escape_string($_POST['user_id']);
$mobile_number = $conn->real_escape_string($_POST['mobile_number']);
$firstName = $conn->real_escape_string($_POST['firstName']);
$lastName = $conn->real_escape_string($_POST['lastName']);

// Insert the guardian into the table with the same user_id
$sql = "INSERT INTO guardian (user_id, mobile_number, firstName, lastName) 
        VALUES ('$user_id', '$mobile_number', '$firstName', '$lastName')";

if ($conn->query($sql) === TRUE) {
    http_response_code(201); // Created
    echo "success";
} else {
    http_response_code(500); // Internal Server Error
    echo "error";
}

$conn->close();
?>
