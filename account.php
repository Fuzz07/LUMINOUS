<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'customer') {
  header("Location: login.php");
  exit();
}
include 'includes/db.php';
include 'includes/header.php';
?>

<main>
  <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
  <p>Browse our latest collection and start shopping.</p>

  <div class="product-grid">
    <?php
    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
    while ($row = $result->fetch_assoc()) {
      echo "<div class='product-card'>";
      echo "<img src='assets/images/products/{$row['image']}' alt='" . htmlspecialchars($row['name']) . "'>";
      echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
      echo "<p>" . htmlspecialchars($row['description']) . "</p>";
      echo "<span class='price'>₱" . number_format($row['price'], 2) . "</span>";
      echo "<form method='post' action='cart.php'>";
      echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
      echo "<button type='submit'>Add to Cart</button>";
      echo "</form>";
      echo "</div>";
    }
    ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>