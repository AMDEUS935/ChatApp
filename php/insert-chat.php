<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header("Location: ../login.php");
    exit;
}

include_once "config.php";

// outgoing_id는 클라이언트 값을 믿지 않고 세션으로 강제
$outgoing_id = (int)($_SESSION['unique_id'] ?? 0);

// incoming_id / message는 클라이언트 입력
$incoming_id = (int)($_POST['incoming_id'] ?? 0);
$message     = trim($_POST['message'] ?? "");

// 응답은 JSON으로 통일
header('Content-Type: application/json; charset=utf-8');

// 기본 값 검증
if ($outgoing_id <= 0 || $incoming_id <= 0) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "bad_id"], JSON_UNESCAPED_UNICODE);
    exit;
}

// 메시지 검증: 빈값/공백만 방지
if ($message === "") {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "empty"], JSON_UNESCAPED_UNICODE);
    exit;
}

// 메시지 길이 제한 (서버 최종 방어)
$MAX_LEN = 500;
$len = function_exists('mb_strlen') ? mb_strlen($message, 'UTF-8') : strlen($message);

if ($len > $MAX_LEN) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "too_long", "max" => $MAX_LEN], JSON_UNESCAPED_UNICODE);
    exit;
}

// 저장 (Prepared Statement)
$sql = "INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => "prepare_failed"], JSON_UNESCAPED_UNICODE);
    exit;
}

mysqli_stmt_bind_param($stmt, "iis", $outgoing_id, $incoming_id, $message);
mysqli_stmt_execute($stmt);

$new_id = mysqli_insert_id($conn);
echo json_encode(["ok" => true, "msg_id" => $new_id], JSON_UNESCAPED_UNICODE);
exit;
?>
