<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists, show error message
        $error_message = "The email is already registered. Please choose another email.";
    } else {
        // Generate a new user ID with 4 digits, starting from 0001
        $id_query = $conn->query("SELECT MAX(id) AS max_id FROM users");
        $result = $id_query->fetch_assoc();
        $max_id = $result['max_id'];
        $new_id = str_pad($max_id + 1, 4, '0', STR_PAD_LEFT); // Generate ID like 0001, 0002, ...

        // ✅ Hash the password before inserting
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into the database with the generated user ID
        $stmt = $conn->prepare("INSERT INTO users (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $new_id, $name, $email, $hashed_password, $role);
        $stmt->execute();

        // Redirect after successful insertion
        header("Location: manage_users.php");
        exit();
    }
} else {
    // Generate the next available user ID on page load if the form is not submitted yet
    $id_query = $conn->query("SELECT MAX(id) AS max_id FROM users");
    $result = $id_query->fetch_assoc();
    $max_id = $result['max_id'];
    $new_id = str_pad($max_id + 1, 4, '0', STR_PAD_LEFT); // Generate ID like 0001, 0002, ...
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add User</title>
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
            margin-left: 260px; /* Space for sidebar */
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
            <h2 class="mt-3">➕ Add User</h2>

            <!-- Error Message -->
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger">
                    <?= $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- User Form -->
            <form method="POST" class="p-4 shadow bg-white rounded">
                <div class="mb-3">
                    <label class="form-label">User ID:</label>
                    <input type="text" name="user_id" class="form-control" value="<?= isset($new_id) ? $new_id : ''; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">👤 Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">📧 Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">🔑 Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">🎭 Role:</label>
                    <select name="role" class="form-control" required>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Employee">Employee</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">✅ Add User</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
