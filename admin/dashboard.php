<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch total users
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];

// Fetch total competencies
$total_competencies = $conn->query("SELECT COUNT(*) FROM competencies")->fetch_row()[0];

// Fetch total training programs
$total_trainings = $conn->query("SELECT COUNT(*) FROM training_programs")->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
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
        /* Dashboard Cards */
        .dashboard-card {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 1.2rem;
        }
        .users { background-color: #007bff; }
        .competencies { background-color: #28a745; }
        .trainings { background-color: #ffc107; }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">📊 Admin Dashboard</h2>

            <div class="row mt-4">
                <!-- Total Users -->
                <div class="col-md-4">
                    <div class="dashboard-card users shadow-sm">
                        <h4>Total Users</h4>
                        <h2><?= $total_users; ?></h2>
                    </div>
                </div>

                <!-- Total Competencies -->
                <div class="col-md-4">
                    <div class="dashboard-card competencies shadow-sm">
                        <h4>Total Competencies</h4>
                        <h2><?= $total_competencies; ?></h2>
                    </div>
                </div>

                <!-- Total Training Programs -->
                <div class="col-md-4">
                    <div class="dashboard-card trainings shadow-sm">
                        <h4>Total Training Programs</h4>
                        <h2><?= $total_trainings; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Add more dashboard widgets here -->

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
