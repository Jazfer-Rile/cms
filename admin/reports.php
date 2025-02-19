<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all employees
$employees = $conn->query("SELECT id, name, email FROM users WHERE role = 'Employee'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Reports</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        /* Sidebar & Content Layout Fix */
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
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
            margin-left: 260px; /* Ensures space for sidebar */
            padding: 20px;
            width: calc(100% - 260px); /* Adjusted width */
        }
        /* Responsive Fix */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
                width: 100%;
            }
        }
        /* Table Styling */
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">📜 Employee Reports</h2>
            <div class="d-flex justify-content-end mb-3">
                <a href="export_pdf.php" class="btn btn-danger me-2">📄 Export to PDF</a>
                <a href="export_excel.php" class="btn btn-success">📊 Export to Excel</a>
            </div>

            <div class="table-responsive shadow-sm p-3 bg-white rounded">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Assigned Competencies</th>
                            <th>Assigned Training</th>
                            <th>Completed Training</th>
                            <th>Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $employees->fetch_assoc()) :
                            // Fetch assigned competencies count
                            $comp_query = $conn->prepare("SELECT COUNT(*) AS total_comp FROM user_competencies WHERE user_id = ?");
                            $comp_query->bind_param("i", $row['id']);
                            $comp_query->execute();
                            $comp_result = $comp_query->get_result();
                            $total_comp = $comp_result->fetch_assoc()['total_comp'];

                            // Fetch assigned training count
                            $train_query = $conn->prepare("SELECT COUNT(*) AS total_train FROM user_training WHERE user_id = ?");
                            $train_query->bind_param("i", $row['id']);
                            $train_query->execute();
                            $train_result = $train_query->get_result();
                            $total_train = $train_result->fetch_assoc()['total_train'];

                            // Fetch completed training count
                            $completed_query = $conn->prepare("SELECT COUNT(*) AS completed FROM user_training WHERE user_id = ? AND status = 'Completed'");
                            $completed_query->bind_param("i", $row['id']);
                            $completed_query->execute();
                            $completed_result = $completed_query->get_result();
                            $completed_trainings = $completed_result->fetch_assoc()['completed'];

                            // Calculate Completion Rate
                            $completion_rate = ($total_train > 0) ? round(($completed_trainings / $total_train) * 100, 2) . '%' : '0%';
                        ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $total_comp; ?></td>
                            <td><?= $total_train; ?></td>
                            <td><?= $completed_trainings; ?></td>
                            <td><span class="badge bg-success"> <?= $completion_rate; ?> </span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
