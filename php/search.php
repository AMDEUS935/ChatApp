<?php
session_start();
include_once "config.php";

if(!isset($_SESSION['unique_id'])){
    echo "로그인이 필요합니다.";
    exit;
}

$outgoing_id = $_SESSION['unique_id'];

// 검색어 가져오기 (POST가 없으면 빈 문자열)
$searchTerm = isset($_POST['searchTerm']) ? mysqli_real_escape_string($conn, $_POST['searchTerm']) : "";

// 검색어가 있으면 LIKE, 없으면 전체
if($searchTerm != ""){
    $query = "SELECT * FROM users WHERE unique_id != {$outgoing_id} AND uname LIKE '%{$searchTerm}%'";
} else {
    $query = "SELECT * FROM users WHERE unique_id != {$outgoing_id}";
}

$sql = mysqli_query($conn, $query);
$output = "";

if(mysqli_num_rows($sql) > 0){
    include "data.php";
} else {
    $output .= "채팅할 수 있는 사용자가 없습니다.";
}

echo $output;
?>