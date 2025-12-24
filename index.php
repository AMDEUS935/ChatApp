<?php
    session_start();
    if(isset($_SESSION['unique_id'])){
        header("location: users.php");
    }
?>

<?php include_once "header.php";?>
<body>
    <div class="wrapper">
        <section class="form signup">
            <header>도란도란</header>
            <form action="#" enctype="multipart/form-data" autocomplete="off">
                <div class="error-txt"></div>
                <div class="field input">
                    <label>이름</label>
                    <input type="text" name ="uname" placeholder="이름을 입력하세요" required>
                </div>
                <div class="field input">
                    <label>이메일</label>
                    <input type="text" name ="email" placeholder="Email@address" required>
                </div>
                <div class="field input">
                    <label>비밀번호</label>
                    <input type="password" name ="password" placeholder="비밀번호를 입력하세요" required>
                    <i class="fas fa-eye"></i>
                </div>
                <div class="field image">
                    <label>이미지 선택</label>
                    <input type="file" name="image" required>
                </div>
                <div class="field button">
                    <input type="submit" value="회원가입">
                </div>
            </form>
            <div class="link">이미 계정이 있으신가요? <a href="login.php">로그인</a></div>
        </section>
    </div>

    <script src="javascript/pass-show-hide.js"></script>
    <script src="javascript/signup.js"></script>
    
</body>
</html>