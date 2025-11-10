<!DOCTYPE html>
<html>
<head>
    <title></title> 
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-box">
    <h1>Register</h1>

    <!-- Normal Login -->
    <form action="register_val.php" method="POST">
        <input type="text" name="username" placeholder="Enter your name" required minlength="4" maxlength="20">
        <input type="password" name="password" placeholder="Enter your password" required minlength="4" maxlength="20">
        <input type="password" name="confirm_password" placeholder="Confirm your password" required minlength="4" maxlength="20"> 
        <button type="submit">register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
