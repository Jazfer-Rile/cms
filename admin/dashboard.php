<?php
// Start the session to access user login data
session_start();

// Include the database configuration file to establish a connection
include("../includes/config.php");

// Redirect user if they are not an Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php"); // Redirect to login if unauthorized
    exit();
}

// Check if the database connection is established
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Initialize variables to prevent "undefined variable" warnings
$total_users = $total_competencies = $total_trainings = 0;

// Fetch total number of users
$result = $conn->query("SELECT COUNT(*) FROM users");
if ($result) {
    $total_users = $result->fetch_row()[0]; // Get the count value
} else {
    echo "⚠️ Error fetching users: " . $conn->error;
}

// Fetch total number of competencies
$result = $conn->query("SELECT COUNT(*) FROM competencies");
if ($result) {
    $total_competencies = $result->fetch_row()[0]; // Get the count value
} else {
    echo "⚠️ Error fetching competencies: " . $conn->error;
}

// Fetch total number of training programs
$result = $conn->query("SELECT COUNT(*) FROM training_programs");
if ($result) {
    $total_trainings = $result->fetch_row()[0]; // Get the count value
} else {
    echo "⚠️ Error fetching training programs: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        /* Layout adjustments */
        body {
            display: flex;
        }
        .content {
            margin-left: 260px; /* Space for sidebar */
            width: 100%;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar Navigation -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content Section -->
    <div class="content">
        <h2>📊 Admin Dashboard</h2>

        <!-- Dashboard Stats Cards -->
        <div class="row">
            <!-- Total Users Card -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h4>Total Users</h4>
                        <h2 class="text-primary"><i class="bi bi-people"></i> <?= $total_users; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Total Competencies Card -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h4>Total Competencies</h4>
                        <h2 class="text-success"><i class="bi bi-book"></i> <?= $total_competencies; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Total Training Programs Card -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h4>Total Training Programs</h4>
                        <h2 class="text-warning"><i class="bi bi-mortarboard"></i> <?= $total_trainings; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link to Bootstrap JavaScript -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
