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

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users</title>
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
            background-color:rgb(249, 242, 244); /* pastel pink background */
            color: #333;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color:  #f4b6cc;
            padding: 1rem 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .top-bar .logo h3 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #b30059; /* darker pink */
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
            flex: 1;
            padding: 2rem;
        }

        h2 {
            color: #d63384;
            margin-bottom: 1rem;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(214, 51, 132, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0c7da;
            text-align: center;
        }

        th {
            background-color: #ff69b4;
            color: white;
        }

        tr:hover {
            background-color: #ffe6f0;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background: #f4b6cc;
            color: #a61e4d;
            font-size: 0.9rem;
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

            table {
                font-size: 0.9rem;
            }

            .content {
                padding: 1rem;
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
    <h2>Registered Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Looking For</th>
            <th>Age Range</th>
            <th>Created At</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['looking_for'] ?></td>
                <td><?= $row['looking_for_age_min'] . " - " . $row['looking_for_age_max'] ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<footer>
    &copy; <?= date("Y") ?> Cherish Admin Panel. All rights reserved.
</footer>

</body>
</html>

<?php
$conn->close();
?>
