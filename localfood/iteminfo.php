<?php
    session_start();
    if(!$_SESSION){
        session_start();
        if(isset($_SESSION['is_login'])!= true){
          echo "<script>
                  alert('접속을 위해 로그인이 필요합니다.');
                  location.href='/beans/account/signin.php'
                </script>";}}

      require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
      $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
      mysqli_select_db($conn, $config['database']);

      // 최신 저장된 데이터 날짜 가져오기
      $weather_locals = mysqli_query($conn, "SELECT LOCAL FROM locals WHERE FLAG=1 ORDER BY LOCAL ASC");
      mysqli_close($conn);
 ?>
<!doctype html>
<html lang="en">
  <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Bean Farm</title>
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="/beans/assets/vendor/bootstrap/css/bootstrap.min.css" />
      <link rel="stylesheet" href="/beans/assets/vendor/fonts/circular-std/style.css" />
      <link rel="stylesheet" href="/beans/assets/libs/css/style.css" />
      <link rel="stylesheet" href="/beans/assets/vendor/fonts/fontawesome/css/fontawesome-all.css" />
      <link rel="stylesheet" href="/beans/assets/vendor/multi-select-min/css/bootstrap-select-min.css"/>
      <link href="/beans/assets/vendor/full-calendar/css/fullcalendar.css" rel="stylesheet" />
      <!-- <link href="/beans/assets/vendor/full-calendar/css/fullcalendar.custom.css" rel="stylesheet" /> -->
      <link href="/beans/assets/vendor/full-calendar/css/fullcalendar.print.css" rel='stylesheet' media='print' />
      <link href="/beans/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet"/>
      <link href="/beans/assets/vendor/fonts/weather-icons/css/weather-icons.css" rel="stylesheet" >
      <link href="/beans/assets/vendor/fonts/weather-icons/css/weather-icons-wind.css" rel="stylesheet" >
  </head>

  <body>
      <!-- ============================================================== -->
      <!-- main wrapper -->
      <!-- ============================================================== -->
      <div class="dashboard-main-wrapper">
          <!-- ============================================================== -->
          <!-- navbar -->
          <!-- ============================================================== -->
          <div class="dashboard-header">
              <nav class="navbar navbar-expand-lg bg-white fixed-top">
                  <a class="navbar-brand" href="/beans/index.php"><span><img id='logo' src="/beans/img/beans.png"></img></span>SH System&nbsp;<span id='vice_name'>Bean Farm</span></a>
                  <div class="collapse navbar-collapse " id="navbarSupportedContent">
                      <ul class="navbar-nav ml-auto navbar-right-top">
                          <li class="nav-item dropdown connection">
                              <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-th-list" style="color:rgba(56,126,66, 0.9); font-size:28px;"></i> </a>
                              <ul class="dropdown-menu dropdown-menu-right connection-dropdown"s>
                                  <li class="connection-list">
                                      <div class="row">
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                              <a href="/baens/index.php" class="connection-item"><img src="/beans/assets/images/dashboard.png" alt="" > <span>Dashboard</span></a>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                              <a href="/beans/weather/today.php" class="connection-item"><img src="/beans/assets/images/weather.png" alt="" > <span>Weather</span></a>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                              <a href="/beans/schedule/main.php" class="connection-item"><img src="/beans/assets/images/schedule.png" alt="" > <span>Scheduler</span></a>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                              <a href="/beans/localfood/orderlist.php" class="connection-item"><img src="/beans/assets/images/localfood.png" alt=""> <span>LocalFood</span></a>
                                          </div>
                                          <?php
                                            if($_SESSION['adminAuth'] == 1){
                                              echo '
                                              <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                                  <a href="/beans/admin/accounts.php" class="connection-item"><img src="/beans/assets/images/admin.png" alt="" ><span>Admin</span></a>
                                              </div>';
                                            }
                                          ?>
                                      </div>
                                  </li>
                                  <!-- <li>
                                      <div class="conntection-footer"><a href="#">More</a></div>
                                  </li> -->
                              </ul>
                          </li>
                          <li class="nav-item dropdown nav-user">
                              <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" style="padding-top:6px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/beans/img/logout.png" alt="" class="user-avatar-md rounded-circle"></a>
                              <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                  <div class="nav-user-info">
                                      <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['name'];?></h5>
                                      <span class="status"></span><span class="ml-2"><?php if($_SESSION['adminAuth'] == 1){echo "Available  (관리자)";}else{echo "Available  (일반)";}?></span>
                                  </div>
                                  <a class="dropdown-item" href="/beans/admin/profile.php"><i class="fas fa-user mr-2"></i>profile</a>
                                  <a class="dropdown-item" href="/beans/account/logout.php"><i class="fas fa-power-off mr-2"></i>Logout</a>
                              </div>
                          </li>
                      </ul>
                  </div>
              </nav>
          </div>
          <!-- ============================================================== -->
          <!-- end navbar -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- left sidebar -->
          <!-- ============================================================== -->
          <div class="nav-left-sidebar sidebar-dark">
              <div class="menu-list">
                  <nav class="navbar navbar-expand-lg navbar-light">
                      <a class="d-xl-none d-lg-none" href="/beans/index.php">Dashboard</a>
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse" id="navbarNav">
                          <ul class="navbar-nav flex-column">
                              <li class="nav-divider">
                                  Menu
                              </li>
                              <li class="nav-item ">
                                  <a class="nav-link active" href="/beans/index.php"><i class="fas fa-clipboard-list"></i>Dashboard</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="/baens/weather/main.php" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fab fa-cloudversify"></i>Weather</a>
                                  <div id="submenu-2" class="collapse submenu" style="">
                                      <ul class="nav flex-column">
                                         <!-- <span class="badge badge-secondary">New</span> -->
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/weather/today.php">Today</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/weather/history.php">History</a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-calendar-check"></i>Schedule</a>
                                  <div id="submenu-3" class="collapse submenu" style="">
                                      <ul class="nav flex-column">
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/schedule/main.php">Main</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/schedule/edit.php">Edit Schedule</a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class=" fas fa-truck"></i>LocalFood</a>
                                  <div id="submenu-4" class="collapse submenu" style="">
                                      <ul class="nav flex-column">
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/localfood/orderlist.php">Order List</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/localfood/Demand.php">Demand</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/localfood/supplier.php">Supplier</a>
                                          </li>
                                          <li class="nav-item">
                                              <a class="nav-link" href="/beans/localfood/iteminfo.php">Item Info</a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <?php
                                if($_SESSION['adminAuth'] == 1){
                                      echo '
                                      <li class="nav-item ">
                                          <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5"><i class="far fa-file-alt"></i>Admin</a>
                                          <div id="submenu-5" class="collapse submenu" style="">
                                              <ul class="nav flex-column">
                                                  <li class="nav-item">
                                                      <a class="nav-link" href="/beans/admin/accounts.php">User</a>
                                                  </li>
                                              </ul>
                                          </div>
                                      </li>
                                      ';
                                  }
                              ?>
                              <li class="nav-item">
                              </li>
                          </ul>
                      </div>
                  </nav>
              </div>
          </div>
          <!-- ============================================================== -->
          <!-- end left sidebar -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- wrapper  -->
          <!-- ============================================================== -->
          <div class="dashboard-wrapper">
            <div class="container-fluid  dashboard-content">
                  <!-- ============================================================== -->
                  <!-- pageheader -->
                  <!-- ============================================================== -->
                  <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                          <div class="page-header">
                              <h2 class="pageheader-title">Bean Farm Dashboard</h2>
                              <div class="page-breadcrumb">
                                  <nav aria-label="breadcrumb">
                                      <ol class="breadcrumb">
                                          <li class="breadcrumb-item"><a href="/beans/index.php" class="breadcrumb-link">Dashboard</a></li>
                                          <li class="breadcrumb-item active" aria-current="page">Bean Farm Dashboard</li>
                                      </ol>
                                  </nav>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- ============================================================== -->
                  <!-- end pageheader -->
                  <!-- ============================================================== -->
                  <!-- ============================================================== -->
                  <!-- simple weather -->
                  <!-- ============================================================== -->
                  <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                          <div id='searchForm' class="card-body" style="padding-right:10px;">
                            <!-- onsubmit="return checkValue();" -->
                            <form id="searchForm" action="/voc/data/index.php?page=1" method="GET" onsubmit="return checkValue();">
                              <input id="selectType" type="text" name="selectType" hidden>
                              <ul id="searchList">
                                <li>
                                  <label for="count_list" style="font-size:12px;">페이지</label>
                                </li>
                                <li>
                                  <select id="count_list" name="onePage" style="height:35px; width:50px">
                                    <option value='10'>10</option>
                                    <option value='20'>20</option>
                                    <option value='30'>30</option>
                                    <option value='50'>50</option>
                                    <option value='100'>100</option>
                                  </select>
                                </li>
                                <li>
                                  <label for="startDate" id="date" style="font-size:12px;">기간</label>
                                </li>
                                <li>
                                  <input id="startDate" type="date" name="startDate" style="height:35px;">
                                </li>
                                <li>
                                  <input id="endDate" type="date" name="endDate" style="height:35px;">
                                </li>
                                <li>
                                  <label for="searchSelect" style="font-size:12px;">항목</label>
                                </li>
                                <li>
                                  <select id="searchSelect" name="indexName" onchange="checkSelect();" style="height:35px; display:block;">
                                    <option value='issueId'>이슈ID</option>
                                    <option value='receiveDate'>등록일</option>
                                    <option value='model'>모델명</option>
                                    <option value='model2'>모델명2</option>
                                    <option value='manu'>제조사</option>
                                    <option value='state'>시도(시/도)</option>
                                    <option value="swVer">SW 버전</option>
                                    <option value='class1'>메모분류</option>
                                    <option value='memo'>메모내용</option>
                                    <option value='model2,memo'>모델명2 + 메모내용</option>
                                    <option value='manu,memo'>제조사 + 메모내용</option>
                                    <option value='model2,class1'>모델명2 + 메모분류</option>
                                    <option value='manu,class1'>제조사 + 메모분류</option>
                                    <option value='model2,swVer'>모델명2 + SW 버전</option>
                                    <option value='model,memo'>모델명 + 메모내용</option>
                                    <option value='manu,memo'>제조사 + 메모내용</option>
                                    <option value='model,class1'>모델명 + 메모분류</option>
                                    <option value='manu,class1'>제조사 + 메모분류</option>
                                    <option value='model,swVer'>모델명 + SW 버전</option>
                                  </select>
                                </li>
                                <!-- <li>
                                  <label for="searchText" style="font-size:12px;">검색어</label>
                                </li> -->
                                <li>
                                  <input id="searchText" type="text" name="searchValue" placeholder="" style="height:35px;">
                                </li>
                                <li class='searchOp' hidden>
                                  <input id="searchText_2" class='searchOp' type="text" name="searchValue_2" hidden placeholder="" style="height:35px;">
                                </li>
                                <li>
                                  <input class="btn btn-info" id="searchButton" type="submit" value="검색" style="margin-left:20px; width:100px;" onclick="">
                                </li>
                              </ul>
                            </form>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-body">
                            <ul id="searchList">
                              <li>
                                <form id="export_excel" action="/voc/data/down/csvDownload.php" method="POST" onsubmit="return fileDownload();">
                                    <input type="text" name="re" value="<?php echo $_SERVER['REQUEST_URI'];?>" hidden style="display:none">
                                    <input type="text" name="base" value="<?php echo $download_base;?>" hidden style="display:none">
                                    <input type="submit" class="btn btn-primary" style="width:200px;" value="csv 다운로드">
                                </form>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- ============================================================== -->
                  <!-- end simple weather -->
                  <!-- ============================================================== -->
                  <!-- ============================================================== -->
                  <!-- simple calendar -->
                  <!-- ============================================================== -->
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    </div>
                  </div>
            </div>
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                  Copyright © 2021 Bean Farm. All rights reserved.
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="text-md-right footer-links d-none d-sm-block">
                                    <a href="http://www.naver.com" target="_blank">Visit Bean Farm Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
          </div>
        <!-- ============================================================== -->
        <!-- end simple calendar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
      </div>
      <!-- end main wrapper -->
      <!-- ============================================================== -->
      <!-- Optional JavaScript -->
      <script src="/beans/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
      <script src="/beans/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
      <script src="/beans/assets/vendor/multi-select-min/js/bootstrap-select-min.js" type="text/javascript"></script>
      <script src="/beans/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
      <script src='/beans/assets/vendor/full-calendar/js/moment.min.js'></script>
      <script src='/beans/assets/vendor/full-calendar/js/fullcalendar.js'></script>
      <script src='/beans/assets/vendor/full-calendar/js/jquery-ui.min.js'></script>
      <script src='/beans/assets/vendor/full-calendar/js/calendar-read.js'></script>
      <script src="/beans/assets/vendor/charts/charts-bundle/Chart.bundle.js"></script>
      <script src="/beans/assets/vendor/charts/charts-bundle/chartjs.js"></script>
      <script src="/beans/assets/libs/js/main-js.js"></script>
      <script src="/beans/assets/libs/js/weather/get-shortcast.js"></script>
      <script src="/beans/assets/libs/js/weather/get-forecast.js"></script>
      <script src="/beans/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.min.js"></script>
  </body>

</html>
