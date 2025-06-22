<?php
    include '../includes/dbconn.php';

    $sql = "SELECT id FROM leave_requests";
    $query = $mysqli->query($sql);
    echo "$query->num_rows";
?>