<?php include 'db.php';
 session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
} 
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Products</title>
<style>
   :root {
    --primary-blue: #2563eb;
    --hover-blue: #1d4ed8;
    --bg-light: #f8fafc;
    --text-dark: #1e293b;
    --border-color: #e2e8f0;
    --white: #ffffff;
    --danger: #ef4444;
    --success: #10b981;
}

body {
    margin: 0;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
}

/* Modernized Topnav */
.topnav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 40px;
    height: 64px;
    background: var(--white);
    color: var(--text-dark);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.topnav .brand {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-blue);
}

.topnav a {
    color: #64748b;
    text-decoration: none;
    margin-left: 20px;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s;
}

.topnav a:hover {
    color: var(--primary-blue);
}

/* Layout Container */
.container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 24px;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

/* Modernized Rows */
.row {
    display: flex;
    justify-content: space-between;
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

.row:last-child {
    border-bottom: none;
}

/* Buttons */
.btn {
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-del {
    background: #fee2e2;
    color: var(--danger);
}

.btn-del:hover {
    background: var(--danger);
    color: var(--white);
}

.btn-up {
    background: var(--primary-blue);
    color: var(--white);
}

.btn-up:hover {
    background: var(--hover-blue);
}

/* Forms & Inputs */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

input {
    padding: 10px 12px;
    border: 1px solid black;
    border-radius: 6px;
    outline: none;
    transition: border-color 0.2s;
}

input:focus {
    border-color: var(--primary-blue);
    ring: 2px solid rgba(37, 99, 235, 0.1);
}
  
  /* 1. Make the Topnav stackable */
@media (max-width: 768px) {
    .topnav {
        flex-direction: column;
        height: auto;
        padding: 20px;
        gap: 15px;
    }
    .topnav a {
        margin-left: 0;
    }
}

/* 2. Make Rows and Containers responsive */
@media (max-width: 768px) {
    .container {
        margin: 20px 10px; /* Reduce side margins on mobile */
        padding: 16px;
    }

    .row {
        flex-direction: column; /* Stack row items vertically */
        align-items: flex-start;
        gap: 10px;
        text-align: left;
    }
    
    .row > div {
        width: 100%; /* Ensure content fills the stack */
    }
}

/* 3. Improve Touch Targets for Mobile */
@media (max-width: 768px) {
    .btn {
        width: 100%; /* Full-width buttons are easier to tap */
        padding: 12px;
        font-size: 16px; /* Prevents iOS zoom on input focus */
    }
    
    input {
        font-size: 16px; /* Prevents auto-zoom on mobile browsers */
        padding: 12px;
    }
}  
    
</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
    <div id="toast" style="
    position:fixed;
    top:20px;
    right:20px;
    background:#2ecc71;
    color:#fff;
    padding:10px 18px;
    border-radius:4px;
    font-size:14px;
    display:none;
    z-index:9999;
">
    Updated
</div>

<div class="topnav">
    <div class="brand">Admin Panel</div>
    <div>
        <a href="admin.php">Products</a>
        <a href="../index.php">Store</a>
         <a href="add.php">Add New product</a>
        <a href="../logout.php">Logout</a>
        
    </div>
</div>


<div class="container">
<h2>Admin – Products</h2>



<br>

<!-- SEARCH -->
<div class="form-group" style="max-width:200px;">
    <label>Search Product</label>
    <input type="text" onkeyup="loadProducts(this.value)">

        <button class="btn btn-up" onclick="updateAll()">
        Update All
    </button>
</div>

<br>

<div id="admin-products"></div>
</div>

<script>
function loadProducts(q='') {
    fetch('update.php?q='+encodeURIComponent(q)+'&t='+Date.now())
        .then(r=>r.text())
        .then(h=>document.getElementById('admin-products').innerHTML=h);
}



function del(id){
    fetch('delete.php?id=' + id)
        .then(() => loadProducts());
}


function save(id){
    let f = document.getElementById('f' + id);
    fetch('update.php', {
        method: 'POST',
        body: new FormData(f)
    }).then(() => {
        loadProducts();
        showToast('Updated');
    });
}

loadProducts();

function updateAll() {
    const forms = document.querySelectorAll('#admin-products form');

    let requests = [];
    forms.forEach(f => {
        requests.push(
            fetch('update.php', {
                method: 'POST',
                body: new FormData(f)
            })
        );
    });

    Promise.all(requests).then(() => {
        loadProducts();
        showToast('Updated');
    });
}

function showToast(msg='Updated') {
    const t = document.getElementById('toast');
    t.innerText = msg;
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 1500);
}



</script>
</body>
</html>