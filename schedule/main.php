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
      // $weather_recent = mysqli_query($conn, "SELECT BASE_DATE, BASE_TIME FROM weather ORDER BY UPLOAD_TIME DESC LIMIT 1");
      // $row_weather = mysqli_fetch_assoc($weather_recent);
      // $forecast_recent = mysqli_query($conn, "SELECT BASE_DATE, BASE_TIME FROM forecast ORDER BY UPLOAD_TIME DESC LIMIT 1");
      // $row_forecast = mysqli_fetch_assoc($forecast_recent);
      // mysqli_close($conn);
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
    <link rel="stylesheet" href="/beans/assets/vendor/multi-select-min/css/bootstrap-select-min.css"/>
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
                            <h2 class="pageheader-title">Schedule Main</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/beans/schedule/main.php" class="breadcrumb-link">Schedule</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Schedule Main</li>
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
                <!-- simple calendar -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- search bar  -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- end search bar  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 col-12">
                      <div class="card">
                          <div class="card-body">
                            <div id='calendar1'></div>
                          </div>
                      </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- influencer sidebar  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="font-16">Sorting By Part Type</h3>
                                    <select id="select_part" class="form-control btn-primary" style="color:white;">
                                        <option value="Tot" selected>전체</option>
                                        <option value="Soft">소프트웨어</option>
                                        <option value="Field">필드</option>
                                        <option value="Hard">하드웨어</option>
                                        <option value="Event">이벤트</option>
                                        <option value="Etc">기타</option>
                                    </select>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16">Sorting By Test Type</h3>
                                    <select id="select_test" class="form-control btn-primary" style="color:white;">
                                        <option value="Tot" selected>전체</option>
                                        <option value="OS">OS</option>
                                        <option value="MR">MR</option>
                                        <option value="ER">ER</option>
                                    </select>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16">Schedule File Upload</h3>
                                    <!-- <input id="upload-schedule"  class="form-control" type="file" accept=".xls,.xlsx">

                                  <button click="uploadFile();" class="btn btn-secondary btn-lg btn-block">Upload</a> -->
                                    <form id="uplad_form" method="POST" enctype="multipart/form-data" action='/beans/phpdata/schedule/upload-file.php' onsubmit="return uploadFileCheck();">
                                      <input id="file_path" class="form-control" type="file" name="file_path" accept='.xls,.xlsx,.csv'/>
                                      </br>
                                      </br>
                                      <input class="btn btn-secondary btn-lg btn-block" name="submit" type="submit" value="UPLOAD"/>
                                    </form>
                                </div>
                                <!-- <div class="card-body border-top">
                                    <a href="#" class="btn btn-secondary btn-lg btn-block">Submit</a>
                                </div> -->
                            </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end influencer sidebar  -->
                    <!-- ============================================================== -->
                  </div>
                </div>
                <!-- ============================================================== -->
                <!-- end simple calendar -->
                <!-- ============================================================== -->
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

        </div>
    <div>
    <!-- ============================================================== -->
    <!-- Start of Add and edit popup modal area -->
    <div id="eventModal" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                      <label for="edit-title">작업명</label>
                      <input id="edit-title" type="text" name="edit-title" data-parsley-trigger="change" required="" placeholder="작업명 입력" autocomplete="off" class="form-control">
                </div>
                <div class="form-group">
                      <label for="edit-type">작업타입</label>
                      <!-- <input id="edit-type" type="text" name="edit-type" data-parsley-trigger="change" required="" placeholder="작업타입 입력" autocomplete="off" class="form-control"> -->
                      <select id="edit-type" class="selectpicker form-control" style="font-size:11px; margin-left:5px;" data-style="btn-primary" data-width:"100px">
                        <option value="Soft" selected="selected">소프트웨어</option>
                        <option value="Field">필드</option>
                        <option value="Hard">하드웨어</option>
                        <option value="Event">이벤트</option>
                        <option value="etc">기타</option>
                      </select>
                </div>
                <div class="form-group">
                      <label for="edit-start">작업시작</label>
                      <input id="edit-start" type="text" name="edit-start" data-parsley-trigger="change" required="" placeholder="YYYY-MM-DD or YYYY-MM-DD HH:mm" autocomplete="off" class="form-control">
                </div>
                <div class="form-group">
                      <label for="edit-end">작업종료</label>
                      <input id="edit-end" type="text" name="edit-end" data-parsley-trigger="change" required="" placeholder="YYYY-MM-DD or YYYY-MM-DD HH:mm" autocomplete="off" class="form-control">
                </div>
                <div class="form-group">
                      <label for="edit-desc">작업설명</label>
                      <textarea id="edit-desc" name="edit-desc" data-parsley-trigger="change" type="textarea" required="" placeholder="작업설명입력"cols="20" rows="5" class="form-control"></textarea>
                </div>
                <!-- <div class="form-group">
                      <label for="edit-color">색상</label>
                      <input type="text" id="edit-color" class="form-control edit-color minicolors-input" data-control="hue" value="#ff6161" size="7" s>
                </div> -->
                <div class="form-group">
                        <label class="be-checkbox custom-control custom-checkbox" for="edit-allDay">
                        <input type="checkbox" class="custom-control-input" id="edit-allDay" checked><span class="custom-control-label">All Day</span>
                </div>
                <div class="form-group modal-footer modalBtnContainer-addEvent">
                      <p class="text-right">
                        <button type="button" class="btn btn-space btn-default" data-dismiss="modal">Exit</button>
                        <button id="save-event" type="button" class="btn btn-space btn-primary">Save</button>
                      </p>
                </div>
                <div class="form-group modal-footer modalBtnContainer-modifyEvent">
                      <button type="button" class="btn btn-space btn-default" data-dismiss="modal">Exit</button>
                      <button id="delete-event" type="button" class="btn btn-space btn-danger">Delete</button>
                      <button id="update-event" type="button" class="btn btn-space btn-success">Update</button>
                </div>
              </div>
            </div>
        </div>
      </div>
    <!-- ============================================================== -->
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
    <script src='/beans/assets/vendor/full-calendar/js/calendar.js'></script>
    <script src='/beans/assets/vendor/full-calendar/js/editSchedule.js'></script>
    <script src='/beans/assets/vendor/full-calendar/js/newSchedule.js'></script>
    <script src="/beans/assets/libs/js/main-js.js"></script>
</body>

</html>
