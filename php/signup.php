<?php
session_start();
include_once "config.php";

$uname = trim($_POST['uname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password_plain = $_POST['password'] ?? '';

if ($uname === '' || $email === '' || $password_plain === '') {
    echo "필수 정보입니다.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "{$email} - 이메일 형식이 잘못되었습니다.";
    exit;
}

// 이메일 중복 체크
$stmt = $conn->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "{$email} - 이미 사용중인 이메일입니다.";
    exit;
}
$stmt->close();

// 이미지 업로드 체크
if (!isset($_FILES['image'])) {
    echo "이미지 파일을 선택해주세요!";
    exit;
}

$img_name  = $_FILES['image']['name'];
$tmp_name  = $_FILES['image']['tmp_name'];
$img_size  = $_FILES['image']['size'];
$img_error = $_FILES['image']['error'];

if ($img_error !== 0) {
    echo "이미지 업로드 오류!";
    exit;
}

// 업로드 용량 제한 (예: 2MB)
if ($img_size > 2 * 1024 * 1024) {
    echo "이미지 용량이 너무 큽니다. (최대 2MB)";
    exit;
}

// 실제 이미지인지 확인
if (@getimagesize($tmp_name) === false) {
    echo "이미지 파일만 업로드 가능합니다.";
    exit;
}

$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
$img_ex_lc = strtolower($img_ex);

$allowed_exs = array("jpg", "jpeg", "png");
if (!in_array($img_ex_lc, $allowed_exs)) {
    echo "jpg, jpeg, png 파일만 업로드 가능합니다.";
    exit;
}

// 사용자 정보 생성
$status = "온라인";

// unique_id 생성 (PHP 7+)
$random_id = random_int(10000000, 99999999);
// 만약 random_int가 안 되면 아래로 교체:
// $random_id = rand(10000000, 99999999);

$new_img_name = $random_id . "." . $img_ex_lc;

// 기존 파일 있으면 정리
@unlink("images/" . $new_img_name);

// 업로드 이동
if (!move_uploaded_file($tmp_name, "images/" . $new_img_name)) {
    echo "이미지 저장 실패!";
    exit;
}

// 비밀번호 해싱 저장
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

// DB 저장 
$stmt = $conn->prepare("
    INSERT INTO users (unique_id, uname, email, password, img, status)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isssss", $random_id, $uname, $email, $password_hash, $new_img_name, $status);

if (!$stmt->execute()) {
    @unlink("images/" . $new_img_name);
    echo "뭔가 잘못됨!!!";
    exit;
}
$stmt->close();

// 가입 성공 -> 세션 설정
$_SESSION['unique_id'] = $random_id;
echo "success";
?>
