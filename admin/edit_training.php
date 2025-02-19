<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Check if training ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_training.php");
    exit();
}

$training_id = $_GET['id'];

// Fetch training details
$stmt = $conn->prepare("SELECT title, description FROM training_programs WHERE id = ?");
$stmt->bind_param("i", $training_id);
$stmt->execute();
$result = $stmt->get_result();
$training = $result->fetch_assoc();

// Redirect if training not found
if (!$training) {
    header("Location: manage_training.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];

    $stmt = $conn->prepare("UPDATE training_programs SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $training_id);
    $stmt->execute();

    header("Location: manage_training.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Training</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        /* Sidebar & Content Layout */
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;  /* Remove default margin */
        }
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 10px;
            z-index: 10; /* Keep sidebar on top */
        }
        .content {
            margin-left: 250px; /* Space for sidebar */
            padding: 20px;
            width: calc(100% - 250px); /* Adjust width */
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

        /* Form Styling */
        .form-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <div class="content">
        <div class="container">
            <h2 class="mt-3">✏ Edit Training Program</h2>

            <form method="POST" class="p-4 shadow bg-white rounded">
                <div class="mb-3">
                    <label class="form-label">Training Title:</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($training['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description:</label>
                    <textarea name="description" class="form-control" required><?= htmlspecialchars($training['description']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-warning w-100">✅ Update Training</button>
            </form>

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
