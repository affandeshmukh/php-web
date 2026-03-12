<?php
session_start();
//require "db.php";

// Get form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// SQL query (NO prepared statement)
$sql = "SELECT id FROM users 
        WHERE username = '$username' 
        AND password = '$password'";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['user_id'] = $row['id'];
    header("Location: index.php");
    exit;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .login-box {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
        button {
            width: 100%;
            padding: 8px;
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <!-- Error message -->
    <?php if (isset($_GET['error'])): ?>
        <div class="error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
