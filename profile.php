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

// Get user ID from the query string
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Fetch the user's profile details
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    if ($result->num_rows == 0) {
        die("User not found.");
    }

    $user = $result->fetch_assoc();
} else {
    die("No user ID provided.");
}

// Fetch all posts of this user
$posts_result = $conn->query("SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user['name']) ?>'s Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            padding: 2rem;
            margin: 0;
        }
        .profile-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .profile-container h2 {
            color: #d63384;
            text-align: center;
        }
        .profile-container p {
            font-size: 16px;
            color: #555;
        }
        .post {
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .post h3 {
            color: #d63384;
            margin: 0;
        }
        .back-btn {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2><?= htmlspecialchars($user['name']) ?>'s Profile</h2>
        
        <!-- No profile picture for now -->
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        
        <!-- Handle missing bio -->
        <p><strong>Bio:</strong> <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : 'No bio available.' ?></p>

        <!-- Display the user's posts -->
        <h3>Posts by <?= htmlspecialchars($user['name']) ?>:</h3>
        <?php while ($post = $posts_result->fetch_assoc()): ?>
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
            </div>
        <?php endwhile; ?>

        <a href="browse_post.php" class="back-btn">Back to Posts</a>
    </div>
</body>
</html>
