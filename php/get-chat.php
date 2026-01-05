<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    http_response_code(401);
    exit;
}

$outgoing_id = (int)($_SESSION['unique_id'] ?? 0);
$incoming_id = (int)($_POST['incoming_id'] ?? 0);
$mode        = $_POST['mode'] ?? 'after';
$last_id     = (int)($_POST['last_id'] ?? 0);

$LIMIT_INIT  = 50;
$LIMIT_AFTER = 50;

if ($outgoing_id <= 0 || $incoming_id <= 0) {
    http_response_code(400);
    exit;
}

if ($mode === 'init') {
    // 최근 50개만 먼저 가져오기
    $sql = "
        SELECT msg_id, outgoing_msg_id, incoming_msg_id, msg
        FROM messages
        WHERE (outgoing_msg_id=? AND incoming_msg_id=?)
           OR (outgoing_msg_id=? AND incoming_msg_id=?)
        ORDER BY msg_id DESC
        LIMIT ?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiiii", $outgoing_id, $incoming_id, $incoming_id, $outgoing_id, $LIMIT_INIT);
} else {

    // last_id 이후의 새 메시지들만
    $sql = "
        SELECT msg_id, outgoing_msg_id, incoming_msg_id, msg
        FROM messages
        WHERE (
            (outgoing_msg_id=? AND incoming_msg_id=?)
            OR (outgoing_msg_id=? AND incoming_msg_id=?)
        )
        AND msg_id > ?
        ORDER BY msg_id ASC
        LIMIT ?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiiiii", $outgoing_id, $incoming_id, $incoming_id, $outgoing_id, $last_id, $LIMIT_AFTER);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = [];
$max_id = $last_id;

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['msg_id'];
    if ($id > $max_id) $max_id = $id;

    $type = ((int)$row['outgoing_msg_id'] === $outgoing_id) ? 'outgoing' : 'incoming';

    // XSS 방어
    $safe_msg = htmlspecialchars($row['msg'], ENT_QUOTES, 'UTF-8');

    $items[] = [
        "msg_id" => $id,
        "type"   => $type,
        "msg"    => $safe_msg,
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(["last_id" => $max_id, "items" => $items], JSON_UNESCAPED_UNICODE);
?>