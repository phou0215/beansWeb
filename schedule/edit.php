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
      $weather_recent = mysqli_query($conn, "SELECT BASE_DATE, BASE_TIME FROM weather ORDER BY UPLOAD_TIME DESC LIMIT 1");
      $row_weather = mysqli_fetch_assoc($weather_recent);
      $forecast_recent = mysqli_query($conn, "SELECT BASE_DATE, BASE_TIME FROM forecast ORDER BY UPLOAD_TIME DESC LIMIT 1");
      $row_forecast = mysqli_fetch_assoc($forecast_recent);
      $weather_base_date = $row_weather['BASE_DATE'];
      $weather_base_time = $row_weather['BASE_TIME'];
      $forecast_base_date = $row_forecast['BASE_DATE'];
      $forecast_base_time = $row_forecast['BASE_TIME'];
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
    <link rel="stylesheet" href="/beans/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="/beans/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/beans/assets/libs/css/style.css">
    <link rel="stylesheet" href="/beans/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link href='/beans/assets/vendor/full-calendar/css/fullcalendar.css' rel='stylesheet' />
    <link href='/beans/assets/vendor/full-calendar/css/fullcalendar.print.css' rel='stylesheet' media='print' />
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
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/beans/img/logout.png" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['name'];?> </h5>
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
                                <a class="nav-link" href="/beans/index.php"><i class="fas fa-clipboard-list"></i>Dashboard</a>
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
                                            <a class="nav-link" href="/beans/weather/forecast.php">Forecast</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-calendar-check"></i>Schedule</a>
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
                            <h2 class="pageheader-title">Schedule Edit</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/beans/schedule/main.php" class="breadcrumb-link">Schedule</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Schedule edit</li>
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
                          <div class="card-header d-flex">
                            <h2 class="card-header-title">Weather</h2>
                            <!-- <select id="selectState" class="custom-select w-auto" style='margin-left:5px;'>
                              <option value="1" selected='selected'></option>
                              <option value="2">평일평균</option>
                              <option value="3">3주평균</option>
                            </select> -->
                            <!-- <button type="button" class="btn btn-success" onclick="category_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button> -->
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                      <!-- 날씨 아이콘 -->
                                      <div class="float-left icon-circle-medium icon-box-lg bg-info-light mt-1">
                                        <i class="fa fa-eye fa-fw fa-sm text-info"></i>
                                      </div>
                                      <!-- 지역 정보 -->
                                      <div class="d-inline-block">
                                        <h2 class="text-muted">충청남도 예산군 덕산면</h2>
                                        <h2 class="mb-0">2021-02-25 16:00(sample)</h2>
                                        <h2 class="mb-0">
                                      </div>
                                      <!-- 해당 날씨 정보 -->
                                      <div class=" d-inline-block float-right">
                                        <h2 class="text-muted">충청남도 예산군 덕산면</h2>
                                        <h2 class="mb-0">2021-02-25 16:00(sample)</h2>
                                        <h2 class="mb-0">
                                      </div>
                                    </div>
                                </div>
                              </div>
                              <!-- 일기 예보 온도 변화 -->
                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"></div>
                            </div>
                          </div>
                          <div class="card-footer text-center">
                            <a href="/beans/weather/main.php" class="card-link">View Details</a>
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
                        <div class="card">
                          <div class="card-header d-flex">
                            <h2 class="card-header-title">Schedule</h2>
                            <select id="selectNet3" class="custom-select ml-auto w-auto">
                              <option value="5G" selected='selected'>5G</option>
                              <option value="LTE">LTE</option>
                            </select>
                            <select id="selectAvg3" class="custom-select w-auto" style='margin-left:5px;'>
                              <option value="1" selected='selected'>전주</option>
                              <option value="2">평일평균</option>
                              <option value="3">3주평균</option>
                            </select>
                            <button id='sum_update' type="button" class="btn btn-info" onclick="category_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button>
                          </div>
                            <div class="card-body">
                                <div id='calendar1'></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end simple calendar -->
                <!-- ============================================================== -->

            </div>
          <!-- ============================================================== -->
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
    <div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="/beans/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="/beans/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/beans/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src='/beans/assets/vendor/full-calendar/js/moment.min.js'></script>
    <script src='/beans/assets/vendor/full-calendar/js/fullcalendar.js'></script>
    <script src='/beans/assets/vendor/full-calendar/js/jquery-ui.min.js'></script>
    <script src='/beans/assets/vendor/full-calendar/js/calendar.js'></script>
    <script src="/beans/assets/libs/js/main-js.js"></script>
</body>

</html>
