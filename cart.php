<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['username'])) {
  echo "<script>alert('Please login first!'); window.location.href='login.html';</script>";
  exit;
}

$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM cart WHERE username='$username'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart | Foodify</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #fff8f3;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #28a745;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            font-size: 1.5rem;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .cart-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item h3 {
            font-size: 1.1rem;
            color: #333;
            margin: 0;
        }

        .cart-item span {
            font-weight: 500;
            color: #ff6b35;
        }

        .total {
            text-align: right;
            font-size: 1.3rem;
            font-weight: bold;
            color: #222;
            margin-top: 20px;
        }

        .empty-cart {
            text-align: center;
            color: #999;
            padding: 30px 0;
            font-size: 1.2rem;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 30px;
            padding: 12px;
            background-color: #ff6b35;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #e55d2d;
        }
    </style>
</head>

<body>

    <header>🛒 Your Cart</header>

    <div class="cart-container">
        <div id="cart-items" class="cart-items"></div>
        <div class="total" id="cart-total"></div>
        <button class="checkout-btn" id="checkout-btn">Proceed to Checkout</button>
    </div>

    <script>
        async function loadCart() {
            const userId = localStorage.getItem('userId');

            if (!userId) {
                document.getElementById('cart-items').innerHTML = `
          <div class="empty-cart">Please log in to view your cart.</div>`;
                document.getElementById('checkout-btn').style.display = "none";
                return;
            }

            const res = await fetch(`backend/get_cart.php?userId=${userId}`);
            const items = await res.json();

            const container = document.getElementById('cart-items');
            const totalDiv = document.getElementById('cart-total');

            if (items.length === 0) {
                container.innerHTML = `<div class="empty-cart">Your cart is empty 🍽️</div>`;
                totalDiv.textContent = '';
                document.getElementById('checkout-btn').style.display = "none";
                return;
            }

            let total = 0;
            container.innerHTML = items.map(item => {
                total += parseFloat(item.price);
                return `
          <div class="cart-item">
            <h3>${item.food_name}</h3>
            <span>₹${item.price}</span>
          </div>
        `;
            }).join('');

            totalDiv.textContent = `Total: ₹${total.toFixed(2)}`;
        }

        document.getElementById('checkout-btn').addEventListener('click', () => {
            alert('Checkout functionality coming soon!');
        });

        loadCart();
    </script>

</body>

</html>