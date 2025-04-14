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

// Fetch post details for editing
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM posts WHERE id = $post_id AND user_id = $user_id");
    
    if ($result->num_rows == 0) {
        die("Post not found or you do not have permission to edit this post.");
    }

    $post = $result->fetch_assoc();
} else {
    die("No post ID provided.");
}

// Handle form submission for updating post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $traits = htmlspecialchars($_POST['traits']);
    $lifestyle = htmlspecialchars($_POST['lifestyle']);
    $fun_question1 = isset($_POST['fun_question1']) ? htmlspecialchars($_POST['fun_question1']) : null;
    $fun_question2 = isset($_POST['fun_question2']) ? htmlspecialchars($_POST['fun_question2']) : null;

    $update_query = "UPDATE posts SET title = '$title', traits = '$traits', lifestyle = '$lifestyle', fun_question1 = '$fun_question1', fun_question2 = '$fun_question2' WHERE id = $post_id AND user_id = $user_id";
    
    if ($conn->query($update_query) === TRUE) {
        header("Location: my_posts.php");
        exit();
    } else {
        echo "Error updating post: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            padding: 2rem;
        }
        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255, 192, 203, 0.2);
        }
        .form-container h2 {
            color: #d63384;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background: #6f42c1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #7b4dd8;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Post</h2>
        <form method="POST">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

            <label for="traits">Traits</label>
            <textarea id="traits" name="traits" required><?= htmlspecialchars($post['traits']) ?></textarea>

            <label for="lifestyle">Lifestyle</label>
            <textarea id="lifestyle" name="lifestyle" required><?= htmlspecialchars($post['lifestyle']) ?></textarea>

            <label for="fun_question1">Fun Question 1</label>
            <textarea id="fun_question1" name="fun_question1"><?= htmlspecialchars($post['fun_question1']) ?></textarea>

            <label for="fun_question2">Fun Question 2</label>
            <textarea id="fun_question2" name="fun_question2"><?= htmlspecialchars($post['fun_question2']) ?></textarea>

            <button type="submit" class="submit-btn">Update Post</button>
        </form>
    </div>
</body>
</html>
