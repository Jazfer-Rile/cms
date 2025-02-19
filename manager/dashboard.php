<?php
session_start();
include("../includes/config.php");

// Redirect if not manager
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Manager") {
    header("Location: ../auth/login.php");
    exit();
}

$manager_id = $_SESSION["user_id"];

// Fetch total assigned employees (Check if employees report to the manager)
$total_employees = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'Employee'")->fetch_row()[0];

// Fetch total assigned competencies (Adjust query based on schema)
$total_competencies = $conn->query("
    SELECT COUNT(*) FROM user_competencies 
    WHERE user_id IN (SELECT id FROM users WHERE role = 'Employee')
")->fetch_row()[0];

// Fetch total assigned training programs (Adjust query based on schema)
$total_trainings = $conn->query("
    SELECT COUNT(*) FROM user_training 
    WHERE user_id IN (SELECT id FROM users WHERE role = 'Employee')
")->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manager Dashboard</title>
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
        .employees { background-color: #007bff; }
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
            <h2 class="mt-3">📊 Manager Dashboard</h2>

            <div class="row mt-4">
                <!-- Total Employees -->
                <div class="col-md-4">
                    <div class="dashboard-card employees shadow-sm">
                        <h4>Total Employees</h4>
                        <h2><?= $total_employees; ?></h2>
                    </div>
                </div>

                <!-- Total Assigned Competencies -->
                <div class="col-md-4">
                    <div class="dashboard-card competencies shadow-sm">
                        <h4>Assigned Competencies</h4>
                        <h2><?= $total_competencies; ?></h2>
                    </div>
                </div>

                <!-- Total Assigned Training Programs -->
                <div class="col-md-4">
                    <div class="dashboard-card trainings shadow-sm">
                        <h4>Assigned Trainings</h4>
                        <h2><?= $total_trainings; ?></h2>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
