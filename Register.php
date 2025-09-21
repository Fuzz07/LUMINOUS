<?php
include 'includes/db.php';
session_start();

$success = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Username validation
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters.";
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (strlen($password) < 8 || !ctype_alnum($password)) {
    $errors[] = "Password must be at least 8 characters and contain only letters and numbers.";
  }

  // Confirm password match
  if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Proceed only if all validations pass
  if (empty($errors)) {
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $errors[] = "Username or email already exists.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      $stmt->execute();
      $success = "Registration successful!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/main.js" defer></script>
</head>
<body>
  <a href="index.php" class="back-button">← Back to Home</a>
  <main>
    <section class="form-section">
      <h2 class="form-title">Create Your Account</h2>

    <?php if ($success): ?>
  <p class="success-msg"><?= $success ?></p>
<?php elseif (!empty($errors)): ?>
  <?php foreach ($errors as $err): ?>
    <p class="error-msg"><?= $err ?></p>
  <?php endforeach; ?>
<?php endif; ?>

      <form class="form-card" method="post" action="register.php">
        <div class="form-group">
          <input type="text" name="username" placeholder=" " required>
          <label for="username">Username</label>
        </div>

        <div class="form-group">
          <input type="email" name="email" placeholder=" " required>
          <label for="email">Email</label>
          <span id="emailError" style="color: red; font-size: 0.9em;"></span>
        </div>

        <div class="form-group">
          <input type="password" name="password" placeholder=" " required>
          <label for="password">Password</label>
        </div>

        <div class="form-group">
          <input type="password" name="confirm_password" placeholder=" " required>
          <label for="confirm_password">Confirm Password</label>
        </div>

        <button type="submit" name="register">Register</button>
      </form>
    </section>
  </main>
</body>
</html>