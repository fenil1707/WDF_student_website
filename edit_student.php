<?php
require_once 'auth.php';
require_once 'db_connect.php';

$msg = "";
$type = "";
$id = $_GET['id'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $name = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars(trim($_POST["mobile"] ?? ""));
    $course = htmlspecialchars(trim($_POST["course"] ?? ""));

    if ($name && $email && $mobile && $course) {
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, mobile=?, course=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $mobile, $course, $id);
        
        if ($stmt->execute()) {
            $msg = "Student updated successfully.";
            $type = "success";
        } else {
            $msg = "Error updating student.";
            $type = "error";
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT id, name, email, mobile, course FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    header("Location: manage_students.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student | StudentHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <div class="card" style="max-width:500px; margin:40px auto;">
            <h2>Edit Student</h2>
            
            <?php if ($msg !== ""): ?>
                <div style="padding:10px; margin-bottom:15px; border-radius:5px; background:<?= $type === 'success' ? '#d4edda' : '#f8d7da' ?>; color:<?= $type === 'success' ? '#155724' : '#721c24' ?>;">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit_student.php?id=<?= $student['id'] ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">
                
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
                
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
                
                <label>Mobile</label>
                <input type="text" name="mobile" value="<?= htmlspecialchars($student['mobile']) ?>" required>
                
                <label>Course</label>
                <select name="course" required>
                    <option value="CSE" <?= $student['course'] === 'CSE' ? 'selected' : '' ?>>CSE</option>
                    <option value="CE" <?= $student['course'] === 'CE' ? 'selected' : '' ?>>CE</option>
                    <option value="IT" <?= $student['course'] === 'IT' ? 'selected' : '' ?>>IT</option>
                    <option value="ME" <?= $student['course'] === 'ME' ? 'selected' : '' ?>>ME</option>
                </select>
                
                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="submit">Update Student</button>
                    <a href="manage_students.php" class="btn" style="background:#6c757d;">Back</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>