<?php
require_once 'auth.php';
require_once 'db_connect.php';

$msg = "";
$type = "";

if (isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    $del_stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $del_stmt->bind_param("i", $del_id);
    if ($del_stmt->execute()) {
        $msg = "Student deleted successfully.";
        $type = "success";
    } else {
        $msg = "Error deleting student.";
        $type = "error";
    }
    $del_stmt->close();
}

$search = $_GET['search'] ?? "";
$course_filter = $_GET['course'] ?? "";

$query = "SELECT id, name, email, mobile, course, academic_year FROM students WHERE 1=1";
$params = [];
$types = "";

if ($search !== "") {
    $query .= " AND (name LIKE ? OR email LIKE ?)";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($course_filter !== "") {
    $query .= " AND course = ?";
    $params[] = $course_filter;
    $types .= "s";
}

$query .= " ORDER BY id DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students | StudentHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="brand">
            <div class="logo">🎓</div>
            <div>
                <h1>StudentHub</h1>
                <p class="tagline">Admin Dashboard</p>
            </div>
        </div>
        <nav class="site-nav">
            <a href="home.php">Home</a>
            <a href="logout.php" class="btn">Logout</a>
        </nav>
    </header>
    <main class="container">
        <div class="card">
            <h2>Manage Students</h2>
            
            <?php if ($msg !== ""): ?>
                <div style="padding:10px; margin-bottom:15px; border-radius:5px; background:<?= $type === 'success' ? '#d4edda' : '#f8d7da' ?>; color:<?= $type === 'success' ? '#155724' : '#721c24' ?>;">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <form method="GET" action="manage_students.php" style="display:flex; gap:10px; margin-bottom:20px;">
                <input type="text" name="search" placeholder="Search name or email" value="<?= htmlspecialchars($search) ?>" style="flex:1;">
                <select name="course" style="width:200px;">
                    <option value="">All Courses</option>
                    <option value="CSE" <?= $course_filter === 'CSE' ? 'selected' : '' ?>>CSE</option>
                    <option value="CE" <?= $course_filter === 'CE' ? 'selected' : '' ?>>CE</option>
                    <option value="IT" <?= $course_filter === 'IT' ? 'selected' : '' ?>>IT</option>
                    <option value="ME" <?= $course_filter === 'ME' ? 'selected' : '' ?>>ME</option>
                </select>
                <button type="submit">Filter</button>
                <a href="add_student.php" class="btn" style="background:#28a745;">+ Add Student</a>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['mobile']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td>
                            <a href="edit_student.php?id=<?= $row['id'] ?>" style="color:#f39c12; margin-right:10px;">Edit</a>
                            <a href="manage_students.php?delete_id=<?= $row['id'] ?>" style="color:#e74c3c;" onclick="return confirm('Delete this student?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>