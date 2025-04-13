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

// Handle delete action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM posts WHERE id = $delete_id AND user_id = $user_id");
    header("Location: my_posts.php");
    exit();
}

// Fetch posts
$result = $conn->query("SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Posts</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            padding: 2rem;
        }
        .post {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255, 192, 203, 0.2);
            margin-bottom: 1.5rem;
        }
        .post h3 {
            color: #d63384;
        }
        .delete-btn {
            color: white;
            background: #ff4d6d;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
        }
        .delete-btn:hover {
            background: #ff6f91;
        }
    </style>
</head>
<body>
    <h2>My Posts</h2>
    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="post">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p><strong>Traits:</strong> <?= nl2br(htmlspecialchars($post['traits'])) ?></p>
            <p><strong>Lifestyle:</strong> <?= nl2br(htmlspecialchars($post['lifestyle'])) ?></p>
            <?php if ($post['fun_question1']): ?>
                <p><strong>Fun Q1:</strong> <?= htmlspecialchars($post['fun_question1']) ?></p>
            <?php endif; ?>
            <?php if ($post['fun_question2']): ?>
                <p><strong>Fun Q2:</strong> <?= htmlspecialchars($post['fun_question2']) ?></p>
            <?php endif; ?>
            <a class="delete-btn" href="?delete=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
        </div>
    <?php endwhile; ?>
</body>
</html>
