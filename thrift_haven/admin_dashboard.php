<?php
session_start();
include "db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "
    SELECT products.product_id, products.name AS product, users.username, products.price 
    FROM products 
    INNER JOIN users ON products.user_id = users.user_id
";

if (!empty($search)) {
    $query .= " WHERE products.name LIKE '%$search%'";
}

$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thrift Haven Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <style>
    .navbar-nav .btn { margin-left: 10px; }
    .table td, .table th { vertical-align: middle; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link font-weight-bold">Thrift Haven Admin</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <form class="form-inline mr-3" method="GET">
        <div class="input-group input-group-sm">
          <input class="form-control" type="search" name="search" placeholder="Search" value="<?= htmlspecialchars($search); ?>">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
      <li class="nav-item">
        <form action="logout.php" method="post" onsubmit="return confirm('Are you sure you want to log out?');">
          <button class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link text-center">
      <span class="brand-text font-weight-light">Thrift Haven</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-box-open"></i>
              <p>Manage Products</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h4 class="mb-0">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></h4>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <!-- Card -->
        <div class="card-header d-flex align-items-center">
  <div class="flex-grow-1">
    <h3 class="card-title mb-0">All Products</h3>
  </div>
  <a href="create.php" class="btn btn-success btn-sm">
    <i class="fas fa-plus"></i> Add Product
  </a>
</div>

          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Owner</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['product']); ?></td>
                      <td>₱<?= htmlspecialchars($row['price']); ?></td>
                      <td><?= htmlspecialchars($row['username']); ?></td>
                      <td class="text-center">
                        <a href="edit.php?id=<?= $row['product_id']; ?>" class="btn btn-sm btn-primary mr-1">
                          <i class="fas fa-edit"></i>
                        </a>
                        <a href="delete.php?id=<?= $row['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">
                          <i class="fas fa-trash-alt"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center">No products found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-center">
    <strong>&copy; <?= date("Y"); ?> Thrift Haven</strong> | All rights reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
