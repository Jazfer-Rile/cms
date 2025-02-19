<?php
session_start();
include("../includes/config.php");

// Redirect if not employee
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Employee") {
    header("Location: ../auth/login.php");
    exit();
}

$employee_id = $_SESSION["user_id"];

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['training_id']) && isset($_POST['status'])) {
    $training_id = $_POST['training_id'];
    $new_status = $_POST['status'];

    // Update training status
    $stmt = $conn->prepare("UPDATE user_training SET status = ? WHERE user_id = ? AND training_id = ?");
    $stmt->bind_param("sii", $new_status, $employee_id, $training_id);
    $stmt->execute();
}

// Fetch training programs assigned to this employee
$query = $conn->prepare("SELECT t.id, t.title, t.description, ut.status 
                         FROM user_training ut
                         JOIN training_programs t ON ut.training_id = t.id 
                         WHERE ut.user_id = ?");
$query->bind_param("i", $employee_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Training Programs</title>
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
        .status-dropdown {
            width: 100%;
        }
        .status-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">📚 My Training Programs</h2>

            <div class="table-responsive shadow-sm p-3 bg-white rounded">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Training Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Update Status</th>
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
                                    <td>
                                        <span class="badge bg-<?= ($row['status'] == 'Completed') ? 'success' : (($row['status'] == 'In Progress') ? 'warning' : 'secondary'); ?>">
                                            <?= $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" class="status-form">
                                            <input type="hidden" name="training_id" value="<?= $row['id']; ?>">
                                            <select name="status" class="form-select form-select-sm status-dropdown" onchange="this.form.submit()">
                                                <option value="Assigned" <?= ($row['status'] == 'Assigned') ? 'selected' : ''; ?>>Assigned</option>
                                                <option value="In Progress" <?= ($row['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; 
                        else : ?>
                            <tr>
                                <td colspan="5" class="text-muted text-center">No training programs assigned yet.</td>
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
                            