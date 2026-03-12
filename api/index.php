<?php session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION['user_id'];
 ?>
<!DOCTYPE html>
<html>
<head>
    <title> System</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="https://affandeshmukh.github.io/json/product.json">

</head>
<body>
 <div class="layout">
    <div class="card products-container" style="display:flex; flex-direction:column; padding:15px;">
<div class="button-group">
      
    <button class="admin-badge" onclick="window.location.href='admin/'">
        Admin
    </button>

 
    <button class="admin-badge" onclick="window.location.href='history.php'"> history</button>

    <button class="admin-badge" onclick="window.location.href='logout.php'">
        Logout
    </button>
</div>

<div id="cart-msg" style="
    position: fixed;
    top: 20px;
    left: 20px;
    background: #ff9800;
    color: #fff;
    padding: 10px 15px;
    border-radius: 5px;
    display: none;
    z-index: 9999;
    font-size: 14px;
">
</div>


    <h2 style="margin: 15px 0 5px 0;">Products</h2>

    <div style="display:flex; gap:20px; align-items:center; margin-bottom:15px;">
        <!-- Search -->
        <input type="text" id="search" placeholder="Search product..."
               onkeyup="filterProducts()">


        <!-- Category buttons 
        <div id="category-filters" style="display:flex; gap:10px; align-items:center;">
            <button class="filter-btn" onclick="setCategoryFilter('Pizza')">Pizza</button>
            <button class="filter-btn" onclick="setCategoryFilter('Milk')">Milk</button>
            <button class="filter-btn" onclick="setCategoryFilter('')">All</button>
        </div>-->
    </div>

    <div id="products-list" ></div>
</div>

    <div class="card cart-container">
        <div id="cart-display">
            </div>
    </div>
<div id="ist"></div>
</div>

    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <?php require 'footer.php'; ?>
</body>
</html>