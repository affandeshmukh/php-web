<?php
include 'db.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$search = $_GET['q'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

$category = $_GET['category'] ?? '';
$category = mysqli_real_escape_string($conn, $category);

// $sql = "SELECT * FROM products";

$sql = "SELECT * FROM products WHERE 1=1";


if ($search !== '') {
    $sql .= " AND name LIKE '%$search%'";
}

if ($category !== '') {
    $sql .= " AND category = '$category'";
}

$result = mysqli_query($conn, $sql);?>
<div class="product-container">
<?php
while ($p = mysqli_fetch_assoc($result)): ?>
    <div class="product-row" >
        <div>
            <div style="font-weight: bold;"><?= htmlspecialchars($p['name']) ?></div>
            <div style="color: #000; font-size: 16px;">
                Stock: <?= $p['stock'] ?> |  MRP ₹<?= $p['mrp'] ?>  | Price ₹<?= $p['price'] ?> | <?= $p['category'] ?> |
            </div>
        </div>
<?php if($p['stock'] >= 1){ ?>
 <button class="add-btn update-btn"onclick="addToCart(<?= $p['id'] ?>)" >Add to cart  </button>
 <?php }else{ ?>

  <button class="add-btn update-btn disabled"onclick="addToCart(<?= $p['id'] ?>)" disabled> out of stock </button>
<?php } ?>
   
  
    </div>
<?php endwhile; ?>
    </div>
