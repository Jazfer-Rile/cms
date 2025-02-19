<?php
session_start();
include("../includes/config.php");

// Redirect if not employee
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Employee") {
    header("Location: ../auth/login.php");
    exit();
}

$employee_id = $_SESSION["user_id"];

// Fetch competencies assigned to this employee
$query = $conn->prepare("SELECT c.title, c.description 
                         FROM user_competencies uc
                         JOIN competencies c ON uc.competency_id = c.id 
                         WHERE uc.user_id = ?");
$query->bind_param("i", $employee_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Competencies</title>
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
        .no-data {
            text-align: center;
            font-size: 1.1rem;
            color: #777;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">🏆 My Competencies</h2>

            <div class="table-responsive shadow-sm p-3 bg-white rounded">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Competency Title</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        if ($result->num_rows > 0) :
                            while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $count++; ?></td>
                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                    <td><?= nl2br(htmlspecialchars($row['description'])); ?></td>
                                </tr>
                            <?php endwhile; 
                        else : ?>
                            <tr>
                                <td colspan="3" class="no-data">No competencies assigned yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
