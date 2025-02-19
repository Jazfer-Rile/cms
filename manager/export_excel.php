<?php
session_start();
include("../includes/config.php");

// Redirect if not manager
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Manager") {
    header("Location: ../auth/login.php");
    exit();
}

// Load PhpSpreadsheet Library
require '../vendor/autoload.php'; // Ensure you have installed PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Column Headers
$headers = ["ID", "Name", "Email", "Assigned Competencies", "Assigned Training", "Completed Training"];
$column = "A";
foreach ($headers as $header) {
    $sheet->setCellValue($column . "1", $header);
    $column++;
}

// Fetch employees
$query = $conn->query("SELECT id, name, email FROM users WHERE role = 'Employee'");

$rowIndex = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->setCellValue("A" . $rowIndex, $row['id']);
    $sheet->setCellValue("B" . $rowIndex, $row['name']);
    $sheet->setCellValue("C" . $rowIndex, $row['email']);
    $sheet->setCellValue("D" . $rowIndex, getCompetencyCount($row['id']));
    $sheet->setCellValue("E" . $rowIndex, getTrainingCount($row['id']));
    $sheet->setCellValue("F" . $rowIndex, getCompletedTrainingCount($row['id']));
    $rowIndex++;
}

// Export to Excel
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"employee_progress.xlsx\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit();

// ✅ Define Missing Functions
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
