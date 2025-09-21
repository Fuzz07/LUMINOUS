<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$cart_items = $_SESSION['cart'] ?? [];

$total = 0;
$products = [];

if (!empty($cart_items)) {
  $ids = implode(',', array_map('intval', $cart_items));
  $query = "SELECT * FROM products WHERE id IN ($ids)";
  $result = $conn->query($query);

  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
    $total += $row['price'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <a href="index.php" class="back-button">← Back to Home</a>

  <main>
    <section class="checkout-section">
      <h2 class="section-title">🧾 Checkout</h2>

      <?php if (empty($products)): ?>
        <p class="empty-cart-msg">Your cart is empty.</p>
      <?php else: ?>
        <ul class="checkout-list">
          <?php foreach ($products as $product): ?>
            <li>
              <strong><?= htmlspecialchars($product['name']) ?></strong> — ₱<?= number_format($product['price'], 2) ?>
            </li>
          <?php endforeach; ?>
        </ul>

        <p class="checkout-total">Total: <strong>₱<?= number_format($total, 2) ?></strong></p>

        <form method="post" action="buy.php">
          <button type="submit" name="checkout" class="checkout-button">Buy Now</button>
        </form>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>