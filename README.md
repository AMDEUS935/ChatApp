## 개발환경
# 💬 도란도란 (Doran Doran)
**PHP & MySQL 기반의 비동기 실시간 웹 채팅 서비스**

단순한 채팅 기능을 넘어, 사용자 경험(UX)과 데이터의 무결성을 고려하여 설계한 웹 기반 채팅 플랫폼입니다. AJAX 통신을 통해 페이지 전환 없이 실시간 메시징이 가능하도록 구현했습니다.

## 🛠 주요 기술 스택
- **Backend**: PHP 7.4 (Session 기반 유저 인증)
- **Database**: MySQL
- **Frontend**: HTML5, CSS, JavaScript(AJAX)
- **Environment**: XAMPP, VS Code

## 📸 핵심 기능 및 화면 구성
| 회원가입/로그인 | 실시간 유저 리스트 | 1:1 채팅 인터페이스 |
| :---: | :---: | :---: |
| <img src="3.PNG" width="200"> | <img src="1.PNG" width="200"> | <img src="5.PNG" width="200"> |
| **`index.php`**: 프로필 이미지 업로드 및 유효성 검사 | **`users.php`**: 세션 기반 실시간 상태(Online/Offline) 노출 | **`chat.php`**: 상대방과의 실시간 메시지 송수신 |

## 💡 주요 개발 포인트 (Self-Review)

### 1. 데이터베이스 스키마 설계 (`chatdb.sql`)
- **`users` 테이블**: `unique_id`를 사용하여 외부 노출용 식별자를 별도로 관리함으로써 보안성을 높였습니다.
- **`messages` 테이블**: `incoming_msg_id`와 `outgoing_msg_id`를 활용해 대화 상대를 정확히 매칭하고 기록하도록 설계했습니다.

### 2. 효율적인 코드 재사용 및 보안
- **헤더 공통화**: 모든 페이지에서 반복되는 HTML `<head>` 섹션을 `header.php`로 분리하여 관리 효율성을 높였습니다.
- **SQL Injection 방지**: `mysqli_real_escape_string`을 사용하여 사용자 입력값에 대한 기본적인 보안 처리를 적용했습니다.
- **UX 최적화**: `style.css`에서 `word-wrap: break-word` 속성을 적용해 긴 메시지 전송 시 레이아웃이 깨지는 현상을 방지했습니다.

## ⚙️ 설치 및 실행 방법
1. 로컬 XAMPP 환경의 `htdocs`에 소스 복사
2. `phpMyAdmin`을 통해 `chatdb.sql` 임포트
3. `php/config.php`에서 DB 연결 정보 확인 후 실행
