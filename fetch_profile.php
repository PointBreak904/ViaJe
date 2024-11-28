<?php
header("Content-Type: application/json");

// Error reporting adjustments to avoid unexpected output
error_reporting(0);  // Suppress warnings and notices
ini_set('display_errors', 0);

$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "viaje_db"; // Fixed variable casing

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$user_id = $_POST['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["error" => "User ID is required"]);
    exit;
}

$sql = $conn->prepare("SELECT first_name, last_name, mobile_number, self_description FROM users WHERE id = ?");
$sql->bind_param("s", $user_id);
$sql->execute();
$result = $sql->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "first_name" => $row['first_name'] ?? "N/A",
        "last_name" => $row['last_name'] ?? "N/A",
        "mobile_number" => $row['mobile_number'] ?? "N/A",
        "self_description" => $row['self_description'] ?? "No description"
    ]);
} else {
    echo json_encode(["error" => "User not found"]);
}

$sql->close();
$conn->close();
