<?php
session_start();
include("../includes/config.php");
require '../vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Redirect if not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header for the sheet
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Assigned Competencies');
$sheet->setCellValue('E1', 'Assigned Training');
$sheet->setCellValue('F1', 'Completed Training');
$sheet->setCellValue('G1', 'Completion Rate');

// Fetch employees and their counts
$query = $conn->query("SELECT id, name, email FROM users WHERE role = 'Employee'");
$rowIndex = 2; // Start from row 2 because row 1 is the header
while ($row = $query->fetch_assoc()) {
    // Get counts for competencies, training, and completed training
    $competencyCount = getCompetencyCount($row['id']);
    $trainingCount = getTrainingCount($row['id']);
    $completedTrainingCount = getCompletedTrainingCount($row['id']);
    $completionRate = ($trainingCount > 0) ? round(($completedTrainingCount / $trainingCount) * 100, 2) . '%' : '0%';

    // Write data to the sheet
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['name']);
    $sheet->setCellValue('C' . $rowIndex, $row['email']);
    $sheet->setCellValue('D' . $rowIndex, $competencyCount);
    $sheet->setCellValue('E' . $rowIndex, $trainingCount);
    $sheet->setCellValue('F' . $rowIndex, $completedTrainingCount);
    $sheet->setCellValue('G' . $rowIndex, $completionRate);
    
    $rowIndex++;
}

// Write the file to the output
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="employee_progress_report.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
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
