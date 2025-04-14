<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user ID is in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];  // Make sure to use 'user_id'

// Fetch user data from 'users' table
$userQuery = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard – Cherish</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 font-sans">

  <!-- Navbar -->
  <header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-pink-600">Cherish</h1>
    <nav class="space-x-4 text-sm">
      <a href="view.php" class="text-pink-700 font-medium">Dashboard</a>
      <a href="post.html" class="text-gray-700 hover:text-pink-600">Create Post</a>
      <a href="my_posts.php" class="text-gray-700 hover:text-pink-600">My Posts</a>
      <a href="browse_post.php" class="text-gray-700 hover:text-pink-600">Browse Posts</a>
      <a href="logout.php" class="text-gray-500 hover:text-pink-400">Logout</a>
    </nav>
  </header>

  <!-- Content -->
  <main class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold text-pink-600 mb-2">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p class="text-gray-600 mb-4">Email: <?= htmlspecialchars($user['email']) ?></p>

    <div class="flex flex-col md:flex-row gap-4">
      <a href="post.html" class="bg-pink-600 text-white px-6 py-2 rounded-full hover:bg-pink-700 text-center">+ Create Post</a>
      <a href="my_posts.php" class="bg-white border border-pink-600 text-pink-600 px-6 py-2 rounded-full hover:bg-pink-50 text-center">View My Posts</a>
      <a href="view_reply.php" class="bg-white border border-pink-600 text-pink-600 px-6 py-2 rounded-full hover:bg-pink-50 text-center">My Replies</a>
    </div>
  </main>

  <footer class="text-center text-sm text-gray-500 mt-10 mb-4">
    © 2025 Cherish. All rights reserved.
  </footer>

</body>
</html>
