
function removeItem(id) {
    fetch('cart-action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=remove&id=' + id
    }).then(() => {
        loadCart();   // refresh cart UI
    });
}   

function loadProducts() {
    fetch('load-products.php?t=' + Date.now())
        .then(r => r.text())
        .then(h => document.getElementById('products-list').innerHTML = h);
}

function searchProducts(text) {
    fetch('load-products.php?q=' + encodeURIComponent(text) + '&t=' + Date.now())
        .then(r => r.text())
        .then(h => document.getElementById('products-list').innerHTML = h);
}

function loadCart() {
    fetch('cart-action.php').then(r => r.text()).then(h => document.getElementById('cart-display').innerHTML = h);
}

// function addToCart(id) {
//     let fd = new FormData(); fd.append('action', 'add'); fd.append('id', id);
//     fetch('cart-action.php', { method: 'POST', body: fd }).then(() => { loadCart(); loadProducts(); });
// }


// function addToCart(id) {
//     fetch('cart-action.php', {
//         method: 'POST',
//         headers: {'Content-Type': 'application/x-www-form-urlencoded'},
//         body: 'action=add&id=' + id
//     }).then(() => {
//         loadCart();
//         filterProducts(); 
//     });
// }

function addToCart(id) {
    fetch('cart-action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=add&id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'exists') {
            showCartMessage(data.message);
        }
        filterProducts();
        loadCart(); // reload cart UI
    });
}


function updateQty(id, qty) {
    let fd = new FormData(); fd.append('action', 'update'); fd.append('id', id); fd.append('qty', qty);
    fetch('cart-action.php', { method: 'POST', body: fd }).then(() => loadCart());
}


function updateQtys(id, qutys) {
    let fd = new FormData(); fd.append('action', 'updated'); fd.append('id', id); fd.append('qutys',qutys);
    fetch('cart-action.php', { method: 'POST', body: fd }).then(() => loadCart());
}

function finalCheckout() {
    

    let fd = new FormData();
    fd.append('action', 'final');


    fetch('cart-action.php', {
        method: 'POST',
        body: fd
    }).then(() => {
        loadCart();
        loadProducts();
    });
}


// Initial Load
loadProducts();
loadCart();


let selectedCategory = ''; // currently selected category

function setCategoryFilter(cat) {
    selectedCategory = cat;
    filterProducts();
}

function filterProducts() {
    const searchText = document.getElementById('search').value;

    // Build query params
    let params = '?t=' + Date.now();
    if (searchText) params += '&q=' + encodeURIComponent(searchText);
    if (selectedCategory) params += '&category=' + encodeURIComponent(selectedCategory);

    fetch('load-products.php' + params)
        .then(r => r.text())
        .then(html => {
            document.getElementById('products-list').innerHTML = html;
        });
}

// Load products on page load
filterProducts();


function updateShop(pid, shop) {
    $.post("cart-action.php", {
        action: "shop",
        id: pid,
        shop: shop
    });
}


async function handleUpdate() {
   try {
    
       //await printReceipt();   // waits until print finishes
       finalCheckout();        // runs only after print success
   } catch (e) {
        alert("Printing failed. Checkout stopped.");
   }
}

function showCartMessage(text) {
    const msg = document.getElementById('cart-msg');
    msg.innerText = text;
    msg.style.display = 'block';

    setTimeout(() => {
        msg.style.display = 'none';
    }, 2000);
}
