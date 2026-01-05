<?php
session_start();
include_once "config.php";

if(!isset($_SESSION['unique_id'])){
    echo "로그인이 필요합니다.";
    exit;
}

$outgoing_id = (int)$_SESSION['unique_id'];
$searchTerm = trim($_POST['searchTerm'] ?? "");

$output = "";

if ($searchTerm !== "") {
    $like = "%{$searchTerm}%";
    $stmt = $conn->prepare("SELECT * FROM users WHERE unique_id != ? AND uname LIKE ?");
    $stmt->bind_param("is", $outgoing_id, $like);
} else {
    // 검색어 비었을 땐 전체 유저(본인 제외)
    $stmt = $conn->prepare("SELECT * FROM users WHERE unique_id != ?");
    $stmt->bind_param("i", $outgoing_id);
}

$stmt->execute();
$res = $stmt->get_result();

if($res && $res->num_rows > 0){
    $sql = $res;          // data.php가 $sql을 사용하므로 그대로 맞춰줌
    include "data.php";
} else {
    $output .= "검색 결과가 없습니다.";
}

echo $output;
$stmt->close();
?>