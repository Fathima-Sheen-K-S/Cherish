<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the post from the database
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect to the posts view page after successful deletion
        header("Location: view_posts.php");
        exit();
    } else {
        echo "Error deleting the post.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
