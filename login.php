<?php
    session_start();
    if(isset($_SESSION['unique_id'])){
        header("location: user.php");
    }
?>

<?php include_once "header.php";?>
<body>
    <div class="wrapper">
        <section class="form login">
            <header>로그인</header>
            <form action="#">
                <div class="error-txt"></div>
                <div class="field input">
                    <label>이메일</label>
                    <input type="text" name = "email" placeholder="이메일을 입력하세요">
                </div>
                <div class="field input">
                    <label>비밀번호</label>
                    <input type="password" name="password" placeholder="비밀번호를 입력하세요">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="field button">
                    <input type="submit" value="로그인">
                </div>
            </form>
            <div class="link">아직 회원이 아니신가요? <a href="index.php">회원가입</a></div>
        </section>
    </div>

    <script src="javascript/pass-show-hide.js"></script>
    <script src="javascript/login.js"></script>
    
</body>
</html>