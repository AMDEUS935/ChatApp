<?php
    // 데이터가 있는 php 폴더 안에 다른 php파일을 만듦
    // 검색어와 사용자 목록에서 users.php와 일부 동일한 코드필요
    while($row = mysqli_fetch_assoc($sql)){
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id}
                OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
        $query2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($query2);
        if(mysqli_num_rows($query2) > 0){
            $result = $row2['msg'];
        }else{
            $result = ""; //대화내역 없음
        }

        // 글자 수 30개까지만 나타내고 ...
        (strlen($result) > 28) ? $msg=substr($result, 0, 30).'...' : $msg = $result;
        //($outgoing_id == $row2['outgoing_msg_id']) ? $you = "" : $you = "";
        //online인지 offline인지 확인
        ($row['status'] == "오프라인") ? $offline = "offline" : $offline = "";

        $output .=  '<a href="chat.php?user_id='.$row['unique_id'].'">
                    <div class="content">
                    <img src="php/images/'. $row['img'] .'" alt="">
                    <div class="details">
                        <span>'. $row['uname'] .'</span>
                        <p>'. $msg .'</p>
                    </div>
                    </div>
                    <div class="status-dot '.$offline.'"><i class="fas fa-circle"></i></div>
                    </a>';
    }
    // 이때 열린 chat.php?user_id=54887, 이 아이디를 사용하여 메시지 수신자 사용자를 식별함
?>