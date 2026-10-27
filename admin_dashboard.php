<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$student_count = 0;
$sql_students = "SELECT COUNT(*) as count FROM students WHERE role='student'";
$res_students = mysqli_query($conn, $sql_students);
if ($row = mysqli_fetch_assoc($res_students)) {
    $student_count = $row['count'];
}

$event_count = 0;
$sql_events = "SELECT COUNT(*) as count FROM events";
$res_events = mysqli_query($conn, $sql_events);
if ($row = mysqli_fetch_assoc($res_events)) {
    $event_count = $row['count'];
}

$sql_latest = "SELECT id, name, email, created_at FROM students WHERE role='student' ORDER BY id DESC LIMIT 5";
$latest_students = mysqli_query($conn, $sql_latest);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | StudentHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="brand">
            <div class="logo">🎓</div>
            <div>
                <h1>StudentHub Admin</h1>
                <p class="tagline">Welcome, <?php echo $_SESSION['student_name']; ?></p>
            </div>
        </div>
        <nav class="site-nav">
            <?php if ($_SESSION['role'] === 'admin') { ?>
                <a href="admin_dashboard.php" class="active">Dashboard</a>
                <a href="manage_students.php">Manage Students</a>
                <a href="manage_events.php">Manage Events</a>
            <?php } else { ?>
                <a href="home.php">Home</a>
                <a href="course.html">Courses</a>
            <?php } ?>
            <a href="logout.php" class="btn" style="background: #e74c3c;">Logout</a>
        </nav>
    </header>

    <main class="container">
        <div class="grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
            <div class="card" style="text-align: center;">
                <h3>Total Students</h3>
                <h1 style="font-size: 48px; color: var(--primary); margin: 10px 0;"><?php echo $student_count; ?></h1>
            </div>
            <div class="card" style="text-align: center;">
                <h3>Total Events</h3>
                <h1 style="font-size: 48px; color: var(--accent); margin: 10px 0;"><?php echo $event_count; ?></h1>
            </div>
        </div>

        <div class="card">
            <h2>Latest Registrations</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = mysqli_fetch_assoc($latest_students)) { ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td><?php echo $student['created_at']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>