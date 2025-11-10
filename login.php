<!DOCTYPE html>
<html>
<head>
    <title>Login - RGMQ</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>


<div class="login-box">
    <!-- Logo -->
    <img src="" alt="Logo" class="logo">

    <h2>Login</h2>

    <!-- Login Form -->
    <form action="login_val.php" method="POST">
        <input type="text" name="username" placeholder="Enter your name" required minlength="3" maxlength="15">
        <input type="password" name="password" placeholder="Enter your password" required minlength="4" maxlength="20">
        <button type="submit" class="alt1">Login</button>
        <p> Don't have an account? <a href="register.php"> Register here </a></p>
    </form>

    <!-- Guest Button -->
    <form action="menu.php" method="POST">
        <input type="hidden" name="username" value="Guest">
        <input type="hidden" name="password" value="">
        <button type="submit" class="alt2">Play as Guest</button>
    </form>
</div>

</body>
</html>

