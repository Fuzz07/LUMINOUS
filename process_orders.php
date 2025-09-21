<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username']) || !isset($_POST['confirm_purchase'])) {
  header("Location: cart.php");
  exit();
}

$voucher = trim($_POST['voucher']);
$selected = $_SESSION['checkout_items'] ?? [];

if (empty($selected)) {
  echo "<p>No products selected.</p>";
  exit();
}

$ids = implode(',', array_map('intval', $selected));
$query = "SELECT * FROM products WHERE id IN ($ids)";
$result = $conn->query($query);

$total = 0;
$purchased_items = [];

while ($row = $result->fetch_assoc()) {
  $total += $row['price'];
  $purchased_items[] = $row;
}

// Voucher logic
$discount = 0;
if ($voucher === "SAVE10") {
  $discount = 0.10 * $total;
}

$final_total = $total - $discount;

// Delivery estimate: 3 to 5 days from now
$purchase_datetime = date('Y-m-d H:i:s');
$delivery_start_sql = date('Y-m-d', strtotime('+3 days'));
$delivery_end_sql = date('Y-m-d', strtotime('+5 days'));
$username = $_SESSION['username'];

// Get user_id from username
$userQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
$userQuery->bind_param("i", $username);
$userQuery->execute();
$userQuery->bind_result($user_id);
$userQuery->fetch();
$userQuery->close();

if (empty($user_id)) {
  echo "<p>Error: Unable to retrieve user ID. Please log in again.</p>";
  exit();
}

// Insert each item into the orders table
foreach ($purchased_items as $item) {
  $product_id = $item['id'];
  $product_name = $conn->real_escape_string($item['name']);
  $price = $item['price'];
  $quantity = 1;

  $stmt = $conn->prepare("INSERT INTO orders 
    ( user_id, username, product_id, product_name, price, quantity, voucher, discount, final_total, purchase_datetime, delivery_start, delivery_end) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param(
    "iisisssddsss",
    $user_id,
    $username,
    $product_id,
    $product_name,
    $price,
    $quantity,
    $voucher,
    $discount,
    $final_total,
    $purchase_datetime,
    $delivery_start_sql,
    $delivery_end_sql
  );

  $stmt->execute();
  $stmt->close();
}

// Set delivery dates for display
$generated_orders[] = [ 
  'product_name' => $item['name'],
  'price' => $item['price']
];

$delivery_start = $delivery_start_sql;
$delivery_end = $delivery_end_sql;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receipt</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main>
    <section class="receipt-section">
      <h2 class="section-title">🧾 Purchase Receipt</h2>

      <p><strong>Buyer:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
      <p><strong>Date:</strong> <?= $purchase_datetime ?></p>

      <ul class="receipt-list">
        <?php foreach ($purchased_items as $item): ?>
          <li>
            <?= htmlspecialchars($item['name']) ?> — ₱<?= number_format($item['price'], 2) ?>
          </li>
        <?php endforeach; ?>
      </ul>

      <p><strong>Subtotal:</strong> ₱<?= number_format($total, 2) ?></p>
      <?php if ($discount > 0): ?>
        <p><strong>Voucher:</strong> <?= htmlspecialchars($voucher) ?> — Discount: ₱<?= number_format($discount, 2) ?></p>
      <?php endif; ?>
      <p><strong>Total Paid:</strong> ₱<?= number_format($final_total, 2) ?></p>

      <p><strong>Estimated Delivery:</strong> Between <?= $delivery_start ?> and <?= $delivery_end ?></p>

      <a href="products.php" class="back-button">← Continue Shopping</a>
    </section>
  </main>
</body>
</html>