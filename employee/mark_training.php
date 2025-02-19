<?php
session_start();
include("../includes/config.php");

// Redirect if not employee
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Employee") {
    header("Location: ../auth/login.php");
    exit();
}

// Check if training ID is provided
if (!isset($_GET['training_id']) || empty($_GET['training_id'])) {
    header("Location: dashboard.php");
    exit();
}

$training_id = $_GET['training_id'];
$user_id = $_SESSION["user_id"];

// Update training status
$stmt = $conn->prepare("UPDATE user_training SET status = 'completed' WHERE user_id = ? AND training_id = ?");
$stmt->bind_param("ii", $user_id, $training_id);
$stmt->execute();

header("Location: dashboard.php");
exit();
?>
