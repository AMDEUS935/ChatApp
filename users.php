<?php
    session_start();
    if(isset($_SESSION['unique_id'])){
        //header("location: users.php");
    }
?>
<!-- 이 헤드 태그 코드는 모든 PHP 파일에서 동일하므로 하나의 파일에 붙여넣고 해당 파일을 모든 PHP 파일에 포함합니다. -->
<?php include_once "header.php";?> 
<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <?php
                    include_once "php/config.php";
                    $sql = mysqli_query($conn,"SELECT * FROM users WHERE unique_id={$_SESSION['unique_id']}");
                    if(mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                ?>
                <div class="content">
                    <img src="php/images/<?php echo $row['img'] ?>" alt="">
                    <div class="details">
                        <span><?php echo $row['uname'] ?></span>
                        <p><?php echo $row['status'] ?></p>
                    </div>
                </div>
                <a href="php/logout.php?logout_id=<?php echo $row['unique_id'] ?>" class="logout">로그아웃</a>
            </header>
            <div class="search">
                <span class="text">채팅할 친구를 선택하세요</span>
                <input type="text" placeholder="검색">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="user-list">
            </div>
        </section>
    </div>
    <script src="javascript\users.js"></script>
</body>
</html>