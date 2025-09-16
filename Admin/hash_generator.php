<?php
// The password you want to use
$plainPassword = 'Chin123';

// Generate a secure hash
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Display the hash
echo 'Your plain password is: ' . $plainPassword . '<br>';
echo 'Your hashed password is: ' . $hashedPassword;
?>