<?php
session_start();
include 'db_connect.php';

$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <div class="card">
            <h2>Manage Events</h2>
            <a href="add_event.html" class="btn" style="background:#28a745; margin-bottom: 20px;">+ Add Event</a>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Poster</th>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <?php if ($row['poster'] != "") { ?>
                                <img src="uploads/<?php echo $row['poster']; ?>" width="60" height="60" style="object-fit:cover; border-radius:4px;">
                            <?php } ?>
                        </td>
                        <td><?php echo $row['event_name']; ?></td>
                        <td><?php echo $row['event_date']; ?></td>
                        <td><?php echo $row['venue']; ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $row['id']; ?>" style="color:#f39c12; margin-right:10px;">Edit</a>
                            <a href="process_delete_event.php?id=<?php echo $row['id']; ?>" style="color:red;">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>