<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$gender = $_POST['gender'];
$lookingFor = $_POST['lookingFor'];
$ageMin = $_POST['ageMin'];
$ageMax = $_POST['ageMax'];

// Check if email already exists
$checkQuery = $conn->prepare("SELECT email FROM users WHERE email = ?");
$checkQuery->bind_param("s", $email);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
  echo "Email already exists. Please try another one.";
} else {
  // Insert into DB
  $stmt = $conn->prepare("INSERT INTO users (name, email, password, gender, looking_for, looking_for_age_min, looking_for_age_max) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $email, $password, $gender, $lookingFor, $ageMin, $ageMax);

  if ($stmt->execute()) {
    header("Location: thankyou.html");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

$checkQuery->close();
$conn->close();
?>
