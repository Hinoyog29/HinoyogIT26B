<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id']; // assuming admin is creating the product

    $stmt = $conn->prepare("INSERT INTO products (name, price, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $name, $price, $user_id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper p-4">
        <h2>Add Product</h2>
        <form method="POST">
            <div class="form-group">
                <label>Product Name</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input name="price" type="number" step="0.01" class="form-control" required>
            </div>
            <button class="btn btn-success">Save</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
