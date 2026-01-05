<?php
if (!function_exists('e')) {
    function e($s) {
        return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$outgoing_id = (int)($outgoing_id ?? 0);

// 마지막 메시지 조회 (Prepared)
$other_id = 0;
$last_msg = null;

$sql2 = "SELECT msg FROM messages
         WHERE (incoming_msg_id = ? OR outgoing_msg_id = ?)
           AND (outgoing_msg_id = ? OR incoming_msg_id = ?)
         ORDER BY msg_id DESC
         LIMIT 1";

$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "iiii", $other_id, $other_id, $outgoing_id, $outgoing_id);
mysqli_stmt_bind_result($stmt2, $last_msg);

while($row = mysqli_fetch_assoc($sql)){
    $other_id = (int)$row['unique_id'];

    mysqli_stmt_execute($stmt2);
    $result = "";
    if (mysqli_stmt_fetch($stmt2)) {
        $result = $last_msg ?? "";
    }
    mysqli_stmt_free_result($stmt2);

    (strlen($result) > 28) ? $msg = substr($result, 0, 30).'...' : $msg = $result;

    ($row['status'] == "오프라인") ? $offline = "offline" : $offline = "";

    $img = basename($row['img'] ?? '');

    $output .=  '<a href="chat.php?user_id='.$other_id.'">
                <div class="content">
                <img src="php/images/'. e($img) .'" alt="">
                <div class="details">
                    <span>'. e($row['uname']) .'</span>
                    <p>'. e($msg) .'</p>
                </div>
                </div>
                <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
}

mysqli_stmt_close($stmt2);
?>
