<?php
session_start(); // Start session
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit();
}

$email = $conn->real_escape_string($data->email);
$password = $conn->real_escape_string($data->password);

// Query user
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // If password stored as plain text:
    if ($user['password'] === $password) {
        // Store user ID in session
        $_SESSION['user_id'] = $user['id'];
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful!',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No user found with this email.']);
}

$conn->close();
?>
