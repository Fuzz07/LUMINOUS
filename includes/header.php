<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>luminous</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page-layout">
    <header class="site-header">
      <div class="logo">
        <h1>LUMINOUS</h1>  
      </div>
      <nav class="main-nav">
        <ul>
  
    <?php if (isset($_SESSION['username'])): ?>
       <li><a href="account.php">Home</a></li>
          <li><a href="products.php">Products</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
     <li></li> <li><a href="account.php">Account</a></li>
      <li><a href="log_out.php">Logout</a></li>    
    <?php else: ?>
       <li><a href="index.php">Home</a></li>
          <li><a href="products.php">Products</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php">Register</a></li>
    <?php endif; ?>

    <li><a href="cart.php" class="cart-link">🛒 Cart</a></li>
        </ul>
      </nav>
    </header>
  </div>
</body>
</html>