<?php
session_start();

// Get form data
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

// Basic validation
if ($username === '' || $password === '' || $confirm === '') {
    die("All fields are required. <a href='register.php'>Go back</a>");
}
if ($password !== $confirm) {
    die("Passwords do not match. <a href='register.php'>Go back</a>");
}

// Connect to database
$dsn = "mysql:host=localhost;dbname=rgmq;charset=utf8mb4";
$pdo = new PDO($dsn, "root", "");

// Check if username already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    die("Username already taken. <a href='register.php'>Go back</a>");
}

// Hash the password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashed, "user"]);

// Optionally log in immediately
$_SESSION['username'] = $username;

// Redirect to menu or welcome page
header("Location: menu.php");
exit();
?>