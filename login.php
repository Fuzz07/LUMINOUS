<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $username = trim($_POST['username']);
  $password = $_POST['password'];


  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['username'] = $user['username'];
  $_SESSION['user_role'] = $user['role'];
  header("Location: account.php");
  exit();
}
else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  
  <main>
    <section class="form-section">
      <h2 class="form-title">Log In</h2>

      <?php if ($error): ?>
        <p class="error-msg"><?= $error ?></p>
      <?php endif; ?>

      <form class="form-card" method="post" action="login.php">
        <div class="form-group">
          <input type="text" name="username" placeholder=" " required>
          <label for="username">Username</label>
        </div>

        <div class="form-group">
          <input type="password" name="password" placeholder=" " required>
          <label for="password">Password</label>
        </div>
        
        <button type="submit" name="login">Login</button>
        <div class="form-links">
  <a href="index.php" class="back-button">Home</a>
  <a href="register.php" class="register">Sign Up</a>
</div>

      </form>
    </section>
  </main>
</body>
</html>