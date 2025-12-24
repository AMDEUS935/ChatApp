# 💬 도란도란 (Doran Doran)

PHP와 MySQL을 기반으로 구현한 웹 채팅 서비스입니다.  
AJAX 비동기 통신을 사용해 페이지 새로고침 없이 실시간으로 메시지를 주고받을 수 있도록 구성했습니다.

회원가입, 로그인, 유저 목록 표시, 1:1 채팅까지  
웹 채팅 서비스의 기본적인 흐름을 직접 구현하는 것을 목표로 한 프로젝트입니다.

---

## 🛠 사용 기술

- **Backend**: PHP  
- **Database**: MySQL  
- **Frontend**: HTML, CSS, JavaScript (AJAX)  
- **Environment**: XAMPP, VS Code  

---

## 📸 주요 기능 및 화면

| 🔐 회원가입 | 🔐 로그인 |
| :---: | :---: |
| 
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/signup.PNG" width="400">
|
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/LoginPage.PNG" width="400">
|

<br>

| 👥 실시간 유저 리스트 | 💬 1:1 채팅 |
| :---: | :---: |
| 
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/main.PNG" width="400">
|
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/chatting.PNG" width="400">
|

---
## 📂 프로젝트 구조

```text
ChatApp
├─ php/
│  ├─ config.php        # DB 설정
│  ├─ signup.php        # 회원가입 처리
│  ├─ login.php         # 로그인 처리
│  ├─ users.php         # 유저 목록 조회
│  ├─ insert-chat.php   # 메시지 저장
│  └─ get-chat.php      # 채팅 내역 조회
├─ javascript/
│  ├─ signup.js         # 계정 생성 요청
│  ├─ login.js          # 인증 요청 처리
│  ├─ users.js          # 유저 상태 동기화
│  └─ chat.js           # 채팅 데이터 동기화
├─ chatdb.sql           # DB 스키마
└─ README.md
```

## 🗄 데이터베이스 설계

채팅 서비스 특성상 **유저 식별**과 **메시지 매칭**을 중심으로 설계했습니다.

- **users**
  - 내부 ID와 분리된 `unique_id`를 사용해 외부 노출 최소화
  - 로그인 상태 및 기본 사용자 정보 관리

- **messages**
  - `incoming_msg_id`, `outgoing_msg_id`를 기준으로 대화 상대 구분
  - 1:1 채팅 내역을 안정적으로 저장 및 조회

| 📊 DB 전체 구조 | 👤 users 테이블 | 💬 messages 테이블 |
| :---:           | :---:           | :---: |
| 
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/db.PNG" width="300">
|
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/users.PNG" width="300">
|
<img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/messages.PNG" width="300">
|

## 🔍 구현 시 고려한 부분

- **비동기 처리**
  - AJAX를 활용해 메시지 송수신 시 페이지 새로고침 없이 처리
  - 주기적인 요청 방식으로 채팅 내용 및 유저 상태 갱신

- **기본 보안 처리**
  - `mysqli_real_escape_string`을 사용해 사용자 입력값 검증
  - 내부 PK 대신 `unique_id` 기반 통신 구조 사용

- **UI 안정성**
  - 긴 메시지 입력 시 레이아웃이 깨지지 않도록 CSS 처리
  - 채팅 화면 중심의 단순한 UI 구성

---

## ⚙️ 실행 방법

1. **XAMPP** 설치 후 Apache, MySQL 실행
2. 프로젝트 폴더를 `xampp/htdocs` 디렉토리에 복사
3. `http://localhost/phpmyadmin` 접속 후 `chatdb.sql` 임포트
4. 페이지 접속 후 사용

🧠 정리 및 개선 방향

웹 채팅 서비스의 전체 흐름과 데이터 구조를 직접 구현하며 이해할 수 있었습니다.

AJAX 방식의 한계를 체감했고, 추후에는 WebSocket 기반 실시간 통신으로 개선해보고 싶습니다.

그룹 채팅, 읽음 표시 처리 등은 추가 개선 포인트로 남겨두었습니다.

본 프로젝트는 개인 학습 및 포트폴리오 목적으로 제작되었습니다.


