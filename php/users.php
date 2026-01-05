<?php
session_start();
include_once "config.php";

if(!isset($_SESSION['unique_id'])){
    echo "로그인이 필요합니다.";
    exit;
}

$outgoing_id = (int)$_SESSION['unique_id'];

$sql_text = "SELECT * FROM users WHERE unique_id != ?";
$stmt = mysqli_prepare($conn, $sql_text);
mysqli_stmt_bind_param($stmt, "i", $outgoing_id);
mysqli_stmt_execute($stmt);
$sql = mysqli_stmt_get_result($stmt);

$output = "";

if($sql && mysqli_num_rows($sql) > 0){
    include "data.php";
} else {
    $output .= "채팅할 수 있는 사용자가 없습니다.";
}

echo $output;
?>
