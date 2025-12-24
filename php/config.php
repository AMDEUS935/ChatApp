<?php 
    // <!-- MySQL 연결 -->
    $conn = mysqli_connect("localhost", "root", "", "chatapp2");
    if($conn) {
        echo "" . mysqli_connect_error();
    }
?>