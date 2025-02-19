<?php
// Start session if not started
if (!isset($_SESSION)) {
    session_start();
}

// Get the logged-in user's role and name
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; // Get user's name

// Determine dashboard URL based on user role
$dashboard_link = "#"; // Default (Guest or undefined role)
if ($role == "Admin") {
    $dashboard_link = "../admin/dashboard.php";
} elseif ($role == "Manager") {
    $dashboard_link = "../manager/dashboard.php";
} elseif ($role == "Employee") {
    $dashboard_link = "../employee/dashboard.php";
}
?>

<!-- Sidebar Navigation -->
<nav class="d-flex flex-column bg-dark text-white vh-100 p-3 position-fixed" style="width: 250px;">
    <h3 class="text-center">CMS</h3>
    <hr>

    <!-- Display logged-in user's name -->
    <div class="text-center mb-4">
        <span class="d-block text-white" style="font-size: 1.2rem;">Welcome, <?= $user_name; ?>!</span>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="<?= $dashboard_link; ?>" class="nav-link text-white">📊 Dashboard</a>
        </li>

        <?php if ($role == "Admin") : ?>
        <li class="nav-item">
            <a href="../admin/manage_users.php" class="nav-link text-white">👤 Manage Users</a>
        </li>
        <li class="nav-item">
            <a href="../admin/manage_competencies.php" class="nav-link text-white">📘 Manage Competencies</a>
        </li>
        <li class="nav-item">
            <a href="../admin/manage_training.php" class="nav-link text-white">📚 Manage Trainings</a>
        </li>
        <li class="nav-item">
            <a href="../admin/reports.php" class="nav-link text-white">📜 Reports</a>
        </li>
        <li class="nav-item">
            <a href="../admin/admin_logs.php" class="nav-link text-white">📜 Logs</a>
        </li>

        <?php endif; ?>

        <?php if ($role == "Manager") : ?>
        <li class="nav-item">
            <a href="../manager/employees.php" class="nav-link text-white">👥 Employees</a>
        </li>
        <li class="nav-item">
            <a href="../manager/progress_report.php" class="nav-link text-white">📜 Reports</a>
        </li>
        <?php endif; ?>

        <?php if ($role == "Employee") : ?>
        <li class="nav-item">
            <a href="../employee/view_competencies.php" class="nav-link text-white">🏆 My Competencies</a>
        </li>
        <li class="nav-item">
            <a href="../employee/view_training.php" class="nav-link text-white">📚 My Training</a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="../auth/logout.php" class="nav-link text-danger">🚪 Logout</a>
        </li>
    </ul>
</nav>
