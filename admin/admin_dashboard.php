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
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff0f5;
      color: #333;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color:rgb(231, 224, 227);
      color: white;
      padding: 1rem 2rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .top-bar .logo {
      font-size: 1.8rem;
      font-weight: bold;
    }

    .top-bar .nav a {
      margin-left: 1.5rem;
      text-decoration: none;
      color: white;
      font-weight: bold;
      background:#9e4672;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .top-bar .nav a:hover {
      background: #ff85c1;
    }

    .image-container {
      position: relative;
      width: 100%;
      height: 100vh;
      background-image: url('bg.jpeg'); /* 🔄 Replace this with your actual image path */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: 1;
    }

    .welcome-box {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(255, 255, 255, 0.7);
      padding: 2rem;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 0 10px rgba(214, 51, 132, 0.2);
      z-index: 2;
    }

    .welcome-box h2 {
      color: #d63384;
      margin-bottom: 0.5rem;
    }

    footer {
      text-align: center;
      padding: 1rem;
      background: #f8d7e2;
      color: #a61e4d;
      font-size: 0.9rem;
      position: relative;
      z-index: 2;
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

      .welcome-box {
        width: 90%;
      }
    }
  </style>
</head>
<body>

  <div class="top-bar">
    <div class="logo"><h3 style="color:#a61e4d;">Cherish</h3></div>
    <div class="nav">
      <a href="dashboard.php">Home</a>
      <a href="view_users.php">View Users</a>
      <a href="view_posts.php">View/Delete Posts</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="image-container">
    <div class="welcome-box">
      <h2>Welcome, Admin</h2>
      <p>Use the menu above to manage your dashboard.</p>
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> Cherish Admin Panel. All rights reserved.
  </footer>

</body>
</html>
