<?php
session_start();
include("../includes/config.php");

// Redirect if not manager
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Manager") {
    header("Location: ../auth/login.php");
    exit();
}

// Get Employee ID
if (!isset($_GET['employee_id']) || empty($_GET['employee_id'])) {
    header("Location: employees.php");
    exit();
}

$employee_id = $_GET['employee_id'];
$manager_id = $_SESSION["user_id"];

// Fetch training programs list
$trainings = $conn->query("SELECT * FROM training_programs");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $training_id = $_POST['training_id'];

    // Assign training with assigned_by
    $stmt = $conn->prepare("INSERT INTO user_training (user_id, assigned_by, training_id, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("iii", $employee_id, $manager_id, $training_id);
    $stmt->execute();

    // Log the activity
    // Fetch training title for logging purposes
    $training_query = $conn->prepare("SELECT title FROM training_programs WHERE id = ?");
    $training_query->bind_param("i", $training_id);
    $training_query->execute();
    $training_result = $training_query->get_result();
    $training_title = $training_result->fetch_assoc()['title'];

    // Log the action in the activity_logs table
    $log_query = $conn->prepare("INSERT INTO activity_logs (action_type, action_description, assigned_by, assigned_to) VALUES (?, ?, ?, ?)");
    $action_type = "Training";
    $action_description = "Assigned Training '{$training_title}' to Employee ID {$employee_id}";
    $log_query->bind_param("ssii", $action_type, $action_description, $_SESSION["user_id"], $employee_id);
    $log_query->execute();

    // Redirect back to employees page
    header("Location: employees.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assign Training</title>
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
        /* Form Styling */
        .form-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-warning {
            font-size: 1.2rem;
            padding: 12px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 text-center">📚 Assign Training</h2>

            <div class="form-container">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Select Training:</label>
                        <select name="training_id" class="form-control form-control-lg" required>
                            <?php while ($row = $trainings->fetch_assoc()) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">✅ Assign Training</button>
                </form>
            </div>

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
