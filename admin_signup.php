<?php
// admin_signup.php

session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: view_members.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new admin into the database
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $result = $stmt->execute(['username' => $username, 'password' => $hashed_password]);

    if ($result) {
        // Redirect to the login page after successful signup
        header('Location: admin_login.php');
        exit();
    } else {
        $error = 'Signup failed. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="./css/base.css">
</head>
<body>
  <div class="forms_all">
    <div class="forms">
      <h2>Admin Signup</h2>
    </div>
    <form action="admin_signup.php" method="POST">
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <div class="forms">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="forms">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="forms">
            <button type="submit">Signup</button>
        </div>
    </form>
    <div class="forms">
        <p>Already have an account? <a href="admin_login.php">Login here</a></p>
    </div>
  </div>
</body>
</html>
