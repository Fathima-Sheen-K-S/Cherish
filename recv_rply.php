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

$user_id = $_SESSION['user_id'];  // Get the logged-in user ID

// Fetch the replies the user has received
$query = "
    SELECT m.id, m.sender_id, m.receiver_id, m.reply_q1, m.reply_q2, m.comments, m.created_at, 
           u.name AS sender_name, u2.name AS receiver_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    JOIN users u2 ON m.receiver_id = u2.id
    WHERE m.receiver_id = ?
    ORDER BY m.created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Replies I Got – Cherish</title>
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
        <a href="view_reply.php" class="text-gray-700 hover:text-pink-600">My Replies</a>
        <a href="replies_received.php" class="text-gray-700 hover:text-pink-600">Replies I Got</a>
        <a href="logout.php" class="text-gray-500 hover:text-pink-400">Logout</a>
    </nav>
</header>

<!-- Content -->
<main class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold text-pink-600 mb-4">Replies You Got</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($reply = $result->fetch_assoc()): ?>
            <div class="mb-6 p-4 bg-gray-50 border border-pink-200 rounded-lg">
                <p class="text-gray-800 font-semibold mb-1">
                    <?= htmlspecialchars($reply['sender_name']) ?> replied to you:
                </p>
                <div class="text-gray-700 mb-3">
                    <p><strong>Reply to Q1:</strong> <?= nl2br(htmlspecialchars($reply['reply_q1'])) ?></p>
                    <p><strong>Reply to Q2:</strong> <?= nl2br(htmlspecialchars($reply['reply_q2'])) ?></p>
                    <p><strong>Comments:</strong> <?= nl2br(htmlspecialchars($reply['comments'])) ?></p>
                </div>
                <p class="text-gray-500 text-sm mb-3">
                    Sent on <?= date("F j, Y, g:i a", strtotime($reply['created_at'])) ?>
                </p>

                <!-- Accept & Reject Buttons -->
                <div class="flex gap-3">
                    <form action="handle_reply_action.php" method="post">
                        <input type="hidden" name="message_id" value="<?= $reply['id'] ?>">
                        <input type="hidden" name="action" value="accept">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Accept
                        </button>
                    </form>
                    <form action="handle_reply_action.php" method="post">
                        <input type="hidden" name="message_id" value="<?= $reply['id'] ?>">
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-gray-600">No replies found.</p>
    <?php endif; ?>
</main>

<footer class="text-center text-sm text-gray-500 mt-10 mb-4">
    © 2025 Cherish. All rights reserved.
</footer>

</body>
</html>
