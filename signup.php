<?php
include 'db.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo "Connection failed";
    exit();
}

// Ensure all required fields are set
if (!isset($_POST['userId'], $_POST['firstName'], $_POST['lastName'], $_POST['mobileNumber'], $_POST['password'])) {
    http_response_code(400); // Bad Request
    echo "Invalid Input";
    exit();
}

$id = $conn->real_escape_string($_POST['userId']);
$firstName = $conn->real_escape_string($_POST['firstName']);
$lastName = $conn->real_escape_string($_POST['lastName']);
$mobileNumber = $conn->real_escape_string($_POST['mobileNumber']);
$password = $conn->real_escape_string($_POST['password']);
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Check if the mobile number is already registered
$checkQuery = "SELECT * FROM users WHERE mobile_number = '$mobileNumber'";
$checkResult = $conn->query($checkQuery);

if ($checkResult->num_rows > 0) {
    http_response_code(409); // Conflict
    echo "mobile_exists";
} else {
    // Start a transaction to ensure both inserts succeed or fail together
    $conn->begin_transaction();

    try {
        // Insert into users table
        $userInsertQuery = "INSERT INTO users (id, first_name, last_name, mobile_number, password) 
                            VALUES ('$id', '$firstName', '$lastName', '$mobileNumber', '$hashedPassword')";
        $conn->query($userInsertQuery);

        // Insert into guardian table
        $guardianInsertQuery = "INSERT INTO guardian (user_id) VALUES ('$id')";
        $conn->query($guardianInsertQuery);

        // Commit the transaction if both inserts succeed
        $conn->commit();

        http_response_code(201); // Created
        echo "success";
    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        $conn->rollback();
        http_response_code(500); // Internal Server Error
        echo "error";
    }
}

$conn->close();
?>
