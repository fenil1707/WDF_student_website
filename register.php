<?php
require_once 'db_connect.php';

$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formType = $_POST["form_type"] ?? "";

    if ($formType === "registration") {
        
        $name = htmlspecialchars(trim($_POST["name"] ?? ""));
        $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
        $mobile = htmlspecialchars(trim($_POST["mobile"] ?? ""));
        $password = $_POST["password"] ?? "";
        $course = htmlspecialchars(trim($_POST["course"] ?? ""));
        $year = htmlspecialchars(trim($_POST["year"] ?? ""));
        $gender = htmlspecialchars(trim($_POST["gender"] ?? ""));

        if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[0-9]{10}$/", $mobile) || $password === "") {
            $message = "Invalid registration details. Please check your inputs.";
            $type = "error";
        } else {
            $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = "Email is already registered. Please login or use another email.";
                $type = "error";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $insertStmt = $conn->prepare("INSERT INTO students (name, email, mobile, password_hash, course, academic_year, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insertStmt->bind_param("sssssss", $name, $email, $mobile, $hashedPassword, $course, $year, $gender);

                if ($insertStmt->execute()) {
                    $message = "Registration submitted successfully! Welcome, " . $name . ".";
                    $type = "success";
                } else {
                    $message = "Error saving registration data.";
                    $type = "error";
                }
                $insertStmt->close();
            }
            $stmt->close();
        }

    } elseif ($formType === "contact") {
        
        $name = htmlspecialchars(trim($_POST["name"] ?? ""));
        $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(trim($_POST["subject"] ?? ""));
        $messageText = htmlspecialchars(trim($_POST["message"] ?? ""));

        if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $messageText === "") {
            $message = "Invalid contact details. Please complete all required fields.";
            $type = "error";
        } else {
            $file = __DIR__ . "/contacts.json";
            $records = [];

            if (file_exists($file)) {
                $data = json_decode(file_get_contents($file), true);
                if (is_array($data)) {
                    $records = $data;
                }
            }

            $records[] = [
                "name" => $name,
                "email" => $email,
                "subject" => $subject,
                "message" => $messageText,
                "submitted_at" => date("Y-m-d H:i:s")
            ];

            $json = json_encode($records, JSON_PRETTY_PRINT);
            if ($json !== false && file_put_contents($file, $json, LOCK_EX) !== false) {
                $message = "Contact message submitted successfully! We will get back to you soon.";
                $type = "success";
            } else {
                $message = "Error saving contact message.";
                $type = "error";
            }
        }
    } else {
        $message = "Invalid form submission.";
        $type = "error";
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Status | StudentHub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .status-box {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="brand">
            <div class="logo">🎓</div>
            <div>
                <h1>StudentHub</h1>
                <p class="tagline">Your College Student Portal</p>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="card" style="text-align: center; padding: 40px;">
            <h2>Submission Status</h2>

            <?php if ($message !== ""): ?>
                <div class="status-box <?= e($type) ?>">
                    <?= e($message) ?>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                <a href="register.html" class="btn">Back to Registration</a>
                <a href="contact.html" class="btn" style="background: var(--muted);">Back to Contact</a>
            </div>
        </section>
    </main>
</body>
</html>