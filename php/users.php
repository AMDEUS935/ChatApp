<?php
session_start();
include_once "config.php";

if(!isset($_SESSION['unique_id'])){
    echo "로그인이 필요합니다.";
    exit;
}

$outgoing_id = $_SESSION['unique_id'];

// 검색어 없으므로 전체 회원 가져오기 (자기 자신 제외)
$query = "SELECT * FROM users WHERE unique_id != '{$outgoing_id}'";
$sql = mysqli_query($conn, $query);

$output = "";

if(mysqli_num_rows($sql) > 0){
    include "data.php";
} else {
    $output .= "채팅할 수 있는 사용자가 없습니다.";
}

echo $output;
?>