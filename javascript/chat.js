const form = document.querySelector(".typing-area"),
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector('.chat-box');


form.onsubmit = (e)=> {
    e.preventDefault(); //양식이 제출되지 않도록 함?
}

sendBtn.onclick = ()=> { // 내가 보낸 메시지??
    // let's start Ajax
    let xhr = new XMLHttpRequest(); //createing XML object
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = ()=>{
        // onload : 프로그램이 기동할 때 뭔가를 작동하게 하고 싶다
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                inputField.value = ""; //메시지가 데이터베이스에 입력되면 입력창 다시 비줘줌
                scrollToBottom();
            }
        }
    }
    // we have to send the form data through ajax to php (양식 데이터를 ajax를 통해 php로 보내야 함??)
    let formData = new FormData(form); // creating new formData object
    xhr.send(formData); // sending the form data to php
}   

chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}
chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}


setInterval(()=> {
    // let's start Ajax
    let xhr = new XMLHttpRequest(); //createing XML object
    xhr.open("POST", "php/get-chat.php", true);
    xhr.onload = ()=>{
        // onload : 프로그램이 기동할 때 뭔가를 작동하게 하고 싶다
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                chatBox.innerHTML = data;
                if(!chatBox.classList.contains("active")){ // active클래스가 contain 되어있지않으면 맨 아래로 스크롤
                    scrollToBottom();
                }
            }
        }
    }

    // we have to send the form data through ajax to php (양식 데이터를 ajax를 통해 php로 보내야 함??)
    let formData = new FormData(form); // creating new formData object
    xhr.send(formData); // sending the form data to php
}, 500); //0.5초 주기로 실행됨

function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
}