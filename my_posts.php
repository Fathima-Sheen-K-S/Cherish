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
            margin: 0;
            padding: 0;
        }

        /* Navbar styling */
        header {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #d63384;
            font-size: 24px;
            margin: 0;
        }

        nav a {
            margin-left: 15px;
            text-decoration: none;
            font-size: 14px;
            color: #555;
        }

        nav a:hover {
            color: #d63384;
        }

        nav a.active {
            color: #d63384;
            font-weight: bold;
        }

        .container {
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

        .delete-btn,
        .edit-btn {
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
        }

        .delete-btn {
            background: #ff4d6d;
        }

        .delete-btn:hover {
            background: #ff6f91;
        }

        .edit-btn {
            background: #6f42c1;
            margin-left: 10px;
        }

        .edit-btn:hover {
            background: #7b4dd8;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header>
        <h1>Cherish</h1>
        <nav>
            <a href="view.php">Dashboard</a>
            <a href="post.html">Create Post</a>
            <a href="my_posts.php" class="active">My Posts</a>
            <a href="browse_post.php">Browse Posts</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <!-- Page Content -->
    <div class="container">
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
                <a class="edit-btn" href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
