<?php
include 'db.php';
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php");
exit();
?>
