<?php
require_once 'c:/xampp/htdocs/Care4Pets/admin/config.php';
$res = mysqli_query($conn, "DESCRIBE products");
while($row = mysqli_fetch_assoc($res)) { print_r($row); }
?>
