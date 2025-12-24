<?php
    session_start(); //$_SESSION['unique_id'] = $row['unique_id']; 이부분?
    include_once "config.php"; // php 파일내용 포함시키기
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($uname) && !empty($email) &&!empty($password)) {
        //email이 유효한지 확인
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){ 
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");//email가 이미 데이터베이스에 존재하는지 확인
            if(mysqli_num_rows($sql) > 0) { // 이미 있으면
                echo "$email - 가입된 이메일입니다.";
            }else {
                //사용자 업로드 파일을 확인
                if(isset($_FILES['image'])){ //파일 업로드된 경우
                    $img_name= $_FILES['image']['name'];//업로드한 이미지 파일 이름 가져오기
                    $tmp_name = $_FILES['image']['tmp_name']; //이 임시 이름은 폴더에 파일을 저장/이동하는 데 사용됨?

                    //이미지 파일 확장자 분해
                    $img_explode = explode('.', $img_name); //.으로 분해
                    $img_ext = end($img_explode); //사용자가 업로드한 img 파일의 확장자를 얻음

                    $extensions = ['png', 'jpeg','jpg']; // 유효한 이미지 확장자들
                    if(in_array($img_ext, $extensions) === true){ // 사용자가 업로드한 이미지 확장
                        $time = time(); // 현재 시간 반환
                                        //img를 폴더에 업로드할 때 현재 시간으로 사용자 파일의 이름을 변경
                                        // 모든 이미지 파일은 고유한 이름을 가짐
                        //이미지 iamges폴더에 저장
                        $new_img_name = $time.$img_name;

                        if(move_uploaded_file($tmp_name, "images/". $new_img_name)) { //업로드한 이미지가 폴더로 성공적으로 이동하면
                            $status = "온라인"; // 사용자가 가입하면 현재 상태 active now상태 됨)
                            $random_id = rand(time(), 100000000); // user에게 랜덤으로 id 생성해줌??? 왜??

                            // 모든 유저 데이터를 테이블에 삽입
                            $sql2 = mysqli_query($conn ,"INSERT INTO users ( unique_id, uname, email, password, img, status)
                                                VALUES ({$random_id}, '{$uname}','{$email}', '{$password}', '{$new_img_name}', '{$status}')");
                            if($sql2) { // 데이터 삽입되면
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                if(mysqli_num_rows($sql3) > 0){
                                    $row = mysqli_fetch_assoc($sql3);  
                                    $_SESSION['unique_id'] = $row['unique_id']; // 이 섹션을 사용하여 다른 php file에서 사용자 unique_id를 사용함 
                                    echo "success";     
                                }
                            }else{
                                echo "뭔가 잘못됨!!!";
                            }
                        }
                        
                    }else {
                        echo "이미지 파일을 선택해주세요 - jpeg, jpg, png!";
                    }

                }else {
                    echo "이미지 파일을 선택해주세요!";
                }
            }
        }else{
            echo "$email - 이메일 형식이 잘못되었습니다.";
        }
    }else {
        // echo "All input field are required!"; // 모든 입력 필드를 채워라
        echo "필수 정보입니다.";
    }
?>