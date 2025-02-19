<?php
session_start();
include("../includes/config.php");

// Redirect if not employee
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Employee") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Count assigned competencies
$query = $conn->prepare("SELECT COUNT(*) AS total_competencies FROM user_competencies WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$total_competencies = $result->fetch_assoc()['total_competencies'];

// Count assigned training
$query = $conn->prepare("SELECT COUNT(*) AS total_trainings FROM user_training WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$total_trainings = $result->fetch_assoc()['total_trainings'];

// Count completed training
$query = $conn->prepare("SELECT COUNT(*) AS completed_trainings FROM user_training WHERE user_id = ? AND status = 'Completed'");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$completed_trainings = $result->fetch_assoc()['completed_trainings'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Dashboard</title>
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
            padding: 40px;
            width: 100%;
        }
        /* Dashboard Cards */
        .card {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: bold;
        }
        .card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">🏆 Employee Dashboard</h2>

            <div class="row mt-4">
                <!-- Assigned Competencies -->
                <div class="col-md-4">
                    <div class="card bg-primary text-white shadow-sm">
                        <i class="bi bi-book"></i>
                        <h4>Assigned Competencies</h4>
                        <h2><?= $total_competencies; ?></h2>
                    </div>
                </div>

                <!-- Assigned Training -->
                <div class="col-md-4">
                    <div class="card bg-warning text-dark shadow-sm">
                        <i class="bi bi-mortarboard"></i>
                        <h4>Assigned Training</h4>
                        <h2><?= $total_trainings; ?></h2>
                    </div>
                </div>

                <!-- Completed Training -->
                <div class="col-md-4">
                    <div class="card bg-success text-white shadow-sm">
                        <i class="bi bi-check-circle"></i>
                        <h4>Completed Training</h4>
                        <h2><?= $completed_trainings; ?></h2>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
