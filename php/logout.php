<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header("location: ../login.php");
    exit;
}

include_once "config.php";

$uid = (int)$_SESSION['unique_id'];
$status = "오프라인";

// 타 사용자 로그아웃 방지
$stmt = $conn->prepare("UPDATE users SET status = ? WHERE unique_id = ?");
if ($stmt) {
    $stmt->bind_param("si", $status, $uid);
    $stmt->execute();
    $stmt->close();
}

session_unset();
session_destroy();

header("location: ../login.php");
exit;
?>
