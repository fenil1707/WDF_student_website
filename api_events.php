<?php
header("Content-Type: application/json");
include 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method == 'GET') {
    $sql = "SELECT id, event_name, event_date, venue FROM events";
    $result = mysqli_query($conn, $sql);
    $events = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    echo json_encode($events);
} 
elseif ($method == 'POST') {
    $name = $input['event_name'];
    $date = $input['event_date'];
    $venue = $input['venue'];
    
    $sql = "INSERT INTO events (event_name, event_date, venue) VALUES ('$name', '$date', '$venue')";
    mysqli_query($conn, $sql);
    echo json_encode(["status" => "success"]);
} 
elseif ($method == 'PUT') {
    $id = $input['id'];
    $name = $input['event_name'];
    $date = $input['event_date'];
    $venue = $input['venue'];
    
    $sql = "UPDATE events SET event_name='$name', event_date='$date', venue='$venue' WHERE id=$id";
    mysqli_query($conn, $sql);
    echo json_encode(["status" => "success"]);
} 
elseif ($method == 'DELETE') {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM events WHERE id=$id";
    mysqli_query($conn, $sql);
    echo json_encode(["status" => "success"]);
}
?>