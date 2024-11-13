<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new Connection();
    $userCRUD = new UserCRUD();
    $user = $userCRUD->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user_type'] = $user->user_type;
        if ($user->user_type == 'admin') {
            header("Location: admin.php");
        } else {

            header("Location: index.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>

<body class="login-body">
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error_message)) : ?>
        <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="login-button">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up here</a>.</p>
    </div>
</body>

</html>