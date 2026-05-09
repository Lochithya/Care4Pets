<?php
require_once 'c:/xampp/htdocs/Care4Pets/admin/config.php';
$res = mysqli_query($conn, "SELECT DISTINCT status FROM orders");
while($row = mysqli_fetch_assoc($res)) { echo "[" . $row['status'] . "]\n"; }
?>
