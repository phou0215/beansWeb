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
      <link rel="stylesheet" href="/beans/assets/vendor/DataTables-cloud/datatables.css"/>
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
                                              <a class="nav-link" href="/beans/localfood/demander.php">Demander</a>
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
                              <h2 class="pageheader-title">Order List</h2>
                              <div class="page-breadcrumb">
                                  <nav aria-label="breadcrumb">
                                      <ol class="breadcrumb">
                                          <li class="breadcrumb-item"><a href="/beans/index.php" class="breadcrumb-link">LocalFood</a></li>
                                          <li class="breadcrumb-item active" aria-current="page">Order List</li>
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
                              <span class="ml-auto w-auto">
                                <label class="ml-auto w-auto" for="start-picker" style="color:white;">시작일</label>
                                <input id="start-picker" class="ml-2 w-auto btn-primary" type="date" name="sdate" value="" style="height:37px;">
                                <label class="ml-2 w-auto" for="end-picker" style="color:white;">종료일</label>
                                <input id="end-picker" class="ml-2 w-auto btn-primary" type="date" name="edate" value="" style="height:37px;">
                                <select id="comp-picker" class="custom-select btn-primary ml-2 w-auto">
                                  <option value="ALL">전체</option>
                                  <option value="A01">당진</option>
                                  <option value="A02">천안</option>
                                  <option value="A03">공주</option>
                                  <option value="A04">보령</option>
                                  <option value="A05">논산</option>
                                  <option value="A06">예산</option>
                                  <option value="A07" selected='selected'>태안</option>
                                  <option value="A08">서산</option>
                                  <option value="A09">서천</option>
                                  <option value="A10">홍성</option>
                                  <option value="A11">아산</option>
                                  <option value="A12">청양</option>
                                  <option value="A13">부여</option>
                                  <option value="A14">금산</option>
                                </select>
                                <select id="item-picker" class="custom-select btn-primary ml-2 w-auto">
                                  <option value="ALL">전체</option>
                                  <option value="B01" selected='selected'>농산물</option>
                                  <option value="B02">수산물</option>
                                  <option value="B04">축산물</option>
                                  <option value="B05">가공식품</option>
                                </select>
                                <button id='order-update' class="btn btn-secondary ml-2 w-2" onclick="setTableData();">UPDATE</button>
                            </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id='table-data' class="stripe row-border order-column" style="width:100%">
                                      <thead>
                                          <tr>
                                            <th class="border-0 text-center">센터명</th>
                                            <th class="border-0 text-center">대분류</th>
                                            <th class="border-0 text-center">중분류</th>
                                            <th class="border-0 text-center">소분류</th>
                                            <th class="border-0 text-center">견적일</th>
                                            <th class="border-0 text-center">상품명</th>
                                            <th class="border-0 text-center">규격</th>
                                            <th class="border-0 text-center">공급처</th>
                                            <th class="border-0 text-center">수량</th>
                                            <th class="border-0 text-center">빈도수</th>
                                            <th class="border-0 text-center">금액</th>
                                          </tr>
                                      </thead>
                                    </table>
                                </div>
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
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header"> Product Category</h5>
                            <div class="card-body">
                                <div class="ct-chart-category ct-golden-section" style="height: 315px;"></div>
                                <div class="text-center m-t-40">
                                    <span class="legend-item mr-3">
                                            <span class="fa-xs text-primary mr-1 legend-tile"><i class="fa fa-fw fa-square-full "></i></span><span class="legend-text">Man</span>
                                    </span>
                                    <span class="legend-item mr-3">
                                        <span class="fa-xs text-secondary mr-1 legend-tile"><i class="fa fa-fw fa-square-full"></i></span>
                                    <span class="legend-text">Woman</span>
                                    </span>
                                    <span class="legend-item mr-3">
                                        <span class="fa-xs text-info mr-1 legend-tile"><i class="fa fa-fw fa-square-full"></i></span>
                                    <span class="legend-text">Accessories</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-right">
                                        <select class="custom-select">
                                            <option selected>Today</option>
                                            <option value="1">Weekly</option>
                                            <option value="2">Monthly</option>
                                            <option value="3">Yearly</option>
                                        </select>
                                    </div>
                                <h5 class="mb-0"> Product Sales</h5>
                            </div>
                            <div class="card-body">
                                <div class="ct-chart-product ct-golden-section"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12">
                        <!-- ============================================================== -->
                        <!-- top perfomimg  -->
                        <!-- ============================================================== -->
                        <div class="card">
                            <h5 class="card-header">Top Performing Campaigns</h5>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table no-wrap p-table">
                                        <thead class="bg-light">
                                            <tr class="border-0">
                                                <th class="border-0">Campaign</th>
                                                <th class="border-0">Visits</th>
                                                <th class="border-0">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Campaign#1</td>
                                                <td>98,789 </td>
                                                <td>$4563</td>
                                            </tr>
                                            <tr>
                                                <td>Campaign#2</td>
                                                <td>2,789 </td>
                                                <td>$325</td>
                                            </tr>
                                            <tr>
                                                <td>Campaign#3</td>
                                                <td>1,459 </td>
                                                <td>$225</td>
                                            </tr>
                                            <tr>
                                                <td>Campaign#4</td>
                                                <td>5,035 </td>
                                                <td>$856</td>
                                            </tr>
                                            <tr>
                                                <td>Campaign#5</td>
                                                <td>10,000 </td>
                                                <td>$1000</td>
                                            </tr>
                                            <tr>
                                                <td>Campaign#5</td>
                                                <td>10,000 </td>
                                                <td>$1000</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <a href="#" class="btn btn-outline-light float-right">Details</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end top perfomimg  -->
                        <!-- ============================================================== -->
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
      <script src="/beans/assets/vendor/DataTables-cloud/datatables.js"></script>
      <!-- chartjs -->
      <script src="/beans/assets/vendor/charts/charts-bundle/Chart.bundle.js"></script>
      <script src="/beans/assets/vendor/charts/charts-bundle/chartjs.js"></script>
      <script src="/beans/assets/libs/js/orderlist/get-orderlist.js"></script>
      <script src="/beans/assets/libs/js/main-js.js"></script>
  </body>

</html>
