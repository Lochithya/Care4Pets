<?php
header('Content-Type: application/json');
require_once '../includes/config.php'; // should provide getConnection()

$conn = getConnection();

$username = trim($_POST['username'] ?? '');
if ($username === '') {
    echo json_encode(['success' => false, 'error' => 'empty']);
    exit;
}

$stmt = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute() ;                                                            // binds the username and runs the query
$result = $stmt->get_result();
$exists = $result->fetch_assoc();

echo json_encode(['success' => $exists ? false : true]);

?>