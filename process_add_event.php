<?php
session_start();
include 'db_connect.php';

$event_name = $_POST['event_name'];
$event_date = $_POST['event_date'];
$venue = $_POST['venue'];

$poster = $_FILES['poster']['name'];
$tmp_name = $_FILES['poster']['tmp_name'];
$size = $_FILES['poster']['size'];
$type = $_FILES['poster']['type'];

$allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
$max_size = 2 * 1024 * 1024; 

if (in_array($type, $allowed_types) && $size <= $max_size) {
    if (!is_dir('uploads')) {
        mkdir('uploads');
    }
    
    $upload_path = 'uploads/' . basename($poster);
    move_uploaded_file($tmp_name, $upload_path);

    $sql = "INSERT INTO events (event_name, event_date, venue, poster) 
            VALUES ('$event_name', '$event_date', '$venue', '$poster')";
    mysqli_query($conn, $sql);
}

header("Location: manage_events.php");
?>