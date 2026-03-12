<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    
  <style>
:root {
    --primary: #0061ff;       /* Modern Indigo */
    --primary-hover: #4338ca;
    --bg: #f1f5f9;            /* Subtle cool gray background */
    --surface: #ffffff;
    --text-main: #0f172a;     /* Deep slate */
    --text-muted: #64748b;
    --border: #cbd5e1;
}

body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background-color: var(--bg);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

#addForm {
    background: var(--surface);
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    width: 100%;
    max-width: 400px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-group { margin-bottom: 1.5rem; }

.form-group label {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 0.6rem;
}

input {
    width: 100%;
    padding: 0.8rem;
    background: #f8fafc;
    border: 2px solid transparent;
    border-radius: 10px;
    font-size: 1rem;
    color: var(--text-main);
    transition: all 0.3s ease;
    box-sizing: border-box;
}

input:focus {
    outline: none;
    background: #ffffff;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

button {
    width: 100%;
    background: var(--primary);
    color: white;
    padding: 0.9rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: transform 0.2s, background 0.2s;
    margin-top: 1rem;
}

button:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
}

/* Stylish Toast */
#toast {
    background: #0f172a; /* Dark navy for contrast */
    color: #f8fafc;
    padding: 12px 24px;
    border-radius: 50px; /* Pill shape */
    font-weight: 500;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
}
</style>
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
    Added
</div>
<!-- ADD PRODUCT -->
<form id="addForm" style="">
    <div class="form-group">
        <label>Product Name</label>
        <input name="name" required>
    </div>

    <div class="form-group">
        <label>Mrp</label>
        <input name="mrp" type="number" required>
    </div>

    <div class="form-group">
        <label>Price</label>
        <input name="price" type="number" required>
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input name="stock" type="number" required>
    </div>

    <div class="form-group">
        <label>Category</label>
        <input name="category" placeholder="Pizza / Burger">
    </div>

    <button>Add</button>
</form>

    <a href="/" >Go back</a>
<script>
document.getElementById('addForm').onsubmit = e => {
    e.preventDefault();
    fetch('update.php', {
        method:'POST',
        body:new FormData(e.target)
    })
    .then(()=>{
         e.target.reset(); 
           showToast('Added');

 });
};

function showToast(msg='Added') {
    const t = document.getElementById('toast');
    t.innerText = msg;
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 1500);
}
</script>

    
</body>
</html>