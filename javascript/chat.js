const form = document.querySelector(".typing-area"),
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button"),
  chatBox = document.querySelector(".chat-box");

let lastMsgId = 0;   // 마지막 메시지 ID
let delay = 500;     // 폴링 간격
let started = false; // 초기 로딩 여부

form.onsubmit = (e) => e.preventDefault();

const MAX_LEN = 500;
inputField.addEventListener("input", () => 
{
  if (inputField.value.length > MAX_LEN) inputField.value = inputField.value.slice(0, MAX_LEN);
});

// 자동 스크롤 방지
chatBox.onmouseenter = () => chatBox.classList.add("active");
chatBox.onmouseleave = () => chatBox.classList.remove("active");

// 채팅 아래로 스크롤
function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

// AJAX 요청
function request(url, formData, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.onload = () => xhr.status === 200 && callback(xhr.responseText);
  xhr.send(formData);
}

// 서버에서 받은 메시지를 화면에 추가
function appendMessages(items) {
  if (!items || items.length === 0) return 0;

  let html = "";
  for (const it of items) {
    html += `<div class="chat ${it.type}">
               <div class="details"><p>${it.msg}</p></div>
             </div>`;
  }
  chatBox.insertAdjacentHTML("beforeend", html);

  if (!chatBox.classList.contains("active")) scrollToBottom();
  return items.length;
}

// 채팅 로딩
// init  : 최초 진입 시 최근 메시지 로딩
// after : 마지막 메시지 이후 새 메시지만 로딩
function load(mode) {
  const fd = new FormData(form);
  fd.append("mode", mode);
  if (mode === "after") 
    {
        // 마지막으로 받은 메시지 이후만 요청
        fd.append("last_id", lastMsgId);
    }

  request("php/get-chat.php", fd, (text) => {
    const data = JSON.parse(text);

    if (typeof data.last_id === "number") // 최신 메시지 ID 갱신
        lastMsgId = data.last_id;

    if (mode === "init") 
    {
      // 최초 로딩 시: 화면 초기화 후 최근 메시지 표시
      chatBox.innerHTML = "";
      appendMessages((data.items || []).slice().reverse());
      started = true;
    } 
    else 
        {
            // 이후 로딩 시: 새 메시지만 추가
            const count = appendMessages(data.items || []);

            // 새 메시지가 없으면 폴링 간격 점진적으로 증가
            delay = count ? 500 : Math.min(delay * 2, 8000);
            setTimeout(() => load("after"), delay);
        }
  });
}

// 메시지 전송
sendBtn.onclick = () => {
  request("php/insert-chat.php", new FormData(form), () => {
    inputField.value = "";
    delay = 500;
    if (started) load("after");
  });
};

// 시작
load("init");  // 최초 채팅 로딩
setTimeout(() => load("after"), delay); // 이후부터 주기적 조회