<?php
session_start();
include "db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO products (user_id, name, description, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $desc, $price);
    $stmt->execute();
}

$stmt = $conn->prepare("SELECT product_id, name, description, price FROM products WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - Thrift Haven</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <style>
    .main-content {
      max-width: 960px;
      margin: 0 auto;
      padding: 20px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <form action="logout.php" method="post" class="mb-0">
          <button class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
      </li>
    </ul>
  </nav>

  <!-- Content Wrapper -->
  <div class="content-wrapper pt-3">
    <div class="main-content">

      <!-- Welcome -->
      <div class="content-header">
        <h1 class="m-0">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
      </div>

      <!-- Product Form -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Add New Product</h3>
        </div>
        <form method="POST">
          <div class="card-body">
            <div class="form-group">
              <label for="name">Product Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" name="description" class="form-control" placeholder="Enter description">
            </div>
            <div class="form-group">
              <label for="price">Price (₱)</label>
              <input type="text" name="price" class="form-control" placeholder="Enter price" required>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</button>
          </div>
        </form>
      </div>

      <!-- Product List -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Your Products</h3>
        </div>
        <div class="card-body">
          <table id="productTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price (₱)</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['product_id']; ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                  <td><?php echo htmlspecialchars($row['description']); ?></td>
                  <td><?php echo htmlspecialchars($row['price']); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-center">
    <strong>&copy; <?php echo date("Y"); ?> Thrift Haven</strong>
  </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
  $(document).ready(function () {
    $('#productTable').DataTable();
  });
</script>
</body>
</html>
