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
 ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bean Farm</title>
    <!-- Bootstrap CSS -->
    <link href="/beans/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <link href="/beans/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link href="/beans/assets/libs/css/style.css" rel="stylesheet" >
    <link href="/beans/assets/vendor/fonts/fontawesome/css/fontawesome-all.css" rel="stylesheet" >
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
                                            <a class="nav-link" href="/beans/weather/forecast.php">forecast</a>
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
                            <h2 class="pageheader-title">Weather Forecast</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/beans/weather/today.php" class="breadcrumb-link">Weather</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Forecast</li>
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
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
                  <div class="card">
                    <div class="card-header d-flex">
                      <h2 class="card-header-title">Middle Term Forecast</h2>
                    </div>
                    <div class="card-body">
                      <div class="section-block">
                          <h5 class="section-title"></h5>
                      </div>
                      <div class="tab-regular">
                          <ul class="nav nav-tabs " id="myTab" role="tablist">
                              <li class="nav-item">
                                  <a class="nav-link" id="sk-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">서울/경기</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="kg-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">강원도</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link active" id="ch-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">충청남도/충청북도</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="jl-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">전라남도/전라북도</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="ks-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">경상남도/경상북도</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="je-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">제주특별자치도</a>
                              </li>
                          </ul>
                          <div class="tab-content" id="fcTab">
                              <div class="tab-pane fade show active" id="sk-region" role="tabpanel" aria-labelledby="sk-tab">

                              </div>
                              <div class="tab-pane fade" id="sk-region" role="tabpanel" aria-labelledby="kg-tab">

                              </div>
                              <div class="tab-pane fade" id="sk-region" role="tabpanel" aria-labelledby="ch-tab">

                              </div>
                              <div class="tab-pane fade" id="sk-region" role="tabpanel" aria-labelledby="ch-tab">

                              </div>
                              <div class="tab-pane fade" id="sk-region" role="tabpanel" aria-labelledby="ch-tab">

                              </div>
                              <div class="tab-pane fade" id="sk-region" role="tabpanel" aria-labelledby="ch-tab">

                              </div>
                              <div class="tab-pane fade" id="sk-region" role="tabpanel" aria-labelledby="ch-tab">

                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>
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
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="/beans/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="/beans/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/beans/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src='/beans/assets/vendor/full-calendar/js/moment.min.js'></script>
    <script src="/beans/assets/libs/js/weather/get-middleforecast.js"></script>
    <script src="/beans/assets/libs/js/main-js.js"></script>
</body>

</html>
