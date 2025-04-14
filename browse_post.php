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

// Check login
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in.");
}

$user_id = $_SESSION['user_id'];

// Fetch others' posts
$result = $conn->query("SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id WHERE posts.user_id != $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Posts</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            padding: 2rem;
            margin: 0;
        }

        header {
            background-color: white;
            padding: 10px;
            border-bottom: 2px solid #f8c5d9;
        }

        header h1 {
            color: #d63384;
            margin: 0;
            font-size: 24px;
            display: inline-block;
        }

        nav {
            float: right;
        }

        nav a {
            color: #555;
            text-decoration: none;
            padding: 0 15px;
            font-size: 14px;
        }

        nav a:hover {
            color: #d63384;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #d63384;
            margin: 20px 0;
        }

        .post {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .post h3 {
            color: #d63384;
            margin: 0;
        }

        .post p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cherish</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="post.html">Create Post</a>
            <a href="my_posts.php">My Posts</a>
            <a href="browse_post.php">Browse Posts</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <h2>Browse Others' Posts</h2>

    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="post">
            <h3><?= htmlspecialchars($post['title']) ?> <span style="font-size: 14px; color: #888;">by <?= htmlspecialchars($post['name']) ?></span></h3>
            <p><strong>Traits:</strong> <?= nl2br(htmlspecialchars($post['traits'])) ?></p>
            <p><strong>Lifestyle:</strong> <?= nl2br(htmlspecialchars($post['lifestyle'])) ?></p>
            <?php if ($post['fun_question1']): ?>
                <p><strong>Fun Q1:</strong> <?= htmlspecialchars($post['fun_question1']) ?></p>
            <?php endif; ?>
            <?php if ($post['fun_question2']): ?>
                <p><strong>Fun Q2:</strong> <?= htmlspecialchars($post['fun_question2']) ?></p>
                <form method="POST" action="apply.php" style="margin-top: 10px;">
    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
    <button type="submit" style="
        padding: 8px 16px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    ">Apply</button>
</form>

            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</body>
</html>
