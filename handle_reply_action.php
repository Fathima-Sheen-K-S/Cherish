<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = $_POST['message_id'];
    $action = $_POST['action']; // accept or reject

    $status = ($action === 'accept') ? 'Accepted' : 'Rejected';

    // Update the message status
    $stmt = $conn->prepare("UPDATE messages SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $message_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to replies_received
    header("Location: recv_rply.php");
    exit;
}
?>
