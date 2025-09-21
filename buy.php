<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username']) || !isset($_POST['selected_products'])) {
  header("Location: cart.php");
  exit();
}

$selected = $_POST['selected_products'];
$_SESSION['checkout_items'] = $selected;

$ids = implode(',', array_map('intval', $selected));
$query = "SELECT * FROM products WHERE id IN ($ids)";
$result = $conn->query($query);

$products = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
  $products[] = $row;
  $total += $row['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Purchase</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main>
    <section class="checkout-section">
      <h2 class="section-title">🧾 Confirm Your Purchase</h2>

      <ul class="checkout-list">
        <?php foreach ($products as $product): ?>
          <li>
            <strong><?= htmlspecialchars($product['name']) ?></strong> — ₱<?= number_format($product['price'], 2) ?>
          </li>
        <?php endforeach; ?>
      </ul>

      <p class="checkout-total">Subtotal: <strong>₱<?= number_format($total, 2) ?></strong></p>

      <form method="post" action="process_orders.php">
        <label for="voucher">Voucher Code:</label>
        <input type="text" name="voucher" placeholder="Enter voucher if any">
        <button type="submit" name="confirm_purchase" class="checkout-button">Confirm & Pay</button>
      </form>
    </section>
  </main>
</body>
</html>