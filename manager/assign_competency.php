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

// Fetch competencies list
$competencies = $conn->query("SELECT * FROM competencies");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $competency_id = $_POST['competency_id'];

    // Insert the competency assignment into the database
    $stmt = $conn->prepare("INSERT INTO user_competencies (user_id, competency_id, assigned_by) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $employee_id, $competency_id, $_SESSION["user_id"]);
    $stmt->execute();

    // Log the activity
    // Fetch the competency title for logging purposes
    $competency_query = $conn->prepare("SELECT title FROM competencies WHERE id = ?");
    $competency_query->bind_param("i", $competency_id);
    $competency_query->execute();
    $competency_result = $competency_query->get_result();
    $competency_title = $competency_result->fetch_assoc()['title'];

    // Log the action in the activity_logs table
    $log_query = $conn->prepare("INSERT INTO activity_logs (action_type, action_description, assigned_by, assigned_to) VALUES (?, ?, ?, ?)");
    $action_type = "Competency";
    $action_description = "Assigned Competency '{$competency_title}' to Employee ID {$employee_id}";
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
    <title>Assign Competency</title>
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
            width: 100%;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 1.2rem;
            padding: 12px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <h2 class="mt-3 text-center">📘 Assign Competency</h2>

        <div class="form-container">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Competency:</label>
                    <select name="competency_id" class="form-control form-control-lg" required>
                        <?php while ($row = $competencies->fetch_assoc()) : ?>
                            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">✅ Assign Competency</button>
            </form>
        </div>

    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
