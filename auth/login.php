<?php
session_start();
include("../includes/config.php");

$error = ""; // Initialize error message

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["name"] = $name;
            $_SESSION["role"] = $role;

            // Redirect based on role
            if ($role == "Admin") {
                header("Location: ../admin/dashboard.php");
            } elseif ($role == "Manager") {
                header("Location: ../manager/dashboard.php");
            } else {
                header("Location: ../employee/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - CMS</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-card {
            width: 350px;
            margin: auto;
            margin-top: 10%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card">
        <h3 class="text-center mb-3">🔐 Login</h3>
        <?php if (!empty($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">📧 Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">🔑 Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
