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
  <title>디지털 자산 거래소</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --nav-bg-start: #4ab3f4;
      --nav-bg-end: #004466;
      --sidebar-bg: #eaf6ff;
      --main-bg: #ffffff;
      --accent: #007acc;
      --text-dark: #004466;
      --text-light: #fff;
      --notice-bg: rgba(0, 68, 102, 0.6);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Pretendard', sans-serif;
      background-color: #d9f0ff;
      color: var(--text-dark);
      font-size: 18px;
    }

    header {
      height: 180px;
      color: var(--text-light);
      padding: 0 30px;
      background: linear-gradient(to right, var(--nav-bg-start), var(--nav-bg-end));
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .login-wrapper {
      position: absolute;
      top: 20px;
      right: 30px;
      z-index: 10;
    }

    .logo {
      font-size: 2.8em;
      font-weight: 700;
      z-index: 2;
      user-select: none;
    }

    .notice-marquee {
      background: var(--notice-bg);
      color: var(--text-light);
      padding: 6px 12px;
      margin-top: 10px;
      overflow: hidden;
      white-space: nowrap;
      border-radius: 6px;
      user-select: none;
      font-weight: 600;
    }

    .notice-marquee span {
      display: inline-block;
      padding-left: 100%;
      animation: marquee 18s linear infinite;
    }

    @keyframes marquee {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }

    .slider {
      position: absolute;
      top: 0; right: 0; bottom: 0; left: 0;
      z-index: 1;
      overflow: hidden;
      border-radius: 0 0 15px 15px;
    }

    .slider img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0;
      position: absolute;
      transition: opacity 1.2s ease-in-out;
      user-select: none;
    }

    .slider img.active {
      opacity: 0.3;
    }

    .container {
      display: flex;
      min-height: calc(100vh - 180px);
      background-color: #d9f0ff;
    }

    nav {
      width: 220px;
      background-color: var(--nav-bg-end);
      color: var(--text-light);
      display: flex;
      flex-direction: column;
      padding: 25px;
      font-size: 18px;
      user-select: none;
      box-shadow: 2px 0 8px rgba(0,68,102,0.2);
    }

    nav a {
      color: var(--text-light);
      text-decoration: none;
      margin-bottom: 14px;
      font-weight: 600;
      display: block;
      cursor: pointer;
      border-radius: 6px;
      padding: 8px 12px;
      transition: background-color 0.25s ease;
    }

    nav a:hover {
      background-color: var(--nav-bg-start);
    }

    nav a.active {
      background-color: var(--accent);
      font-weight: 700;
    }

    .dropdown {
      display: none;
      padding-left: 16px;
      margin-top: 6px;
    }

    .dropdown a {
      font-size: 16px;
      margin-bottom: 10px;
      color: var(--text-light);
    }

    .sidebar {
      width: 180px;
      background-color: var(--sidebar-bg);
      padding: 25px;
      font-size: 16px;
      box-shadow: -2px 0 8px rgba(0,68,102,0.1);
      user-select: none;
    }

    .sidebar h4 {
      font-weight: 700;
      margin-bottom: 14px;
      color: var(--accent);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar li {
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #004466;
    }

    .sidebar a {
      text-decoration: none;
      font-weight: 600;
      color: #007acc;
      transition: color 0.25s ease;
    }

    .sidebar a:hover {
      color: #004466;
      text-decoration: underline;
    }

    main.main {
      flex: 1;
      max-width: 1100px;
      margin: 0 auto;
      padding: 50px 40px;
      background-color: var(--main-bg);
      display: grid;
      gap: 28px;
      grid-template-columns: repeat(2, 1fr);
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 122, 204, 0.15);
      animation: fadeIn 1s ease-in forwards;
      user-select: none;
    }

    @media (max-width: 768px) {
      .main {
        grid-template-columns: 1fr;
        padding: 30px 25px;
      }
      .container {
        flex-direction: column;
      }
      nav, .sidebar {
        width: 100%;
        box-shadow: none;
      }
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(15px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    .menu-card {
      background-color: #e6f2ff;
      border-radius: 12px;
      padding: 28px 24px;
      box-shadow: 0 6px 12px rgba(0,122,204,0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      justify-content: center;
      user-select: none;
    }

    .menu-card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 10px 25px rgba(0,122,204,0.3);
    }

    .menu-card h3 {
      font-size: 24px;
      color: var(--accent);
      margin-bottom: 14px;
      font-weight: 700;
    }

    .menu-card p {
      font-size: 16px;
      line-height: 1.5;
      color: #004466;
    }

    footer {
      background-color: #004466;
      color: #cce7ff;
      text-align: center;
      padding: 25px;
      font-size: 16px;
      user-select: none;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">Upbit</div>
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
    <div class="notice-marquee" aria-live="polite" aria-atomic="true">
      <span>🎉 누적 거래액 1,000억 원 돌파! &nbsp;&nbsp;&nbsp; 안전하고 투명한 디지털 자산 거래소 &nbsp;&nbsp;&nbsp; 24시간 고객지원 서비스 제공</span>
    </div>
    <div class="slider" aria-hidden="true">
      <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1280&q=80" class="active" alt="디지털 자산 이미지 1" />
      <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1280&q=80" alt="디지털 자산 이미지 2" />
      <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1280&q=80" alt="디지털 자산 이미지 3" />
    </div>
  </header>

  <div class="container" role="main">
    <nav role="navigation" aria-label="주 메뉴">
      <a href="#" class="active">거래소</a>
      <a href="#">입출금</a>
      <a href="#">코인동향</a>
      <a href="#">고객지원</a>
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
            echo "<font color=\"white\">검색할 단어를 입력하세요.</font>";       
        }

        else            
        {  
            echo "<font color=\"white\">" . sqli($query) . "에 대한 검색결과가 없습니다.</font>";
        }
    }

?>

  </div>
    </nav>


    <div class="sidebar" aria-label="공지 및 빠른 링크">
      <h4><i class="fas fa-bullhorn"></i> 공지사항</h4>
      <ul>
        <li><i class="fas fa-bolt"></i> 시스템 점검 안내 (7월 2일 새벽 2시)</li>
        <li><i class="fas fa-shield-alt"></i> 개인정보 처리방침 개정 예정</li>
        <li><i class="fas fa-gas-pump"></i> 6월 마지막 주 GAS, VTHO 지급 안내</li>
      </ul>
      <h4><i class="fas fa-link"></i> 빠른 링크</h4>
      <ul>
        <li><i class="fas fa-file-alt"></i><a href="#"> 이용 가이드</a></li>
      </ul>
    </div>

    <main class="main" aria-live="polite" aria-atomic="true">
      <div class="menu-card" tabindex="0">
        <h3>거래소</h3>
        <p>최신 마켓 정보를 실시간으로 제공합니다. 다양한 암호화폐를 편리하게 거래하세요.</p>
      </div>
      <div class="menu-card" tabindex="0">
        <h3>입출금</h3>
        <p>간편하고 안전한 입출금 서비스를 이용하세요. 빠른 자산 이동과 출금 확인을 지원합니다.</p>
      </div>
      <div class="menu-card" tabindex="0">
        <h3>코인동향</h3>
        <p>다양한 코인의 시세와 트렌드를 한눈에 확인하세요. 실시간 데이터와 전문 분석 제공.</p>
      </div>
      <div class="menu-card" tabindex="0">
        <h3>고객지원</h3>
        <p>문의사항이 있으신가요? 빠르고 친절한 고객 지원 서비스를 제공합니다.</p>
      </div>
    </main>
  </div>

  <footer>
    &copy; 2025 Upbit. All rights reserved.
  </footer>

  <script>
    // 슬라이더 자동 전환
    const slides = document.querySelectorAll('.slider img');
    let current = 0;
    setInterval(() => {
      slides[current].classList.remove('active');
      current = (current + 1) % slides.length;
      slides[current].classList.add('active');
    }, 5000);

    // 네비게이션 active 토글 (필요시 확장 가능)
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
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