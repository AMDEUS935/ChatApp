const searchBar = document.querySelector(".users .search input"),
searchBtn = document.querySelector(".users .search button"),
usersList = document.querySelector(".users .user-list");

searchBtn.onclick = ()=>{
    searchBar.classList.toggle("active");
    searchBar.focus();
    searchBtn.classList.toggle("active");
    searchBar.value="";
}


searchBar.onkeyup = ()=>{
    let searchTerm = searchBar.value;
    // 동일한 파일에서 ajax는 두 번 실행됨 - 사용자 목록, 사용자 검색
    // 그러므로 사용자가 검색할때는 사용자 목록 ajax를 중지함
    if(searchTerm != ""){
        searchBar.classList.add("active");
    }else{
        searchBar.classList.remove("active");
    }
    //let's start Ajax
    let xhr = new XMLHttpRequest(); //createing XML object
    xhr.open("POST", "php/search.php", true);
    xhr.onload = ()=>{
        // onload : 프로그램이 기동할 때 뭔가를 작동하게 하고 싶다
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                usersList.innerHTML = data;
            }
        }
    }
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send("searchTerm=" + searchTerm);
}

setInterval(()=> {
    // let's start Ajax
    let xhr = new XMLHttpRequest(); //createing XML object
    xhr.open("GET", "php/users.php", true);
    xhr.onload = ()=>{
        // onload : 프로그램이 기동할 때 뭔가를 작동하게 하고 싶다
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(!searchBar.classList.contains("active")){ //
                    usersList.innerHTML = data;
                }
            }
        }
    }
    xhr.send();
}, 500); //0.5초 주기로 실행됨