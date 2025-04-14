<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in.");
}

$sender_id = $_SESSION['user_id'];

// Ensure receiver ID is set (this is the profile owner)
if (!isset($_GET['user_id'])) {
    die("No user ID specified.");
}
$receiver_id = intval($_GET['user_id']);

// Fetch receiver info (to show name)
$receiver = $conn->query("SELECT name FROM users WHERE id = $receiver_id")->fetch_assoc();
if (!$receiver) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_q1 = $conn->real_escape_string($_POST['reply_q1']);
    $reply_q2 = $conn->real_escape_string($_POST['reply_q2']);
    $comments = $conn->real_escape_string($_POST['comments']);

    $sql = "INSERT INTO messages (sender_id, receiver_id, reply_q1, reply_q2, comments)
            VALUES ($sender_id, $receiver_id, '$reply_q1', '$reply_q2', '$comments')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!'); window.location.href = 'browse_post.php';</script>";
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message <?= htmlspecialchars($receiver['name']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f8ff;
            padding: 2rem;
        }
        .message-form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        .message-form h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        .submit-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="message-form">
        <h2>Reply to <?= htmlspecialchars($receiver['name']) ?></h2>
        <form method="POST">
            <label for="reply_q1">Your answer to Fun Q1:</label>
            <textarea name="reply_q1" id="reply_q1" rows="3" required></textarea>

            <label for="reply_q2">Your answer to Fun Q2:</label>
            <textarea name="reply_q2" id="reply_q2" rows="3" required></textarea>

            <label for="comments">Anything else about you:</label>
            <textarea name="comments" id="comments" rows="4"></textarea>

            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
</body>
</html>
