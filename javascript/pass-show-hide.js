// work on password show or hid toggle 패스워드 입력 시 보이거나 숨기거나 
const pswrdField = document.querySelector(".form input[type='password']"),
toggleIcon = document.querySelector(".form .field i");

toggleIcon.onclick = () =>{
  if(pswrdField.type === "password"){
    pswrdField.type = "text"; //password 형식을 text로 바꾸어 보이게 함
    toggleIcon.classList.add("active"); //클래스를 필요에 따라 삽입
  }else{
    pswrdField.type = "password";
    toggleIcon.classList.remove("active");
  }
}
