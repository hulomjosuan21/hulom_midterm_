<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type']; // E.g., 'user' or 'admin'
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $city = $_POST['city'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $userCRUD = new UserCRUD();
        try {
            $userCRUD->createUser($email, $username, $password, $user_type, $first_name, $last_name, $phone_number, $address_line1, $address_line2, $city);
            $success_message = "Registration successful! You can now log in.";
        } catch (Exception $e) {
            $error_message = "An error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
</head>

<body class="register-body">
    <div class="register-container">
        <h2>Create an Account</h2>

        <?php if (!empty($error_message)) : ?>
        <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)) : ?>
        <p class="success-message"><?= htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="input-wrapper">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <label for="user_type">User Type:</label>
                    <select id="user_type" name="user_type" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" required>
                    <label for="address_line1">Address Line 1:</label>
                    <input type="text" id="address_line1" name="address_line1" required>
                    <label for="address_line2">Address Line 2:</label>
                    <input type="text" id="address_line2" name="address_line2">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                    <button type="submit" class="register-button">Register</button>
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                </div>
            </div>
        </form>

    </div>
</body>

</html>