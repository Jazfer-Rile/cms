<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Check if user ID is provided in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Redirect if user not found
if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    // If password is provided, update it
    if (!empty($_POST["password"])) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $password, $role, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Error updating user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        /* Sidebar & Content Layout */
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 10px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }
        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">✏ Edit User</h2>

            <?php if (isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>

            <form method="POST" class="p-4 shadow bg-white rounded">
                <div class="mb-3">
                    <label class="form-label">👤 Name:</label>
                    <input type="text" name="name" class="form-control" value="<?= $user['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">📧 Email:</label>
                    <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">🔑 New Password (Optional):</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                </div>
                <div class="mb-3">
                    <label class="form-label">🎭 Role:</label>
                    <select name="role" class="form-control" required>
                        <option value="Admin" <?= ($user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="Manager" <?= ($user['role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                        <option value="Employee" <?= ($user['role'] == 'Employee') ? 'selected' : ''; ?>>Employee</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">✅ Update User</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
