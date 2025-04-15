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

// Fetch user replies (assuming 'sender_id' is the user you're interested in)
$messagesQuery = $conn->prepare("SELECT m.reply_q1, m.reply_q2, m.comments, m.created_at, u.name AS receiver_name FROM messages m JOIN users u ON m.receiver_id = u.id WHERE m.sender_id = ?");
$messagesQuery->bind_param("i", $user_id);
$messagesQuery->execute();
$messagesResult = $messagesQuery->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Replies – Cherish</title>
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
    <h2 class="text-2xl font-semibold text-pink-600 mb-4">My Replies</h2>

    <?php if ($messagesResult->num_rows > 0): ?>
      <ul class="space-y-4">
        <?php while ($message = $messagesResult->fetch_assoc()): ?>
          <li class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-semibold text-pink-600">Reply to: <?= htmlspecialchars($message['receiver_name']) ?></h3>
            <p class="text-gray-600 mt-2"><strong>Reply Question 1:</strong> <?= htmlspecialchars($message['reply_q1']) ?></p>
            <p class="text-gray-600 mt-2"><strong>Reply Question 2:</strong> <?= htmlspecialchars($message['reply_q2']) ?></p>
            <p class="text-gray-600 mt-2"><strong>Comments:</strong> <?= htmlspecialchars($message['comments']) ?></p>
            <p class="text-gray-500 mt-2 text-sm">Sent on: <?= htmlspecialchars($message['created_at']) ?></p>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p class="text-gray-600">You have no replies yet.</p>
    <?php endif; ?>
  </main>

  <footer class="text-center text-sm text-gray-500 mt-10 mb-4">
    © 2025 Cherish. All rights reserved.
  </footer>

</body>
</html>