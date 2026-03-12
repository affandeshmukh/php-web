<?php
include 'db.php';
@session_start();
$session_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';


if ($action === 'remove') {
    $pid = (int)$_POST['id'];
    mysqli_query($conn,"
        DELETE FROM cart 
        WHERE session_id='$session_id' AND product_id=$pid
    ");
}


if ($action === 'add') {
    $pid = (int)$_POST['id'];

    $check = mysqli_query($conn, "
        SELECT id FROM cart 
        WHERE session_id='$session_id' AND product_id=$pid
    ");

    if (mysqli_num_rows($check)) {
        
        echo json_encode([
            'status' => 'exists',
            'message' => 'Product already in cart'
        ]);
    } else {
        
        mysqli_query($conn, "
            INSERT INTO cart (session_id, product_id, qty, qutys)
            VALUES ('$session_id', $pid, 1, 1)
        ");

        echo json_encode([
            'status' => 'added',
            'message' => 'Product added to cart'
        ]);
    }
    exit;
}


if ($action === 'update') {
    $pid = (int)$_POST['id'];
    $change = (int)$_POST['qty'];
    mysqli_query($conn, "UPDATE cart JOIN products ON cart.product_id = products.id SET cart.qty = LEAST(products.stock, GREATEST(1, cart.qty + $change)) WHERE cart.session_id='$session_id' AND cart.product_id=$pid");
    exit;
}

if ($action === 'updated') {
    $pid = (int)$_POST['id'];
    $change = (int)$_POST['qutys'];
    mysqli_query($conn, "UPDATE cart JOIN products ON cart.product_id = products.id SET cart.qutys = LEAST(products.qtys, GREATEST(1, cart.qutys + $change)) WHERE cart.session_id='$session_id' AND cart.product_id=$pid");
    exit;
}

// if ($action === 'final') {
//     $cartItems = mysqli_query($conn, "SELECT product_id, qty ,qutys FROM cart WHERE session_id='$session_id'");
//     while ($item = mysqli_fetch_assoc($cartItems)) {
//         $pid = $item['product_id']; $qty = $item['qty'];
//          $qutys = $item['qutys'];
//         mysqli_query($conn, "UPDATE products SET 
//         stock = stock - $qty
//      

        
//          WHERE id = $pid AND stock >= $qty ");
//     }
//     mysqli_query($conn, "DELETE FROM cart WHERE session_id='$session_id'");
//     exit;
// }


if ($action === 'final') {
    $cartItems = mysqli_query($conn, "SELECT product_id, qty, qutys FROM cart WHERE session_id='$session_id'");
    
    // Loop through each item in the cart
    while ($item = mysqli_fetch_assoc($cartItems)) {
        $pid = $item['product_id'];
        $qty = $item['qty'];
        $qutys = $item['qutys'];
        
        // Get product name and price from products table
        $productResult = mysqli_query($conn, "SELECT name, mrp,price FROM products WHERE id = $pid");
        $product = mysqli_fetch_assoc($productResult);
        $productName = $product['name'];
        $price = $product['price'];
        $mrp = $product['mrp'];
        
        
        // Insert into history table
        $insertHistoryQuery = "INSERT INTO history (session_id, product_id, product_name, mrp,price, qty,  purchase_date) 
                               VALUES ('$session_id', $pid, '" . mysqli_real_escape_string($conn, $productName) . "', $mrp,$price, $qty, NOW())";
        mysqli_query($conn, $insertHistoryQuery);
        
        // Update the stock and qtys in products table
        mysqli_query($conn, "UPDATE products SET 
            stock = stock - $qty
            WHERE id = $pid AND stock >= $qty");
    }
    
    // Now delete the cart items for this session
    mysqli_query($conn, "DELETE FROM cart WHERE session_id='$session_id'");
    
    exit;
}


$result = mysqli_query($conn, "SELECT cart.*, products.name, products.price, products.stock,products.qtys ,products.mrp FROM cart JOIN products ON cart.product_id = products.id WHERE cart.session_id='$session_id'");
$total = 0;


if (mysqli_num_rows($result) == 0):
?>
    <div class="cart-empty" style="padding:20px; text-align:center; color:#555;">
        Your cart is empty
    </div>
<?php
else:
?>


<div class="cart-header">
    <h2 style="margin:0;">Your Cart</h2>
    <button class="update-btn" onclick="handleUpdate()">Update</button>

</div>

<table>
    <tr>
        <th>Product</th>
        <th>MRP</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
              <th>Delete</th>


    </tr>
    <?php while($row = mysqli_fetch_assoc($result)): 
        $sub = $row['price'] * $row['qty'];
        $total += $sub;
    ?>
    <tr>
        <td style="font-size: 13px; width: 40%;"><?= $row['name'] ?></td>
               <td>₹<?= $row['mrp'] ?></td>
        <td>₹<?= $row['price'] ?></td>
    
        <td>
            <div class="qty-controls">
                <div class="qty-box-blue">
                    <button onclick="updateQty(<?= $row['product_id'] ?>, -1)">−</button>
                    <div class="divider"></div>
                                    <span style="font-weight: bold;"><?= $row['qty'] ?></span>

                    <button onclick="updateQty(<?= $row['product_id'] ?>, 1)" <?= $row['qty'] >= $row['stock'] ? 'disabled' : '' ?>>+</button>
                </div>
            </div>
            <?php if($row['qty'] >= $row['stock']): ?>
                <div style="color:red; font-size:10px;">Max Stock</div>
            <?php endif; ?>
  
        </td>

              <td>₹<?= $sub ?></td>
        <td>        <button onclick="removeItem(<?= $row['product_id'] ?>)">❌</button></td>
         <?php endwhile; ?>
    </tr>
   
</table>

<div style="margin-top: 20px; font-weight: bold; font-size: 18px;">
    Total: ₹<?= number_format($total, 2) ?>
</div>
<?php endif; ?>

    <script>function removeItem(id) {
    fetch('cart-action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=remove&id=' + id
    }).then(() => {
        loadCart();   // refresh cart UI
    });
}   </script>