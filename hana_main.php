<?php
include("security.php");
include("security_level_check.php");
include("selections.php");
include("functions_external.php");
include("connect.php");

$message = "";

function sqli($data)
{

    switch($_COOKIE["security_level"])
    {

        case "0" :

            $data = no_check($data);
            break;

        case "1" :

            $data = xss_check_1(sqli_check_1($data));
            break;

        case "2" :

            $data = xss_check_3(sqli_check_2($data));
            break;

        default :

            $data = no_check($data);
            break;

    }

    return $data;

}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>하나카드 스타일 금융 사이트</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet" />
  <!-- Font Awesome CDN 추가 (아이콘 사용) -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-papbE72NZVxQaMRS7YLBsFA0bCzCy6FnIHvZ3KvhjZRo64m8IG4RRm2tY6yAkhwT1Lt/2RxjQHhnAzOxh4FJig=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  <style>
    :root {
      --dark-green: #004d40;
      --green-light: #00796b;
      --white: #fff;
      --text-dark: #1b1b1b;
      --shadow-color: rgba(0, 0, 0, 0.15);
      --hover-green: #00695c;
    }

    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }

    body {
      font-family: 'Pretendard', sans-serif;
      background: #f5f7f7;
      color: var(--text-dark);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    header {
      background: var(--dark-green);
      padding: 40px 30px; /* 기존 15px 30px에서 높이 2배 */
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: var(--white);
      font-weight: 700;
      font-size: 1.2rem;
      user-select: none;
      position: relative;
    }

    /* 가운데 하나은행 로고 */
    header::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 120px;
      height: auto;
      background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Hana_Bank_logo.svg/120px-Hana_Bank_logo.svg.png') no-repeat center center;
      background-size: contain;
      pointer-events: none;
      opacity: 0.9;
    }

    .login-wrapper {
      position: absolute;
      top: 20px;
      right: 30px;
      z-index: 10;
    }

    .search-box {
      position: absolute;
      bottom: 10%;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
    }

    /* 상단 개인/기업/가맹점 탭 */
    .top-tabs {
      display: flex;
      gap: 20px;
      z-index: 1; /* 로고 위로 */
      position: relative;
    }

    .top-tabs button {
      background: transparent;
      border: none;
      color: var(--white);
      font-weight: 700;
      font-size: 1rem;
      padding: 6px 15px;
      cursor: pointer;
      border-radius: 25px;
      transition: background-color 0.3s ease;
    }
    .top-tabs button.active,
    .top-tabs button:hover {
      background-color: var(--green-light);
      box-shadow: 0 2px 10px var(--shadow-color);
    }

    .header-right {
      position: absolute;
      top: 90px;              /* 상단 여백 */
      left: 50%;
      transform: translateX(-50%); /* 수평 가운데 정렬 */
      display: flex;
      gap: 18px;
      font-size: 0.9rem;
      z-index: 1;
    }

    .header-right a {
      color: var(--white);
      text-decoration: none;
      border-bottom: 1px solid transparent;
      transition: border-bottom 0.3s ease;
    }
    .header-right a:hover {
      border-bottom: 1px solid var(--white);
    }

    main {
      max-width: 1100px;
      margin: 40px auto;
      background: var(--white);
      border-radius: 16px;
      padding: 30px 40px;
      box-shadow: 0 8px 24px var(--shadow-color);
      display: flex;
      flex-direction: column;
      gap: 30px;
      user-select: none;
      position: relative;
      overflow: hidden;
    }

    /* 메인페이지 배경에 연한 하나은행 로고 워터마크 */
    main::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 300px;
      height: 300px;
      background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Hana_Bank_logo.svg/300px-Hana_Bank_logo.svg.png') no-repeat center center;
      background-size: contain;
      opacity: 0.05;
      transform: translate(-50%, -50%);
      pointer-events: none;
      user-select: none;
      z-index: 0;
    }

    /* 탭 메뉴 */
    .content-tabs {
      display: flex;
      gap: 25px;
      border-bottom: 2px solid var(--green-light);
      user-select: none;
      position: relative;
      z-index: 1;
    }
    .content-tabs button {
      flex: 1;
      background: transparent;
      border: none;
      font-weight: 700;
      font-size: 1.1rem;
      padding: 10px 0;
      cursor: pointer;
      color: var(--dark-green);
      border-bottom: 4px solid transparent;
      transition: border-color 0.3s ease, color 0.3s ease;
    }
    .content-tabs button.active {
      color: var(--green-light);
      border-bottom-color: var(--green-light);
      font-weight: 900;
    }
    .content-tabs button:hover:not(.active) {
      color: var(--hover-green);
    }

    .tab-content {
      min-height: 300px;
      color: var(--text-dark);
      font-size: 1.1rem;
      line-height: 1.5;
      user-select: text;
      position: relative;
      z-index: 1;
    }

    /* 카드 리스트 */
    .card-list {
      display: flex;
      gap: 24px;
      margin-top: 25px;
      z-index: 1;
      position: relative;
    }

    .card {
      flex: 1;
      background: #e0f2f1;
      border-radius: 12px;
      padding: 25px 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: box-shadow 0.3s ease, transform 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      text-align: center;
      color: var(--dark-green);
    }
    .card:hover {
      box-shadow: 0 8px 20px rgba(0,0,0,0.18);
      transform: translateY(-6px);
      background: var(--green-light);
      color: var(--white);
    }

    /* 아이콘 크기 및 색상 */
    .card i {
      font-size: 3rem;
      user-select: none;
      transition: color 0.3s ease;
    }
    .card:hover i {
      color: var(--white);
    }

    .card p {
      font-weight: 700;
      font-size: 1rem;
      margin-top: 5px;
    }

    footer {
      text-align: center;
      padding: 22px 15px;
      background: var(--dark-green);
      color: var(--white);
      font-weight: 600;
      font-size: 1rem;
      margin-top: auto;
      user-select: none;
    }

    /* 반응형 */
    @media (max-width: 768px) {
      main {
        margin: 20px 15px;
        padding: 25px 20px;
      }
      .content-tabs {
        flex-direction: column;
        gap: 12px;
      }
      .content-tabs button {
        flex: none;
        font-size: 1rem;
        border-bottom: none;
        padding: 8px 0;
      }
      .content-tabs button.active {
        border-bottom-color: transparent;
        background-color: var(--green-light);
        border-radius: 8px;
        color: var(--white);
      }
      .card-list {
        flex-direction: column;
      }
      .card {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="top-tabs" role="tablist" aria-label="사용자 유형 선택">
      <button role="tab" aria-selected="true" tabindex="0" class="active" id="tab-personal">개인</button>
      <button role="tab" aria-selected="false" tabindex="-1" id="tab-company">기업</button>
      <button role="tab" aria-selected="false" tabindex="-1" id="tab-store">가맹점</button>
    </div>
    <nav class="header-right" aria-label="상단 사용자 메뉴">
      <a href="#" tabindex="0">공지사항</a>
      <a href="#" tabindex="0">상품공시실</a>
      <a href="#" tabindex="0">금융소비자보호</a>
    </nav>

  <div class="login-wrapper">
    <div class="login-form">
    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">

        <label for="login"><font color="white">Login:</font></label>
        <input type="text" id="login" name="login" size="20" autocomplete="off" />

        <label for="password"><font color="white">Password:</font></label>
        <input type="password" id="password" name="password" size="20" autocomplete="off" />

        <button type="submit" name="form" value="submit">Login</button>
    </form>

<br />
<?php

    if(isset($_POST["form"]))
    {

        $login = $_POST["login"];
        $login = sqli($login);

        $password = $_POST["password"];
        $password = sqli($password);

        $sql = "SELECT * FROM heroes WHERE login = '" . $login . "' AND password = '" . $password . "'";

        // echo $sql;

        $recordset = mysql_query($sql, $link);

        if(!$recordset)
        {

            die("Error: " . mysql_error());

        }

        else
        {

            $row = mysql_fetch_array($recordset);

            if($row["login"])
            {

                // $message = "<font color=\"white\">로그인이 성공되었습니다. " . ucwords($row["login"]) . "님</font>" ;
                $message =  "<p><font color=\"white\">로그인이 성공되었습니다. <b>" . ucwords($row["login"]) . "님</b></font></p><p><font color=\"white\">오늘의 메세지: <b>" . ucwords($row["secret"]) . "</b></font></p>";
                // $message = $row["login"];

            }

            else
            {

                $message = "<font color=\"white\">로그인이 실패되었습니다.</font>";

            }

        }

        mysql_close($link);

    }

    echo $message;

?>


    </div>
  </div>
  </header>

  <main>
    <div class="content-tabs" role="tablist" aria-label="주요 서비스 탭">
      <button role="tab" aria-selected="true" tabindex="0" class="active" id="tab1">카드</button>
      <button role="tab" aria-selected="false" tabindex="-1" id="tab2">금융</button>
      <button role="tab" aria-selected="false" tabindex="-1" id="tab3">모바일</button>
      <button role="tab" aria-selected="false" tabindex="-1" id="tab4">라이프</button>
      <button role="tab" aria-selected="false" tabindex="-1" id="tab5">혜택</button>
    </div>

    <section id="tab1-content" class="tab-content" role="tabpanel" aria-labelledby="tab1">
      <p>다양한 신용카드 및 체크카드를 제공합니다. 국내외 가맹점에서 사용할 수 있는 혜택과 서비스를 경험하세요.</p>
      <div class="card-list">
        <div class="card" tabindex="0" aria-label="국내 가맹점 5% 적립 카드">
          <i class="fa-solid fa-credit-card"></i>
          <p>국내 가맹점 5% 적립</p>
        </div>
        <div class="card" tabindex="0" aria-label="스카이패스 마일리지 적립 카드">
          <i class="fa-solid fa-plane"></i>
          <p>스카이패스 마일리지 적립</p>
        </div>
        <div class="card" tabindex="0" aria-label="해외 가맹점 이용 수수료 할인 카드">
          <i class="fa-solid fa-globe"></i>
          <p>해외 가맹점 이용 수수료 할인</p>
        </div>
      </div>
    </section>

    <section id="tab2-content" class="tab-content" role="tabpanel" aria-labelledby="tab2" hidden>
      <p>자동차 금융, 개인 대출 등 다양한 금융 서비스를 쉽고 빠르게 이용할 수 있습니다.</p>
    </section>

    <section id="tab3-content" class="tab-content" role="tabpanel" aria-labelledby="tab3" hidden>
      <p>모바일 앱을 통해 언제 어디서나 카드 및 금융 서비스를 편리하게 관리하세요.</p>
    </section>

    <section id="tab4-content" class="tab-content" role="tabpanel" aria-labelledby="tab4" hidden>
      <p>여행, 쇼핑, 문화 생활 등 다양한 라이프스타일 혜택을 제공합니다.</p>
    </section>

    <section id="tab5-content" class="tab-content" role="tabpanel" aria-labelledby="tab5" hidden>
      <p>특별 할인, 적립, 이벤트 등 다양한 카드 혜택 정보를 확인하세요.</p>
    </section>

  <div class="search-box">
    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="GET">
      <input type="text" id="query" name="query" placeholder="검색어를 입력하세요..." />
    </form>

<br />
<?php
    if(isset($_GET["query"]))
    {   

        $query = $_GET["query"];

        if($query == "")
        {
            echo "<font color=\"#004d40\">검색할 단어를 입력하세요.</font>";       
        }

        else            
        {  
            echo "<font color=\"#004d40\">" . sqli($query) . "에 대한 검색결과가 없습니다.</font>";
        }
    }

?>

  </div>
  </main>

  <footer>
    &copy; 2025 하나카드. All rights reserved.
  </footer>

  <script>
    // 상단 개인/기업/가맹점 탭 이벤트
    const userTabs = document.querySelectorAll('.top-tabs button');
    userTabs.forEach(tab => {
      tab.addEventListener('click', e => {
        userTabs.forEach(t => {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
          t.setAttribute('tabindex', '-1');
        });
        e.currentTarget.classList.add('active');
        e.currentTarget.setAttribute('aria-selected', 'true');
        e.currentTarget.setAttribute('tabindex', '0');
      });
    });

    // 주요 서비스 탭 이벤트
    const serviceTabs = document.querySelectorAll('.content-tabs button');
    const tabContents = document.querySelectorAll('.tab-content');

    serviceTabs.forEach(tab => {
      tab.addEventListener('click', e => {
        const targetId = e.currentTarget.id;
        // 탭 버튼 상태 변경
        serviceTabs.forEach(t => {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
          t.setAttribute('tabindex', '-1');
        });
        e.currentTarget.classList.add('active');
        e.currentTarget.setAttribute('aria-selected', 'true');
        e.currentTarget.setAttribute('tabindex', '0');

        // 콘텐츠 영역 표시 변경
        tabContents.forEach(content => {
          if(content.getAttribute('aria-labelledby') === targetId){
            content.hidden = false;
          } else {
            content.hidden = true;
          }
        });
      });
    });
  </script>

<div id="security_level">

    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">

        <label>보안 로그인</label><br />

        <select name="security_level">

            <option value="0">일반 로그인</option>
            <option value="2">보안 로그인</option> 

        </select>

        <button type="submit" name="form_security_level" value="submit">선택</button>
        <font size="4">현재 상태: <b><?php echo $security_level?></b></font>

    </form>   
    
</div>
</body>
</html>