<?php
session_start();

// Get POST data
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

$dsn = "mysql:host=localhost;dbname=rgmq;charset=utf8mb4";
$db_user = "root";    // XAMPP default
$db_pass = "";        // XAMPP default
$pdo = new PDO($dsn, $db_user, $db_pass);

$stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($username === 'Guest') {
    $_SESSION['username'] = 'Guest';
    header("Location: menu.php");
    exit();
} elseif ($user && password_verify($password, $user['password'])) {
    // password is correct
    $_SESSION['username'] = $user['username'];
    header("Location: menu.php");
    exit();
} else {
    // invalid login
    echo "<h2>Invalid username or password</h2>";
    echo "<a href='login.php'>Try again</a>";
    exit();
}

function check_pass($pw, $row){
    $checkHash = hash('sha512', $row['salt'] . $pw);
    if (hash_equals($row['password_hash'], $checkHash)){
        return 1;
    }
    else{
        return 0;
    }
}

// Save username into session (only after validation)
// $_SESSION['username'] = $username;

//comment 4-31 uncomment 34

// Redirect to a welcome/protected page
header("Location: menu.php");
exit();
?>
