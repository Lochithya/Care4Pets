<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/config.php';

$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'errors' => ['Invalid request']]);
    exit;
}

// CSRF check
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'errors' => ['Invalid CSRF token']]);
    exit;
}

// Collect inputs
$firstname = trim($_POST['firstname'] ?? '');
$lastname  = trim($_POST['lastname'] ?? '');
$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$confirm   = $_POST['confirm_password'] ?? '';
$phone     = preg_replace('/\D/', '', trim($_POST['phone'] ?? ''));

$errors = [];

// Server-side validation
if ($firstname === '' || $lastname === '') $errors[] = "First and last name required.";
if (!preg_match('/^[\w.\-]{3,30}$/', $username)) $errors[] = "Invalid username format.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (!preg_match('/^\d{7,15}$/', $phone)) $errors[] = "Phone number must be 7–15 digits.";
if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) $errors[] = "Password must be 8+ chars with upper, lower, number, special.";
if ($password !== $confirm) $errors[] = "Passwords do not match.";

$avatar_path = null;
if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {                  //  must check for both for the existence
    $avatar = $_FILES['avatar'];
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($avatar['type'], $allowed)) {
        $errors[] = "Avatar must be JPEG, PNG, or GIF.";
    } elseif ($avatar['size'] > 2 * 1024 * 1024) { // 2MB
        $errors[] = "Avatar must be ≤ 2MB.";
    } else {
        $ext = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));                    // generating a unique name for prevent overwriting
        $avatar_name = uniqid('avatar_') . '.' . $ext;

        $uploadDir = '../images/attachments/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);                 // making sure the folder exists
            $avatar_path = $uploadDir . $avatar_name;

        if (!move_uploaded_file($avatar['tmp_name'], $avatar_path)) {
            $errors[] = "Failed to upload avatar.";
        }

        $avatar_db_path = null ;
        if(!empty($_FILES['avatar']['name'])){
            $avatar_db_path = '../images/attachments/'.$avatar_name ;
        }
    }
}


if ($errors) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Check uniqueness
$stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->fetch_assoc()) {
    echo json_encode(['success' => false, 'errors' => ['Username or email already exists.']]);
    exit;
}
$stmt->close();

// Insert user
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, phone , avatar) VALUES (?, ?, ?, ?, ?, ?,?)");
$stmt->bind_param("sssssss", $firstname, $lastname, $username, $email, $hash, $phone,$avatar_db_path);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registration successful.']);
} else {
    echo json_encode(['success' => false, 'errors' => [$stmt->error]]);
}

$stmt->close();
$conn->close();
