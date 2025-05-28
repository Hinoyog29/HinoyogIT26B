<?php
session_start();
include "db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Insert purchase record
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    $message = "Purchase successful!";
}

// Get all products except own
$stmt = $conn->prepare("SELECT p.id, p.name, p.description, p.price, u.username FROM products p INNER JOIN users u ON p.user_id = u.id WHERE p.user_id != ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buy Products</title>
</head>
<body>
    <h2>Available Products to Buy</h2>
    <?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <ul>
    <?php while ($row = $products->fetch_assoc()): ?>
        <li>
            <strong><?php echo htmlspecialchars($row['name']); ?></strong> 
            by <?php echo htmlspecialchars($row['username']); ?> -
            $<?php echo number_format($row['price'], 2); ?><br/>
            <em><?php echo htmlspecialchars($row['description']); ?></em><br/>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <button type="submit">Buy</button>
            </form>
        </li>
    <?php endwhile; ?>
    </ul>

    <a href="user_dashboard.php">Back to Dashboard</a>
</body>
</html>
