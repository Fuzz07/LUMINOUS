<?php
include 'includes/db.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT * FROM products WHERE 1";

if ($search) {
  $search = $conn->real_escape_string($search);
  $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}

if ($category) {
  $category = $conn->real_escape_string($category);
  $query .= " AND category = '$category'";
}

$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jewelry Collection</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/main.js" defer></script>
</head>

<body>

<?php if (isset($_SESSION['username'])): ?>
  <a href="account.php" class="back-button">← Back to Home</a>
<?php else: ?>
  <a href="index.php" class="back-button">← Back to Home</a>
<?php endif; ?>


  <main>
    <section class="collection-section">
      <h2 class="section-title">Our Collection</h2>

      <!-- Filters -->
      <div class="filters">
        <form method="GET" action="products.php" class="search-form">
          <input type="text" name="search" placeholder="Search jewelry..." value="<?php echo htmlspecialchars($search); ?>">
          <button type="submit">🔍 Search</button>
        </form>

        <form method="GET" action="products.php" class="category-form">
          <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php
            $catQuery = "SELECT DISTINCT category FROM products";
            $catResult = $conn->query($catQuery);
            while ($catRow = $catResult->fetch_assoc()) {
              $cat = htmlspecialchars($catRow['category']);
              $selected = ($category === $cat) ? 'selected' : '';
              echo "<option value='$cat' $selected>$cat</option>";
            }
            ?>
          </select>
        </form>
      </div>

      <!-- Product Grid -->
      <div class="product-grid">
        <?php
        while ($row = $result->fetch_assoc()) {
          echo "<article class='product-card'>";
          echo "<figure>";
          echo "<img src='assets/images/products/{$row['image']}' alt='" . htmlspecialchars($row['name']) . "'>";
          echo "<figcaption>";
          echo "<h3 class='product-name'>" . htmlspecialchars($row['name']) . "</h3>";
          echo "<p class='product-description'>" . htmlspecialchars($row['description']) . "</p>";
          echo "<span class='product-price'>₱" . number_format($row['price'], 2) . "</span>";
          
          echo "<div class='cart-controls'>";
          echo "<h4>Quantity:</h4>";
          echo "<input type='number' id='qty-{$row['id']}' min='1' value='1' class='quantity-input'>";
          echo "<button class='cart-button' onclick='addToCart({$row['id']})'>Add to Cart</button>";
          echo "</div>";

          echo "</figcaption>";
          echo "</figure>";
          echo "</article>";
        }
        ?>
      </div>
    </section>
  </main>

</body>
</html>