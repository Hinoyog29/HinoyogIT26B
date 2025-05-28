<?php
include "db.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username_db, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username_db;
            $_SESSION['role'] = $role; // Save role in session

            if ($role === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "❌ No account found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <h2>Login</h2>
                <?php if ($message): ?>
                    <p><?php echo $message; ?></p>
                <?php endif; ?> 
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <label><input type="checkbox" name="remember"> Remember Me</label>
                <a href="#" style="float: right;">Forgot Password?</a>
                <button type="submit">Login</button>
                <p>Don't Have An Account? <a href="register.php">Sign Up</a></p>
            </form>
        </div>
        <div class="banner">Thrift Haven</div>
    </div>
</body>
</html>
