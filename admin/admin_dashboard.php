<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #fff0f5;
      margin: 0;
      padding: 2rem;
    }

    .dashboard {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(255, 105, 180, 0.2);
    }

    h1 {
      text-align: center;
      color: #d63384;
    }

    .nav {
      display: flex;
      justify-content: space-around;
      margin-top: 2rem;
    }

    .nav a {
      text-decoration: none;
      background: #ff69b4;
      color: white;
      padding: 1rem 2rem;
      border-radius: 10px;
      transition: background 0.3s;
    }

    .nav a:hover {
      background: #ff85c1;
    }

    .logout {
      text-align: center;
      margin-top: 3rem;
    }

    .logout a {
      color: #d63384;
      text-decoration: none;
    }

    .logout a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1>Welcome, Admin</h1>
    <div class="nav">
      <a href="view_users.php">View Registered Users</a>
      <a href="view_posts.php">View/Delete Posts</a>
    </div>
    <div class="logout">
      <p><a href="logout.php">Logout</a></p>
    </div>
  </div>
</body>
</html>
