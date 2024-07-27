<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: view_members.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the admin from the database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: view_members.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./css/base.css">
</head>
<body>
  <div class="forms_all">
    <div class="forms">
      <h2>Admin Login</h2>
    </div>
    <form action="admin_login.php" method="POST">
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
            <button type="submit">Login</button>
        </div>
    </form>
  </div>
</body>
</html>
