<?php
require_once 'auth.php';
require_once 'db_connect.php';

$msg = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars(trim($_POST["mobile"] ?? ""));
    $course = htmlspecialchars(trim($_POST["course"] ?? ""));
    $password = password_hash("default123", PASSWORD_DEFAULT);

    if ($name && $email && $mobile && $course) {
        $stmt = $conn->prepare("INSERT INTO students (name, email, mobile, password_hash, course) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $mobile, $password, $course);
        
        if ($stmt->execute()) {
            $msg = "Student added successfully. Default password is 'default123'.";
            $type = "success";
        } else {
            $msg = "Error adding student. Email may already exist.";
            $type = "error";
        }
        $stmt->close();
    } else {
        $msg = "All fields are required.";
        $type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student | StudentHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <div class="card" style="max-width:500px; margin:40px auto;">
            <h2>Add New Student</h2>
            
            <?php if ($msg !== ""): ?>
                <div style="padding:10px; margin-bottom:15px; border-radius:5px; background:<?= $type === 'success' ? '#d4edda' : '#f8d7da' ?>; color:<?= $type === 'success' ? '#155724' : '#721c24' ?>;">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="add_student.php">
                <label>Name</label>
                <input type="text" name="name" required>
                
                <label>Email</label>
                <input type="email" name="email" required>
                
                <label>Mobile</label>
                <input type="text" name="mobile" required>
                
                <label>Course</label>
                <select name="course" required>
                    <option value="CSE">CSE</option>
                    <option value="CE">CE</option>
                    <option value="IT">IT</option>
                    <option value="ME">ME</option>
                </select>
                
                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="submit">Save Student</button>
                    <a href="manage_students.php" class="btn" style="background:#6c757d;">Back</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>