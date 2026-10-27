<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'];

$sql_select = "SELECT poster FROM events WHERE id = $id";
$result = mysqli_query($conn, $sql_select);
$row = mysqli_fetch_assoc($result);

if ($row && $row['poster'] != "") {
    $file_path = 'uploads/' . $row['poster'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

$sql = "DELETE FROM events WHERE id = $id";
mysqli_query($conn, $sql);

header("Location: manage_events.php");
?>