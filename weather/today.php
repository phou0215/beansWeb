<?php
    session_start();
    if (!$_SESSION) {
        session_start();
        if (isset($_SESSION['is_login'])!= true) {
            echo "<script>
                  alert('접속을 위해 로그인이 필요합니다.');
                  location.href='/beans/account/signin.php'
                </script>";
        }
    }
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'], $config['user'], $config['password']);
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
    <link rel="stylesheet" href="/beans/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="/beans/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/beans/assets/libs/css/style.css">
    <link rel="stylesheet" href="/beans/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
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
                                            <a href="/beans/index.php" class="connection-item"><img src="/beans/assets/images/dashboard.png" alt="" > <span>Dashboard</span></a>
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
                                          if ($_SESSION['adminAuth'] == 1) {
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
                                    <span class="status"></span><span class="ml-2"><?php if ($_SESSION['adminAuth'] == 1) {
                                            echo "Available  (관리자)";
                                        } else {
                                            echo "Available  (일반)";
                                        }?></span>
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
                                <a class="nav-link active" href="/baens/weather/main.php" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fab fa-cloudversify"></i>Weather</a>
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
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-calendar-check"></i>Schedule</a>
                                <div id="submenu-3" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/beans/schedule/main.php">Main</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" href="/beans/schedule/edit.php">Edit Schedule</a>
                                        </li> -->
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
                              if ($_SESSION['adminAuth'] == 1) {
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
                            <h2 class="pageheader-title">Weather Today</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/beans/weather/today.php" class="breadcrumb-link">Weather</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Weather Today</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <!-- simple weather -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                          <div class="card-header d-flex">
                            <h2 class="card-header-title">Weather</h2>
                            <span class="ml-auto w-auto">
                              <select id="local-picker" class="custom-select btn-primary ml-auto w-auto" data-live-search="true" data-size="10" onchange="changeLocal();">
                                <?php
                                  while ($row_locals = mysqli_fetch_assoc($weather_locals)) {
                                      $local_name = $row_locals['LOCAL'];
                                      if ($local_name == '서울') {
                                          echo "<option value=".$local_name." selected='selected'>".$local_name."</option>";
                                          continue;
                                      }
                                      echo "<option value=".$local_name.">".$local_name."</option>";
                                  }
                                ?>
                              </select>
                              <!-- <button id='sum_update' type="button" class="btn btn-success" onclick="category_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button> -->
                          </span>
                          </div>
                          <div class="card-body">
                            <!-- 일기 예보 정보 카드-->
                            <div id='weather-current-card' class="row" style='display:None;'>
                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="row">
                                  <!-- 날씨 아이콘 -->
                                  <div class="col-xl-2 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="text-center" style="color:rgba(117,163,102,0.8);">
                                      <i id="main-weather-icon" style="font-size:10rem;" ></i>
                                    </div>
                                  </div>
                                  <!-- 지역 정보 -->
                                  <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 col-12 local-area">
                                    <div class="mt-2 mb-2">
                                      <ul class="social-sales list-group list-group-flush local-area">
                                        <li class="list-group-item social-sales-content local-area">
                                          <div class='row'>
                                            <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6'>
                                                <h2 id="main-state" class="text-muted"></h2>
                                                <h4 id="main-base" class="text-muted"></h4>
                                            </div>
                                            <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6'>
                                              <span class="social-sales-count ml-2">
                                                <h4 id="main-rise" class="text-muted"></h4>
                                                <h4 id="main-set" class="text-muted"></h4>
                                              </span>
                                            </div>
                                          </div>
                                        </li>
                                        <li class="list-group-item social-sales-content local-area">
                                          <span class="ml-1" style="color:rgba(117,163,102,0.8);">
                                            <i class="wi wi-thermometer" style="font-size:28px;padding-top:5px;"></i>
                                          </span>
                                          <span class="social-sales-count mr-4">
                                            <h3 id="main-t1h" class="text-muted" style=""></h3>
                                          </span>
                                        </li>
                                        <li class="list-group-item social-sales-content local-area">
                                          <span class="ml-1" style="color:rgba(117,163,102,0.8);">
                                            <i id="main-wsd-icon" class="" style=font-size:32px;></i>
                                          </span>
                                          <span class="social-sales-count mr-4">
                                            <h3 id="main-wsd" class="text-muted"></h3>
                                          </span>
                                        </li>
                                        <li class="list-group-item social-sales-content local-area">
                                          <span class="ml-1" style="color:rgba(117,163,102,0.8);">
                                            <i class="wi wi-humidity" style=font-size:32px;></i>
                                          </span>
                                          <span class="social-sales-count mr-4">
                                            <h3 id="main-reh" class="text-muted"></h3>
                                          </span>
                                        </li>
                                        <li class="list-group-item social-sales-content local-area">
                                          <span class= "ml-1" style="color:rgba(117,163,102,0.8);">
                                            <i class="wi wi-showers" style=font-size:32px;></i>
                                          </span>
                                          <span class="social-sales-count mr-4">
                                            <h3 id="main-rn1" class="text-muted"></h3>
                                          </span>
                                        </li>
                                        <li class="list-group-item social-sales-content local-area">
                                          <span class="m1-1" style="color:rgba(117,163,102,0.8);">
                                            <i class="wi wi-lightning" style=font-size:32px;></i>
                                          </span>
                                          <span class="social-sales-count mr-4">
                                            <h3 id="main-lgt" class="text-muted"></h3>
                                          </span>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                  <!-- 그래프 구역-->
                                  <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="card mt-1 mb-2">
                                      <div class="card-header d-flex">
                                        <div class="toolbar card-toolbar-tabs  ml-auto">
                                          <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                              <a class="nav-link active show" id="chart-t3h" onclick="gridGraph('t3h');" data-toggle="pill" href="#" role="tab" aria-controls="pills-t1h" aria-selected="false">TEMP</a>
                                            </li>
                                            <li class="nav-item">
                                              <a class="nav-link" id="chart-reh" onclick="gridGraph('reh');" data-toggle="pill" href="#" role="tab" aria-controls="pills-reh" aria-selected="false">HUM</a>
                                            </li>
                                            <li class="nav-item">
                                              <a class="nav-link" id="chart-pop" onclick="gridGraph('pop');" data-toggle="pill" href="#" role="tab" aria-controls="pills-pop" aria-selected="false">RAIN</a>
                                            </li>
                                          </ul>
                                        </div>
                                      </div>
                                      <div class="card-body">
                                        <canvas id="chartjs_line_weather" height="70"></canvas>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                </br>
                                </br>
                                <!-- 일기예보 구역 -->
                                <div class='row'>
                                  <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card forecast-card">
                                      <div class="card-body forecast-card">
                                        <ul>
                                          <li class="top-weather text-center">
                                            <span style="color:rgba(117,163,102,0.8);">
                                              <i id="forecast-icon-0" class=""></i>
                                            </span>
                                          </li>
                                          <li class="text-center">
                                            <h3 id="forecast-base-0" class="text-muted"></h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-t1h-0" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-celsius" style=font-size:27px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-wsd-0" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i id="forecast-wsd-icon-0" class="" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-reh-0" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-humidity" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-pop-0" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-barometer" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card forecast-card">
                                      <div class="card-body forecast-card">
                                        <ul>
                                          <li class="top-weather text-center">
                                            <span style="color:rgba(117,163,102,0.8);">
                                              <i id="forecast-icon-1" class=""></i>
                                            </span>
                                          </li>
                                          <li class="text-center">
                                            <h3 id="forecast-base-1" class="text-muted"></h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-t1h-1" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-celsius" style=font-size:27px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-wsd-1" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i id="forecast-wsd-icon-1" class="" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-reh-1" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-humidity" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-pop-1" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-barometer" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card forecast-card">
                                      <div class="card-body forecast-card">
                                        <ul>
                                          <li class="top-weather text-center">
                                            <span style="color:rgba(117,163,102,0.8);">
                                              <i id="forecast-icon-2" class=""></i>
                                            </span>
                                          </li>
                                          <li class="text-center">
                                            <h3 id="forecast-base-2" class="text-muted"></h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-t1h-2" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-celsius" style=font-size:27px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-wsd-2" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i id="forecast-wsd-icon-2" class="" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-reh-2" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-humidity" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-pop-2" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-barometer" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card forecast-card">
                                      <div class="card-body forecast-card">
                                        <ul>
                                          <li class="top-weather text-center">
                                            <span style="color:rgba(117,163,102,0.8);">
                                              <i id="forecast-icon-3" class=""></i>
                                            </span>
                                          </li>
                                          <li class="text-center">
                                            <h3 id="forecast-base-3" class="text-muted"></h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-t1h-3" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-celsius" style=font-size:27px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-wsd-3" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i id="forecast-wsd-icon-3" class="" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-reh-3" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-humidity" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-pop-3" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-barometer" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card forecast-card">
                                      <div class="card-body forecast-card">
                                        <ul>
                                          <li class="top-weather text-center">
                                            <span style="color:rgba(117,163,102,0.8);">
                                              <i id="forecast-icon-4" class=""></i>
                                            </span>
                                          </li>
                                          <li class="text-center">
                                            <h3 id="forecast-base-4" class="text-muted"></h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-t1h-4" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-celsius" style=font-size:27px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-wsd-4" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i id="forecast-wsd-icon-4" class="" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-reh-4" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-humidity" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-pop-4" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-barometer" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card forecast-card">
                                      <div class="card-body forecast-card">
                                        <ul>
                                          <li class="top-weather text-center">
                                            <span style="color:rgba(117,163,102,0.8);">
                                              <i id="forecast-icon-5" class=""></i>
                                            </span>
                                          </li>
                                          <li class="text-center">
                                            <h3 id="forecast-base-5" class="text-muted"></h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-t1h-5" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-celsius" style=font-size:27px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-wsd-5" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i id="forecast-wsd-icon-5" class="" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-reh-5" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-humidity" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                          <li>
                                            <h3 id="forecast-pop-5" class="text-muted">
                                              <span style="color:rgba(117,163,102,0.8);">
                                                <i class="wi wi-barometer" style=font-size:23px;></i>
                                              </span>
                                            </h3>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- <div class="card-footer text-center">
                              <a href="/beans/weather/main.php" class="card-link">View Details</a>
                          </div> -->
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end simple weather -->
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
    <script src="/beans/assets/vendor/multi-select-min/js/bootstrap-select-min.js" type="text/javascript"></script>
    <script src="/beans/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="/beans/assets/vendor/charts/charts-bundle/Chart.bundle.js"></script>
    <script src="/beans/assets/vendor/charts/charts-bundle/chartjs.js"></script>
    <script src='/beans/assets/vendor/full-calendar/js/moment.min.js'></script>
    <script src="/beans/assets/libs/js/main-js.js"></script>
    <script src="/beans/assets/libs/js/weather/get-shortcast.js"></script>
    <script src="/beans/assets/libs/js/weather/get-forecast.js"></script>
</body>

</html>
