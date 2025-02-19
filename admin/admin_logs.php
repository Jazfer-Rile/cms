<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch activity logs
$logs_query = $conn->query("SELECT al.id, al.action_type, al.action_description, al.timestamp, u.name AS assigned_by, e.name AS assigned_to 
                            FROM activity_logs al 
                            JOIN users u ON al.assigned_by = u.id 
                            JOIN users e ON al.assigned_to = e.id 
                            ORDER BY al.timestamp DESC");

// Check if the query is successful
if (!$logs_query) {
    die("Query failed: " . $conn->error); // Handle error gracefully if the query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Logs</title>
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
            width: calc(100% - 260px); /* Adjusted width */
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
            <h2 class="mt-3">📜 Activity Logs</h2>

            <div class="table-responsive shadow-sm p-3 bg-white rounded">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Action Type</th>
                            <th>Description</th>
                            <th>Assigned By</th>
                            <th>Assigned To</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($log = $logs_query->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $log['id']; ?></td>
                            <td><?= $log['action_type']; ?></td>
                            <td><?= $log['action_description']; ?></td>
                            <td><?= $log['assigned_by']; ?></td>
                            <td><?= $log['assigned_to']; ?></td>
                            <td><?= $log['timestamp']; ?></td>
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
