<?php
include 'db.php';

/*
|--------------------------------------------------------------------------
| ADD OR UPDATE PRODUCT (AJAX POST)
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id       = $_POST['id'] ?? '';
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $price    = (float) $_POST['price'];
    $stock    = (int) $_POST['stock'];
    $mrp    = (int) $_POST['mrp'];

    $category = mysqli_real_escape_string($conn, $_POST['category']);

    if ($id) {
        // UPDATE
        mysqli_query($conn, "
            UPDATE products 
            SET name='$name',
                price='$price',
                mrp='$mrp',
                stock='$stock',
                category='$category'
            WHERE id=$id
        ");
    } else {
        // INSERT
        mysqli_query($conn, "
            INSERT INTO products (name, mrp,price, stock, category)
            VALUES ('$name','$mrp', '$price', '$stock', '$category')
        ");
    }

    exit; // Important for AJAX
}

/*
|--------------------------------------------------------------------------
| LIST PRODUCTS + LIVE SEARCH (AJAX GET)
|--------------------------------------------------------------------------
*/
$q = $_GET['q'] ?? '';
$q = mysqli_real_escape_string($conn, $q);

$sql = "SELECT * FROM products";
if ($q !== '') {
    $sql .= " WHERE name LIKE '%$q%' OR category LIKE '%$q%'";
}

$result = mysqli_query($conn, $sql);

while ($p = mysqli_fetch_assoc($result)):
?>
<form class="row" id="f<?= $p['id'] ?>">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">

    <div class="form-group">
        <label>Product Name</label>
        <input name="name" value="<?= htmlspecialchars($p['name']) ?>">
    </div>

    <div class="form-group">
        <label>Price</label>
        <input name="price" type="number" value="<?= $p['price'] ?>" style="width:90px">
    </div>
    <div class="form-group">
        <label>Mrp</label>
        <input name="mrp" type="number" value="<?= $p['mrp'] ?>" style="width:90px">
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input name="stock" type="number" value="<?= $p['stock'] ?>" style="width:90px">
    </div>

    <div class="form-group">
        <label>Category</label>
        <input name="category" value="<?= htmlspecialchars($p['category']) ?>" style="width:110px">
    </div>

    <button type="button" class="btn btn-del" onclick="del(<?= $p['id'] ?>)">
        Delete
    </button>
</form>
<?php endwhile; ?>
