<?php
// Start session if not started
if (!isset($_SESSION)) {
    session_start();
}

// Get the logged-in user's role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';
?>

<!-- Sidebar Navigation -->
<nav class="d-flex flex-column bg-dark text-white vh-100 p-3 position-fixed" style="width: 250px;">
    <h3 class="text-center">CMS</h3>
    <hr>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="../admin/dashboard.php" class="nav-link text-white">📊 Dashboard</a>
        </li>

        <?php if ($role == "Admin") : ?>
        <li class="nav-item">
            <a href="../admin/manage_users.php" class="nav-link text-white">👤 Manage Users</a>
        </li>
        <li class="nav-item">
            <a href="../admin/manage_competencies.php" class="nav-link text-white">📘 Manage Competencies</a>
        </li>
        <li class="nav-item">
            <a href="../admin/reports.php" class="nav-link text-white">📜 Reports</a>
        </li>
        <?php endif; ?>

        <?php if ($role == "Manager") : ?>
        <li class="nav-item">
            <a href="../manager/dashboard.php" class="nav-link text-white">📊 Manager Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="../manager/assign_competencies.php" class="nav-link text-white">🎯 Assign Competencies</a>
        </li>
        <?php endif; ?>

        <?php if ($role == "Employee") : ?>
        <li class="nav-item">
            <a href="../employee/dashboard.php" class="nav-link text-white">🏆 My Competencies</a>
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
