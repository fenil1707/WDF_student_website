<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM events WHERE id = $id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <div class="card" style="max-width:500px; margin:40px auto;">
            <h2>Edit Event</h2>
            <form method="POST" action="process_edit_event.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                
                <label>Event Name</label>
                <input type="text" name="event_name" value="<?php echo $event['event_name']; ?>" required>
                
                <label>Event Date</label>
                <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
                
                <label>Venue</label>
                <input type="text" name="venue" value="<?php echo $event['venue']; ?>" required>
                
                <label>Update Poster (Optional)</label>
                <input type="file" name="poster" accept="image/png, image/jpeg">
                
                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="submit">Update Event</button>
                    <a href="manage_events.php" class="btn" style="background:#6c757d;">Back</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>