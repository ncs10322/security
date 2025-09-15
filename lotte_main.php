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
  <meta charset="UTF-8">
  <title>롯데카드 스타일 홈페이지</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary-color: #e60012;
      --secondary-color: #f4f4f4;
      --accent-color: #3c3c3c;
      --text-color: #333;
      --hover-color: #cc0000;
      --font-family: 'Pretendard', sans-serif;
    }
    *, *::before, *::after {
      margin: 0; padding: 0; box-sizing: border-box;
    }
    body {
      font-family: var(--font-family);
      background-color: var(--secondary-color);
      color: var(--text-color);
      overflow-x: hidden;
    }

    /* 헤더 */
    header {
      position: fixed; top: 0; left: 0;
      width: 100%; height: 120px;
      background: linear-gradient(to right, #e60012, #f13c6e);
      display: flex; align-items: center;
      justify-content: space-between;
      padding: 0 30px; z-index: 1000;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .login-wrapper {
      position: absolute;
      top: 20px;
      right: 30px;
      z-index: 10;
    }

    .logo {
  	font-size: 36px;       /* 크기 키움 */
  	font-weight: 700;
  	color: white;
  	letter-spacing: 1px;   /* 자간 좁힘 */
    }

    .navbar {
      position: absolute;
      top: 80px;              /* 상단 여백 */
      left: 50%;
      transform: translateX(-50%); /* 수평 가운데 정렬 */
      display: flex;
      gap: 18px;
      font-size: 0.9rem;
      z-index: 1;
    }
    .navbar a {
      color: white; text-decoration: none;
      position: relative; transition: color .3s;
    }
    .navbar a::after {
      content: ""; position: absolute;
      bottom: -5px; left: 0;
      width: 100%; height: 3px;
      background: white;
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .25s ease-out;
    }
    .navbar a:hover {
      color: var(--hover-color);
    }
    .navbar a:hover::after {
      transform: scaleX(1);
    }

    .search-box {
      position: absolute;
      top: 25%;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
    }

    /* 뉴스 티커 */
    .ticker {
      position: fixed;
      top: 120px; left: 0;
      width: 100%; height: 40px;
      background: #fff;
      border-bottom: 1px solid #ddd;
      overflow: hidden;
      z-index: 999;
    }
    .ticker p {
      white-space: nowrap;
      display: inline-block;
      padding-left: 100%;
      animation: ticker 10s linear infinite;
      font-size: 14px;
      color: var(--accent-color);
      line-height: 40px;
    }
    @keyframes ticker {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }

    /* 배너 */
    .banner {
      position: relative;
      width: 100%;
      height: calc(100vh - 160px);
      background: url('https://via.placeholder.com/1600x900') center/cover no-repeat;
      margin-top: 160px;
      overflow: hidden;
    }
    /* 떠다니는 원 데코 */
    .banner::before,
    .banner::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,0.3);
      animation: float 8s ease-in-out infinite;
    }
    .banner::before { width: 120px; height: 120px; top: 20%; left: 10%; }
    .banner::after {
      width: 180px; height: 180px;
      bottom: 15%; right: 12%;
      animation-delay: 4s;
    }
    @keyframes float {
      0%,100% { transform: translateY(0) scale(1); }
      50%     { transform: translateY(-30px) scale(1.1); }
    }

    /* 배너 콘텐츠 */
    .banner .content {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -40%);
      text-align: center; color: white; z-index: 2;
      width: 90%; max-width: 800px;
    }
    .banner .cards {
      display: flex; justify-content: center; gap: 20px;
      margin-bottom: 40px;
    }
    .banner .cards .card {
      background: rgba(255,255,255,0.85);
      border-radius: 12px;
      width: 120px; height: 160px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      transition: transform .3s, box-shadow .3s;
    }
    .banner .cards .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 18px rgba(0,0,0,0.2);
    }
    .banner .cards .card i {
      font-size: 36px; color: var(--primary-color); margin-bottom: 10px;
    }
    .banner .cards .card span {
      font-size: 14px; font-weight: 500; color: var(--accent-color);
    }

    /* 수정: 그림자·테두리 얇게 */
    .banner h1 {
      font-size: 48px; margin-bottom: 16px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
      -webkit-text-stroke: 0.5px rgba(0,0,0,0.4);
    }
    .banner p {
      font-size: 20px; margin-bottom: 32px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
      -webkit-text-stroke: 0.3px rgba(0,0,0,0.3);
    }
    .banner .cta {
      display: inline-block;
      background: var(--primary-color);
      padding: 14px 32px;
      border-radius: 6px;
      font-size: 18px;
      font-weight: 600;
      color: white;
      text-decoration: none;
      transition: background .3s, transform .3s;
    }
    .banner .cta:hover {
      background: var(--hover-color);
      transform: translateY(-3px);
    }

    /* 사이드바 */
    .sidebar-left,
    .sidebar-right {
      position: fixed;
      top: 50%; transform: translateY(-50%);
      background: rgba(255,255,255,0.9);
      border-radius: 8px;
      padding: 10px;
      display: flex; flex-direction: column;
      gap: 20px;
      z-index: 1001;
    }
    .sidebar-left { left: 10px; }
    .sidebar-right { right: 10px; }
    .sidebar-left a,
    .sidebar-right a {
      color: var(--primary-color);
      text-decoration: none;
      font-size: 24px;
      text-align: center;
      transition: color .3s;
    }
    .sidebar-left a span,
    .sidebar-right a span {
      display: block;
      font-size: 12px;
      color: var(--accent-color);
      margin-top: 4px;
    }
    .sidebar-left a:hover,
    .sidebar-right a:hover {
      color: var(--hover-color);
    }

    /* 푸터 */
    footer {
      background: var(--primary-color);
      color: white;
      padding: 24px;
      text-align: center;
    }
    footer a {
      color: white;
      text-decoration: none;
      margin: 0 8px;
      font-size: 14px;
    }

    /* 반응형 */
    @media (max-width: 768px) {
      .sidebar-left, .sidebar-right { display: none; }
      .ticker { display: none; }
      .banner .cards { flex-direction: column; }
      .banner .cards .card { width: 140px; height: 180px; }
      .banner h1 { font-size: 32px; }
      .banner p  { font-size: 16px; }
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">LOTTE</div>
    <nav class="navbar">
      <a href="#">홈</a>
      <a href="#">서비스</a>
      <a href="#">카드 혜택</a>
      <a href="#">고객센터</a>
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

  <!-- 뉴스 티커 추가 -->
  <div class="ticker">
    <p>🎉 롯데카드 신규 회원 대상 5만원 캐시백 이벤트 진행 중! &nbsp;&nbsp;|&nbsp;&nbsp; 📢 모바일 앱 설치 시 추가 혜택을 확인하세요! &nbsp;&nbsp;|&nbsp;&nbsp; 🎁 연회비 할인 혜택도 놓치지 마세요!</p>
  </div>



  <div class="search-box">
    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="GET">
      <input type="text" id="query" name="query" placeholder="검색어를 입력하세요..." />
      <button type="submit"><i class="fas fa-search"></i></button>
    </form>

<br />
<?php
    if(isset($_GET["query"]))
    {   

        $query = $_GET["query"];

        if($query == "")
        {
            echo "<font color=\"red\">검색할 단어를 입력하세요.</font>";       
        }

        else            
        {  
            echo "<font color=\"red\">" . sqli($query) . "에 대한 검색결과가 없습니다.</font>";
        }
    }

?>

  </div>
  <aside class="sidebar-left">
    <a href="#"><i class="fas fa-gift"></i><span>이벤트</span></a>
    <a href="#"><i class="fas fa-percent"></i><span>혜택</span></a>
    <a href="#"><i class="fas fa-headset"></i><span>고객지원</span></a>
  </aside>

  <aside class="sidebar-right">
    <a href="#"><i class="fas fa-newspaper"></i><span>뉴스</span></a>
    <a href="#"><i class="fas fa-info-circle"></i><span>안내</span></a>
    <a href="#"><i class="fas fa-phone-alt"></i><span>문의</span></a>
  </aside>

  <section class="banner">
    <div class="content">
      <div class="cards">
        <div class="card"><i class="fas fa-crown"></i><span>프리미엄</span></div>
        <div class="card"><i class="fas fa-gift"></i><span>리워드</span></div>
        <div class="card"><i class="fas fa-coins"></i><span>캐시백</span></div>
      </div>
      <h1>롯데카드와 함께하는 더 나은 생활</h1>
      <p>카드 하나로 더 많은 혜택을 누리세요</p>
      <a href="#" class="cta">지금 신청하기</a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 롯데카드. 모든 권리 보유.</p>
    <p><a href="#">개인정보 처리방침</a> | <a href="#">이용약관</a></p>
  </footer>


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