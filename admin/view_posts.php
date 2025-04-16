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
    <meta charset="UTF-8">
    <title>View Posts</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: rgb(249, 242, 244);
            color: #333;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f4b6cc;
            padding: 1rem 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .top-bar .logo h3 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #b30059;
        }

        .top-bar .nav a {
            margin-left: 1.5rem;
            text-decoration: none;
            color: white;
            font-weight: bold;
            background: #d94f7c;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .top-bar .nav a:hover {
            background: #ff85c1;
        }

        .content {
            padding: 2rem;
            overflow-x: auto;
        }

        h2 {
            margin-bottom: 1rem;
            color: #c2185b;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(214, 51, 132, 0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #f0c0d1;
            text-align: left;
        }

        th {
            background-color: #ff69b4;
            color: white;
        }

        td button {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        td button:hover {
            background-color: #ff80aa;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background: #f4b6cc;
            color: #a61e4d;
            font-size: 0.9rem;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .top-bar .nav {
                margin-top: 1rem;
            }

            .top-bar .nav a {
                margin: 0.3rem 0;
                display: inline-block;
            }

            .content {
                padding: 1rem;
            }

            table {
                font-size: 0.9rem;
            }

            td, th {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="logo"><h3>Cherish</h3></div>
    <div class="nav">
        <a href="admin_dashboard.php">Home</a>
        <a href="view_users.php">View Users</a>
        <a href="view_posts.php">View/Delete Posts</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="content">
    <h2>User Posts</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Title</th>
            <th>Traits</th>
            <th>Lifestyle</th>
            <th>Q1</th>
            <th>Q2</th>
            <th>Posted On</th>
            <th>Action</th>
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
                <form action="delete_post.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<footer>
    &copy; <?= date("Y") ?> Cherish Admin Panel. All rights reserved.
</footer>

</body>
</html>

<?php $conn->close(); ?>
