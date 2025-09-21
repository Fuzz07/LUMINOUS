<?php
session_start();
include 'includes/db.php';

$cart_items = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <a href="index.php" class="back-button">← Back to Home</a>


   <main>
    <section class="cart-section">
      <h2 class="section-title">🛒 Your Cart</h2>

      <?php if (empty($cart_items)): ?>
        <p class="empty-cart-msg">Your cart is empty.</p>
      <?php else: ?>
        <form method="post" action="buy.php">
          <div class="cart-grid">
            <?php
            $ids = implode(',', array_map('intval', array_keys($cart_items)));
            $query = "SELECT * FROM products WHERE id IN ($ids)";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
              $productId = $row['id'];
              $quantity = $cart_items[$productId];

              echo "<article class='cart-card'>";
              echo "<label class='cart-select'>";
              echo "<input type='checkbox' name='selected_products[]' value='{$productId}'>";
              echo "<div class='cart-details'>";
              echo "<img src='assets/images/products/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
              echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
              echo "<p>₱" . number_format($row['price'], 2) . "</p>";
              echo "<label>Qty: <input type='number' name='quantities[{$productId}]' value='{$quantity}' min='1'></label>";
              echo "</div>";
              echo "</label>";
              echo "</article>";
            }
            ?>
          </div>


         <form method="post" action="buy.php">
  ...
  <div class="cart-summary" action="buy.php">
    <button type="submit" name="checkout" class="checkout-button">Buy Selected</button>
  </div>
</form>
        </form>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>