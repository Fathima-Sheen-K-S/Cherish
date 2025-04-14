<?php
session_start();
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

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit();
}

$email = $conn->real_escape_string($data->email);
$password_input = $conn->real_escape_string($data->password);

// Admin login
$sql_admin = "SELECT * FROM admin WHERE email = '$email'";
$result_admin = $conn->query($sql_admin);

if ($result_admin->num_rows > 0) {
    $admin = $result_admin->fetch_assoc();
    if ($admin['password'] === $password_input) { // Use hashed passwords in production!
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['role'] = 'admin';
        echo json_encode([
            'status' => 'success',
            'message' => 'Admin login successful!',
            'redirect' => 'admin/admin_dashboard.php',
            'user' => [
                'id' => $admin['id'],
                'email' => $admin['email'],
                'role' => 'admin'
            ]
        ]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid admin password.']);
        exit();
    }
}

// User login
$sql_user = "SELECT * FROM users WHERE email = '$email'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    if ($user['password'] === $password_input) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'user';
        echo json_encode([
            'status' => 'success',
            'message' => 'User login successful!',
            'redirect' => 'view.php',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => 'user'
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
