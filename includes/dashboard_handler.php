<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/config.php';

$conn = getConnection();

// Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success'=>false,'message'=>'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$firstname = trim($_POST['first_name'] ?? '');
$lastname  = trim($_POST['last_name'] ?? '');
$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = preg_replace('/\D/','', trim($_POST['phone'] ?? ''));
$password  = trim($_POST['password'] ?? '');
$confirmPassword  = trim($_POST['confirm_password'] ?? '');

$errors = [];

// Validation
if ($firstname==='' || $lastname==='') $errors[]='First/Last name required';
if (!preg_match('/^[\w.\-]{3,30}$/', $username)) $errors[]='Invalid username';
if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Invalid email';
if (!preg_match('/^\d{7,15}$/',$phone)) $errors[]='Phone must be 7-15 digits';

if ($password === '' && $confirmPassword === '') {
    // leave password unchanged
} 
// Case 2: only one filled → error
else if ($password === '' || $confirmPassword === '') {
    $errors[] = "Both password fields must be filled to change your password.";
} 
// Case 3: both filled → validate
else {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $errors[] = "Password must be ≥8 chars and include uppercase, lowercase, number and special char.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    // if no errors → hash & update password
}



$avatar_db_path = null;
if(isset($_FILES['avatar']) && $_FILES['avatar']['name']) {
    $avatar = $_FILES['avatar'];
    $allowed = ['image/jpeg','image/png','image/gif'];

    if (!in_array($avatar['type'],$allowed)) $errors[]='Avatar must be jpeg/png/gif';
    elseif ($avatar['size']>2*1024*1024) $errors[]='Avatar ≤2MB';
    else {
        $ext = pathinfo($avatar['name'], PATHINFO_EXTENSION);
        $avatar_name = uniqid('avatar_').'.'.$ext;
        $uploadDir = '../images/attachments/';
        if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);                   // making sure the folder exists
            $avatar_path = $uploadDir.$avatar_name;

        if(move_uploaded_file($avatar['tmp_name'],$avatar_path)) {
            $avatar_db_path = $avatar_path;
        } 
        else $errors[]='Avatar upload failed';
    }
}

if ($errors) {
    echo json_encode(['success'=>false,'errors'=>$errors]);
    exit;
}

// Check username/email uniqueness
$stmt = $conn->prepare("SELECT id FROM users WHERE (username=? OR email=?) AND id!=? LIMIT 1");
$stmt->bind_param('ssi',$username,$email,$user_id);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()) {
    echo json_encode(['success'=>false,'errors'=>['Username or email already exists']]);
    exit;
}
$stmt->close();

// Update user
$params = [$firstname,$lastname,$username,$email,$phone];
$types = 'sssss';
$sql = "UPDATE users SET first_name=?, last_name=?, username=?, email=?, phone=?";
if ($password) {
    $sql.=", password=?";
    $params[] = password_hash($password,PASSWORD_DEFAULT);
    $types .= 's';
}
if ($avatar_db_path) {
    $sql.=", avatar=?";
    $params[] = $avatar_db_path;
    $types .= 's';
}
$sql.=" WHERE id=?";
$params[] = $user_id;
$types.='i';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types,...$params);

if($stmt->execute()) {
    echo json_encode(['success'=>true]);
}
else {
    echo json_encode(['success'=>false,'errors'=>[$stmt->error]]);
}
$stmt->close();
$conn->close();
