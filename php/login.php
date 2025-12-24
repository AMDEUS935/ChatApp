<?php
    session_start(); //$_SESSION['unique_id'] = $row['unique_id']; 이부분?
    include_once "config.php"; // php 파일내용 포함시키기
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    
    if(!empty($email) && !empty($password)) {
        // 사용자가 입력한 정보가 데이터베이스 및 테이블 행 이메일 및 비밀번호와 일치하는지 확인
        $sql = mysqli_query($conn, "SELECT * FROM  users WHERE email = '{$email}' AND password = '{$password}'");
        if(mysqli_num_rows($sql) > 0){ // 사용자가 맞으면
            $row = mysqli_fetch_assoc($sql);
            $status = "온라인";
            $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
            if($sql2){
                $_SESSION['unique_id'] = $row['unique_id']; // 이 섹션을 사용하여 다른 php file에서 사용자 unique_id를 사용함 
                echo "success";  
            }
        }else{
            echo "이메일이나 비밀번호가 맞지 않습니다..";
        }
    }else{
        echo "필수 정보입니다.";
    }
    
?>