<?php
session_start();
include 'db_connect.php';

$id = $_POST['id'];
$event_name = $_POST['event_name'];
$event_date = $_POST['event_date'];
$venue = $_POST['venue'];

$sql = "UPDATE events SET event_name='$event_name', event_date='$event_date', venue='$venue' WHERE id=$id";
mysqli_query($conn, $sql);

if (isset($_FILES['poster']) && $_FILES['poster']['size'] > 0) {
    $poster = $_FILES['poster']['name'];
    $tmp_name = $_FILES['poster']['tmp_name'];
    $size = $_FILES['poster']['size'];
    $type = $_FILES['poster']['type'];

    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
    $max_size = 2 * 1024 * 1024;

    if (in_array($type, $allowed_types) && $size <= $max_size) {
        $upload_path = 'uploads/' . basename($poster);
        move_uploaded_file($tmp_name, $upload_path);

        $sql_img = "UPDATE events SET poster='$poster' WHERE id=$id";
        mysqli_query($conn, $sql_img);
    }
}

header("Location: manage_events.php");
?>