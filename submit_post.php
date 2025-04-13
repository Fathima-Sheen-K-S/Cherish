<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in.");
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$traits = $_POST['traits'];
$lifestyle = $_POST['lifestyle'];
$question1 = $_POST['question1'] ?? null;
$question2 = $_POST['question2'] ?? null;

// Prepare and insert
$stmt = $conn->prepare("INSERT INTO posts (user_id, title, traits, lifestyle, fun_question1, fun_question2) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $user_id, $title, $traits, $lifestyle, $question1, $question2);

if ($stmt->execute()) {
    header("Location: my_posts.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
