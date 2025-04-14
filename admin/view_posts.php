<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Posts</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #ff69b4;
            color: white;
        }
    </style>
</head>
<body>
    <h2>User Posts</h2>
    <table>
        <tr>
            <th>ID</th><th>User</th><th>Title</th><th>Traits</th><th>Lifestyle</th>
            <th>Q1</th><th>Q2</th><th>Posted On</th><th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['traits'])) ?></td>
            <td><?= nl2br(htmlspecialchars($row['lifestyle'])) ?></td>
            <td><?= htmlspecialchars($row['fun_question1']) ?></td>
            <td><?= htmlspecialchars($row['fun_question2']) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <!-- Delete button -->
                <form action="delete_post.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
