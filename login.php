<?php
header('Content-Type: application/json'); // Ensure the response is JSON

// Database connection (replace with your actual database credentials)
$servername = "localhost";  // Your MySQL server (usually localhost)
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "your_database";  // Your database name (replace with your actual database name)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

// Get raw POST data (since you're sending JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if email and password are provided
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email and password are required.',
    ]);
    exit();
}

// Sanitize input (for safety against SQL injection)
$email = $conn->real_escape_string($data->email);
$password = $conn->real_escape_string($data->password);

// Query to find the user by email
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password (assuming the password is stored in plain text)
    // For production, use password_hash() and password_verify() for better security
    if ($user['password'] === $password) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful!',
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid password.',
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No user found with this email.',
    ]);
}

// Close connection
$conn->close();
?>
