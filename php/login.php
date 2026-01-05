<?php
session_start();
include_once "config.php";

$email = trim($_POST['email'] ?? '');
$password_plain = $_POST['password'] ?? '';

if ($email === '' || $password_plain === '') {
    echo "필수 정보입니다.";
    exit;
}

// 이메일로 사용자 조회 
$stmt = $conn->prepare("SELECT unique_id, password FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($unique_id, $password_hash);
    $stmt->fetch();

    // 해시 검증
    if (password_verify($password_plain, $password_hash)) {
        session_regenerate_id(true);

        $uid = (int)$unique_id;
        $status = "온라인";

        $stmt2 = $conn->prepare("UPDATE users SET status = ? WHERE unique_id = ?");
        $stmt2->bind_param("si", $status, $uid);

        if ($stmt2->execute()) {
            $_SESSION['unique_id'] = $uid;
            echo "success";
        } else {
            echo "로그인 처리 중 오류가 발생했습니다.";
        }
        $stmt2->close();
    } else {
        echo "이메일이나 비밀번호가 맞지 않습니다..";
    }
} else {
    echo "이메일이나 비밀번호가 맞지 않습니다..";
}

$stmt->close();
?>
