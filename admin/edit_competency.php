<?php
session_start();
include("../includes/config.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Check if competency ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_competencies.php");
    exit();
}

$competency_id = $_GET['id'];

// Fetch competency details
$stmt = $conn->prepare("SELECT title, description FROM competencies WHERE id = ?");
$stmt->bind_param("i", $competency_id);
$stmt->execute();
$result = $stmt->get_result();
$competency = $result->fetch_assoc();

// Redirect if competency not found
if (!$competency) {
    header("Location: manage_competencies.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];

    $stmt = $conn->prepare("UPDATE competencies SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $competency_id);
    $stmt->execute();

    header("Location: manage_competencies.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Competency</title>
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
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3">✏ Edit Competency</h2>
            <form method="POST" class="p-4 shadow bg-white rounded">
                <div class="mb-3">
                    <label class="form-label">📘 Competency Title:</label>
                    <input type="text" name="title" class="form-control" value="<?= $competency['title']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">📝 Description:</label>
                    <textarea name="description" class="form-control" rows="3" required><?= $competency['description']; ?></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">✅ Update Competency</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
