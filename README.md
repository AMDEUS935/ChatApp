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

### 🔐 인증 기능

| 회원가입 | 로그인 |
| :---: | :---: |
| <img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/signup.PNG" width="350"> | <img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/LoginPage.PNG" width="350"> |

- 사용자 입력값 검증 후 계정 생성
- 로그인 성공 시 세션 기반 인증 처리

---

### 💬 채팅 기능

| 실시간 유저 리스트 | 1:1 채팅 |
| :---: | :---: |
| <img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/main.PNG" width="350"> | <img src="https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/chatting.PNG" width="350"> |

- 현재 접속 중인 유저 목록 실시간 갱신
- 특정 유저 선택 시 1:1 채팅 화면 진입
- 메시지 송수신 시 페이지 새로고침 없음

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

채팅 서비스 특성상 **유저 식별**과 **메시지 매칭 구조**에 집중해 설계했습니다.

| 전체 구조 | users 테이블 | messages 테이블 |
| :---: | :---: | :---: |
| ![](https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/db.PNG) | ![](https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/users.PNG) | ![](https://raw.githubusercontent.com/AMDEUS935/ChatApp/chatapp-images/messages.PNG) |

### 설계 요약
- 내부 PK와 분리된 `unique_id` 기반 사용자 식별 구조
- 외부 노출 최소화를 고려한 사용자 ID 설계
- 로그인 상태 및 기본 사용자 정보 관리
- `incoming_msg_id`, `outgoing_msg_id`를 통한 1:1 채팅 메시지 구분 및 저장

## 🔍 구현 시 고려한 부분

- **비동기 처리**
  - AJAX를 활용해 메시지 송수신 시 페이지 새로고침 없이 처리
  - 주기적인 요청 방식으로 채팅 내용 및 유저 상태 갱신

- - **기본 보안/검증**
  - 비밀번호는 **해시 저장/검증**(`password_hash`, `password_verify`)
  - DB 쿼리는 **Prepared Statement**로 처리하여 SQL Injection 방어(로그인/검색/채팅 등 핵심 구간)
  - 전송자(outgoing_id)는 클라이언트 입력을 신뢰하지 않고 **세션 사용자로 서버에서 강제**
  - 메시지/업로드는 **서버에서 입력 검증**(빈 값/길이/파일 크기/실제 이미지 여부)


- **UI 안정성**
  - 긴 메시지 입력 시 레이아웃이 깨지지 않도록 CSS 처리
  - 채팅 화면 중심의 단순한 UI 구성
---

## ✅ 업데이트 내역 (v2)

초기 버전(v1) 구현 이후, 실제 서비스 관점에서 **부하/안정성/보안**을 개선했습니다.

- **채팅 조회 최적화**
  - `last_id` 기반 **증분 조회** 적용(초기 로딩 이후엔 마지막 메시지 이후만 요청)
  - 화면 렌더링을 **append 방식**으로 변경하여 불필요한 전체 재렌더 제거

- **Polling 부하 감소**
  - 새 메시지가 없을 때 요청 간격을 점진적으로 늘리는 **Backoff 적용**
  - **유저 리스트 polling 주기 증가**로 불필요 요청 감소
  - **채팅방 활성 상태일 때만 polling** (채팅창이 열려 있을 때만 조회)
  - 채팅창을 열지 않으면 **get-chat 요청 자체를 하지 않도록** 처리

- **입력/업로드 검증 강화**
  - 메시지 **공백 전송 차단 + 최대 500자 제한** (서버에서 최종 검증, 클라이언트는 UX 제한)
  - 프로필 이미지 업로드 **파일 크기 제한**
  - 확장자가 아닌 **실제 이미지 파일 여부 검증**
  - 프로필 이미지는 **유저당 1개만 유지** (업로드 시 기존 파일 교체/정리)

---

## ⚙️ 실행 방법

1. **XAMPP** 설치 후 Apache, MySQL 실행
2. 프로젝트 폴더를 `xampp/htdocs` 디렉토리에 복사
3. `http://localhost/phpmyadmin` 접속 후 `chatdb.sql` 임포트
4. 브라우저 접속 후 이용

--- 
## 🧠 정리 및 개선 방향

웹 채팅 서비스의 인증부터 메시지 저장/조회까지 전체 흐름을 직접 구현하면서,  
서버가 **클라이언트 입력을 신뢰하지 않고 검증해야 한다는 점**과 DB 설계/조회 패턴의 중요성을 체감했습니다.

또한 Polling 기반 구조에서 발생할 수 있는 부하를 줄이기 위해 **증분 조회(last_id)와 Backoff** 같은 개선을 적용해보며,
기능 구현뿐만 아니라 운영 관점(부하/안정성)도 함께 고민했습니다.

추후에는 WebSocket 기반 방식으로 전환하고, 그룹 채팅(채팅방/멤버십/권한)을 확장해볼 계획입니다.

---
본 프로젝트는 개인 학습 및 포트폴리오 목적으로 제작되었습니다.






