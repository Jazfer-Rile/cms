<?php
session_start();
include("../includes/config.php");
require("../includes/fpdf/fpdf.php");

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(190, 10, "Employee Progress Report", 1, 1, "C");
$pdf->Ln(10);

// Table Headers
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30, 10, "ID", 1);
$pdf->Cell(50, 10, "Name", 1);
$pdf->Cell(50, 10, "Email", 1);
$pdf->Cell(20, 10, "Competencies", 1);
$pdf->Cell(20, 10, "Training", 1);
$pdf->Cell(20, 10, "Completed", 1);
$pdf->Cell(30, 10, "Completion Rate", 1);
$pdf->Ln();

// Fetch employees and their counts
$query = $conn->query("SELECT id, name, email FROM users WHERE role = 'Employee'");
while ($row = $query->fetch_assoc()) {
    $competencyCount = getCompetencyCount($row['id']);
    $trainingCount = getTrainingCount($row['id']);
    $completedTrainingCount = getCompletedTrainingCount($row['id']);
    $completionRate = ($trainingCount > 0) ? round(($completedTrainingCount / $trainingCount) * 100, 2) . '%' : '0%';

    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(30, 10, $row['id'], 1);
    $pdf->Cell(50, 10, $row['name'], 1);
    $pdf->Cell(50, 10, $row['email'], 1);
    $pdf->Cell(20, 10, $competencyCount, 1);
    $pdf->Cell(20, 10, $trainingCount, 1);
    $pdf->Cell(20, 10, $completedTrainingCount, 1);
    $pdf->Cell(30, 10, $completionRate, 1);
    $pdf->Ln();
}

$pdf->Output();
exit();

// Helper Functions
function getCompetencyCount($user_id) {
    global $conn;
    $query = $conn->query("SELECT COUNT(*) AS total FROM user_competencies WHERE user_id = $user_id");
    return $query->fetch_assoc()['total'];
}

function getTrainingCount($user_id) {
    global $conn;
    $query = $conn->query("SELECT COUNT(*) AS total FROM user_training WHERE user_id = $user_id");
    return $query->fetch_assoc()['total'];
}

function getCompletedTrainingCount($user_id) {
    global $conn;
    $query = $conn->query("SELECT COUNT(*) AS total FROM user_training WHERE user_id = $user_id AND status = 'Completed'");
    return $query->fetch_assoc()['total'];
}
?>
