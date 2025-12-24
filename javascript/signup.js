// javascript ajax를 사용하여 가입 양식 세부 정보를 php로 보내는 작업
const form = document.querySelector(".signup form"),
joinBtn = form.querySelector(".button input");
errorText = form.querySelector(".error-txt");

form.onsubmit = (e)=> {
    e.preventDefault(); //양식이 제출되지 않도록 함?
}

joinBtn.onclick = ()=> { // 회원가입 버튼 누르면 양식 제출
    // let's start Ajax
    let xhr = new XMLHttpRequest(); //createing XML object
    xhr.open("POST", "php/signup.php", true);
    xhr.onload = ()=>{
        // onload : 프로그램이 기동할 때 뭔가를 작동하게 하고 싶다
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(data == "success"){
                    location.href="users.php";
                }else { // 왜 esle가 실행되지??아닌가
                    errorText.textContent = data;
                    errorText.style.display = "block";
                }
            }
        }
    }
    // we have to send the form data through ajax to php (양식 데이터를 ajax를 통해 php로 보내야 함??)
    let formData = new FormData(form); // creating new formData object
    xhr.send(formData); // sending the form data to php
}   