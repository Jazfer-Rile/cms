<?php
session_start();
include("../includes/config.php");

// Redirect if not manager
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Manager") {
    header("Location: ../auth/login.php");
    exit();
}

$manager_id = $_SESSION["user_id"];

// Fetch employees under this manager
$employees = $conn->query("SELECT id, name, email FROM users WHERE role = 'Employee'");

// Handle delete request for competencies
if (isset($_GET['delete_competency']) && isset($_GET['user_id'])) {
    $competency_id = intval($_GET['delete_competency']);
    $user_id = intval($_GET['user_id']);

    // Allow Admins to delete anything, but Managers can only delete their own assignments
    if ($_SESSION["role"] == "Admin") {
        $stmt = $conn->prepare("DELETE FROM user_competencies WHERE user_id = ? AND competency_id = ?");
        $stmt->bind_param("ii", $user_id, $competency_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM user_competencies WHERE user_id = ? AND competency_id = ? AND assigned_by = ?");
        $stmt->bind_param("iii", $user_id, $competency_id, $manager_id);
    }
    $stmt->execute();
    header("Location: employees.php");
    exit();
}

// Handle delete request for training
if (isset($_GET['delete_training']) && isset($_GET['user_id'])) {
    $training_id = intval($_GET['delete_training']);
    $user_id = intval($_GET['user_id']);

    // Allow Admins to delete anything, but Managers can only delete their own assignments
    if ($_SESSION["role"] == "Admin") {
        $stmt = $conn->prepare("DELETE FROM user_training WHERE user_id = ? AND training_id = ?");
        $stmt->bind_param("ii", $user_id, $training_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM user_training WHERE user_id = ? AND training_id = ? AND assigned_by = ?");
        $stmt->bind_param("iii", $user_id, $training_id, $manager_id);
    }
    $stmt->execute();
    header("Location: employees.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Employees</title>
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
        /* Action Buttons */
        .btn-sm {
            font-size: 0.875rem;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">👥 Employees Under Your Supervision</h2>

            <div class="table-responsive shadow-sm p-3 bg-white rounded">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Assigned Competencies</th>
                            <th>Assigned Training</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $employees->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td>
                                <?php
                                // Fetch assigned competencies
                                $comp_query = $conn->query("SELECT c.id, c.title, uc.assigned_by FROM user_competencies uc JOIN competencies c ON uc.competency_id = c.id WHERE uc.user_id = " . $row['id']);
                                if ($comp_query->num_rows > 0) {
                                    while ($comp = $comp_query->fetch_assoc()) {
                                        echo "📘 " . $comp['title'];
                                        if ($_SESSION["role"] == "Admin" || $comp['assigned_by'] == $manager_id) {
                                            echo " <a href='?delete_competency=" . $comp['id'] . "&user_id=" . $row['id'] . "' class='text-danger'>❌</a>";
                                        }
                                        echo "<br>";
                                    }
                                } else {
                                    echo "<span class='text-muted'>No competencies</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                // Fetch assigned trainings
                                $train_query = $conn->query("SELECT t.id, t.title, ut.assigned_by FROM user_training ut JOIN training_programs t ON ut.training_id = t.id WHERE ut.user_id = " . $row['id']);
                                if ($train_query->num_rows > 0) {
                                    while ($train = $train_query->fetch_assoc()) {
                                        echo "📚 " . $train['title'];
                                        if ($_SESSION["role"] == "Admin" || $train['assigned_by'] == $manager_id) {
                                            echo " <a href='?delete_training=" . $train['id'] . "&user_id=" . $row['id'] . "' class='text-danger'>❌</a>";
                                        }
                                        echo "<br>";
                                    }
                                } else {
                                    echo "<span class='text-muted'>No trainings</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="assign_competency.php?employee_id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">📘 Assign Competency</a>
                                <a href="assign_training.php?employee_id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">📚 Assign Training</a>
                            </td>
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
