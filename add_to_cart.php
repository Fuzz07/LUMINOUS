<?php
session_start();
include 'includes/db.php';

file_put_contents('debug.txt', print_r($_POST, true));


$productId = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if ($productId) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = $quantity;
  }

  echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'No product ID received']);
}
?>