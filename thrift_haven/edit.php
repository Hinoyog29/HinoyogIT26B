<?php
include 'db.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE products SET name=?, price=? WHERE product_id=?");
    $stmt->bind_param("sdi", $name, $price, $id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT name, price FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $price);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head><title>Edit Product</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper p-4">
        <h2>Edit Product</h2>
        <form method="POST">
            <div class="form-group">
                <label>Product Name</label>
                <input name="name" class="form-control" value="<?= htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input name="price" type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($price); ?>" required>
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
