<?php
session_start();

$db['host'] = "localhost"; // DBサーバのURL
$db['user'] = "g031p015"; // ユーザー名
$db['pass'] = "g031p015PW"; // ユーザー名のパスワード
$db['dbname'] = "g031p015"; // データベース名

$loginName = "ゲストユーザ";
// エラーメッセージの初期化
$errorMessage = "";
$login = false;
$settingDisplay = "disabled";

// ログイン確認
if (isset($_SESSION["ID"])) {
    // IDの格納
    $loginID = $_SESSION['ID'];
    $loginName = $_SESSION['name'];
    $login = true;
} else {
    $loginID = 0;
}

// パラメータの確認


    
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

       //活動記録呼び出し
    $activityCount = 0;
    $activ_name = array();
    $activity = array(); // 調査情報入れる変数
    $stmt = $pdo->prepare('SELECT * FROM activity');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array($activ_name, $row['name']);
        $activity[] = array('activ_name' => $row['activ_name'], 'activityID' => $row['activityID'], 'researchStart' => $row['researchStart'], 'researchEnd' => $row['researchEnd'], 'activ_details' => $row['activ_details']);
        $activityCount++;
    }

//状況報告の呼び出し
    $mediaCount = 0;
    $media_name = array();
    $media = array(); // 調査情報入れる変数
    $stmt = $pdo->prepare('SELECT * FROM media_search');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // array_push($media_name, $row['name']);
        $media[] = array('media_name' => $row['media_name'], 'mediaID' => $row['mediaID'], 'media_start' => $row['media_start'], 'media_end' => $row['media_end'], 'media_details' => $row['media_details']);
        $mediaCount++;
    }

//ごみ報告の呼び出し
    $debrisCount = 0;
    $debris_name = array();
    $debris = array(); // 調査情報入れる変数
    $stmt = $pdo->prepare('SELECT * FROM debris');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // array_push($debris_name, $row['name']);
        $debris[] = array('debris_name' => $row['debris_name'], 'debrisID' => $row['debrisID'], 'debris_start' => $row['debris_start'], 'debris_end' => $row['debris_end'], 'debris_details' => $row['media_details']);
        $debrisCount++;
    }
$allcount = $activityCount + $debrisCount + $mediaCount;

            // 全ての報告情報の取得（省略可能）
            $stmt = $pdo->prepare('SELECT * FROM activ_report ');
            $stmt->execute();

            
            $reportCount = 0;
            $reportAry = array();
            $userCountAry = array();
            $reportDateAry = array();
            $lavel = "";
            $reportData = "";
            $reportDataSum = "";
            $sum = 0;

            // 検証結果取得用のprepare
            // $stmt2 = $pdo->prepare('SELECT * FROM validation WHERE reportID = ?');

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $searchCount++;
                // 年月日以外の要素を除外して配列に格納
                array_push($reportDateAry, preg_replace('/\s\d\d:\d\d:\d\d/', '', $row['date']));
                // ユーザ情報の格納
                // array_push($userCountAry, $row['userID']);
                $_SESSION["login_id"] = "id00001";
                $comment = '<p>報告日:' . $row['date'] . '<br>活動団体名:' . $row['group_name'] . '</p>';
                $comment .= '<a href="activ-report-detail.php?id=' . $row['activityID'] . '&report=' . $row['activ_reID'] . '">詳細情報を確認</a>';

                // マップピン情報を格納
                $reportAry[] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'comment' => $comment, 'group_name' => $row['group_name']);
            }
            // }
            $stmt = $pdo->prepare('SELECT * FROM media_report ');
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $searchCount++;
               // 年月日以外の要素を除外して配列に格納
             array_push($reportDateAry, preg_replace('/\s\d\d:\d\d:\d\d/', '', $row['date']));
            //  // ユーザ情報の格納
            //  // array_push($userCountAry, $row['userID']);
             $_SESSION["login_id"] = "id00001";
             $comment = '<p>報告日:' . $row['date'] . '<br>報告者' . $row['media_user'] . '</p>';
             $comment .= '<a href="media-report-detail.php?id=' . $row['mediaID'] . '&report=' . $row['media_reID'] . '">詳細情報を確認</a>';
        
            //  // マップピン情報を格納
             $reportAry[] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'comment' => $comment, 'group_name' => $row['media_user']);
          // echo $comment;
            }

            $stmt = $pdo->prepare('SELECT * FROM debris_report ');
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $searchCount++;
               // 年月日以外の要素を除外して配列に格納
             array_push($reportDateAry, preg_replace('/\s\d\d:\d\d:\d\d/', '', $row['date']));
            //  // ユーザ情報の格納
            //  // array_push($userCountAry, $row['userID']);
             $_SESSION["login_id"] = "id00001";
             $comment = '<p>報告日:' . $row['date'] . '<br>発見報告者:' . $row['debris_user'] . '</p>';
             $comment .= '<a href="debris-report-detail.php?id=' . $row['debrisID'] . '&report=' . $row['debris_reID'] . '">詳細情報を確認</a>';
        
            //  // マップピン情報を格納
             $reportAry[] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'comment' => $comment, 'group_name' => $row['debris_user']);
          // echo $comment;
            }

            // 報告が0件のときの処理
            if (empty($reportAry)) {
                // ダミーデータの格納
                $reportAry[] = array('lat' => 39.80263880587395, 'lng' => 141.13756477832797, 'comment' => "※このデータは報告が0件のときに表示されるダミーデータです。", 'number' => 0);
                // Javascriptで扱えるようにJSON形式に変換
                $report = json_encode($reportAry);
            } else {
                // Javascriptで扱えるようにJSON形式に変換
                $report = json_encode($reportAry);
            }

    } catch (PDOException $e) {
     }


?>

<!DOCTYPE html>
<html>

<head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154120714-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-154120714-1');
</script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>市民参加型調査支援システム</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- 追加CSS -->
  <link rel="stylesheet" href="dist/css/mod.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- leaflet marker cluster css -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/skin-black.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    #map {
      height: 800px;
      margin: 2px;
    }
  </style>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
</head>


<body class="hold-transition skin-black sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">T</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">調査支援システム</span>
      </a>
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">　</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.php"><i class="fa fa-link"></i><span>トップ</span></a></li>
          <!-- <li><a href="mypage.php?user=<?php echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li> -->
          <!-- <li><a href="ranking.php"><i class="fa fa-pie-chart"></i><span>ランキング</span></a></li> -->
          <li><a href="about.html"><i class="fa fa-pie-chart"></i><span>このシステムについて</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!-- Main content -->
      <section class="content">



        <!-- <div class="row">
          <div class="col-xs-8">
            <img src="img/report/reportID652-2.jpg" class="img-responsive margin" alt="">
          </div>

          <div class="col-xs-4">
            2019/06/05
          </div>
        </div> -->

        <h3>
          地図統合閲覧
          <small>Research Result</small>
        </h3>

        <div class="box box-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-teal-active">
            <h3 class="widget-user-username">地図統合閲覧</h3>
            <!-- <a href="community.php?id=<?php echo $communityID; ?>" style="color: #FFF"><h5 class="widget-user-desc"><i class="fa fa-anchor margin-r-5"></i> <?php echo $communityName; ?></h5></a> -->
            <div class="pull-right" style="color: #000000">
              <!-- Buttons, labels, and many other things can be placed here! -->
              <!-- <button type="button" class="btn btn-block btn-danger btn-md" data-toggle="modal" data-target="#modal-default" <?php echo $settingDisplay; ?>>設定</button> -->

              <!-- Here is a label for example -->
            </div>
          </div>
          <!-- <div class="widget-user-image">
            <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
          </div> -->
          <div class="box-footer">
            <strong><i class="fa fa-book margin-r-5"></i> 概要</strong>
            <p class="text-muted">この地図は今まで調査を行なっている活動報告・状況報告・漂着物報告の全ての調査報告を一つのマップにまとめました。</p>

            
            <!-- <strong><i class="fa fa-search margin-r-5"></i> 対象タイプ</strong>
            <p class="text-muted">
              <?php
// if (strcmp($targetType, "multi")!=0) {
//     echo "単生";
// } else {
//     echo "群生";
// }
?>
            </p> -->
              </div>
        </div>

      

        <div class="box box-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          
          <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $allcount ?><sup style="font-size: 20px">件</sup></h3>
                <p>全調査数</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          
          <!-- ./col -->
          
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php echo $searchCount; ?><sup style="font-size: 20px">件</sup></h3>
                <p>全報告数</p>
              </div>
              <div class="icon">
                <i class="ion ion-search"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>
        

        <div class="row">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">報告地点一覧</h3>
              <div class="box-tools pull-right">
                <!-- Buttons, labels, and many other things can be placed here! -->
                <!-- Here is a label for example -->
                <!-- <span class="label label-primary">Label</span> -->
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div id='map'></div>
            </div>
            <!-- /.box-body -->
          </div>

          <!-- Left col -->
          <section class="col-lg-8 connectedSortable">
            <!-- AREA CHART -->
            <div class="box box-primary">
              
              <div class="box-body">
                <div class="chart">
                  <canvas id="chart1" style="height:0px"></canvas>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
           
            
            <!-- BAR CHART -->
            <!-- <div class="box box-primary">
              
              <div class="box-body">
                <div class="chart">
                  <canvas id="chart2" style="height:400px"></canvas>
                </div>
              </div>
              /.box-body
            </div> -->
            <!-- /.box -->


          </section>
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
		  
		
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.0
      </div>
      <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
      reserved.
    </footer>

    

  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Morris.js charts -->
  <script src="bower_components/raphael/raphael.min.js"></script>
  <script src="bower_components/morris.js/morris.min.js"></script>
  <!-- Sparkline -->
  <script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
  <!-- jvectormap -->
  <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="bower_components/moment/min/moment.min.js"></script>
  <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- Slimscroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- ChartJS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  <!-- leaflet script -->
  <script src="plugins/leaflet/leaflet.js" charset="utf-8"></script>
  <!-- leaflet heatmap script -->
  <script src="plugins/leaflet/leaflet-heat.js" charset="utf-8"></script>
  <!-- leaflet maeker cluster -->
  <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
  <!-- jquery-qrcode -->
  <script src="plugins/jquery-qrcode/jquery.qrcode.min.js" charset="utf-8"></script>
  <script>
    $('#qrcode').qrcode(location.href);

    // チャート生成
    var ctx = document.getElementById("chart1").getContext('2d');
    var chart1 = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [0, <?php echo $lavel; ?>],
        datasets: [{
          label: '報告数（累計）',
          data: [0, <?php echo $reportDataSum; ?>],
          backgroundColor: [
            'rgba(255, 159, 64, 1)'
          ],
          borderColor: [
            'rgba(255,99,132,1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - If there is a stroke on each bar
        barShowStroke: true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth: 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing: 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing: 1,
        //String - A legend template
        legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        //Boolean - whether to make the chart responsive
        responsive: true,
        maintainAspectRatio: true
      }
    });

   

    // マップ生成
    var map; //ベースマップ
    //地理院地図
    var gsi = L.tileLayer('https://cyberjapandata.gsi.go.jp/xyz/std/{z}/{x}/{y}.png', {
      attribution: "<a href='https://maps.gsi.go.jp/development/ichiran.html' target='_blank'>地理院タイル</a>"
    });
    //地理院地図の淡色地図タイル
    var gsipale = L.tileLayer('http://cyberjapandata.gsi.go.jp/xyz/pale/{z}/{x}/{y}.png', {
      attribution: "<a href='http://portal.cyberjapan.jp/help/termsofuse.html' target='_blank'>地理院タイル</a>"
    });
    //オープンストリートマップ
    var osm = L.tileLayer('http://tile.openstreetmap.jp/{z}/{x}/{y}.png', {
      attribution: "<a href='http://osm.org/copyright' target='_blank'>OpenStreetMap</a> contributors"
    });
    //ベースレイヤーの設定
    var baseLayers = {
      "OpenStreetMap": osm,
      "地理院地図": gsi,
      "淡色地図": gsipale
    };
    //オーバーレイレイヤーの初期化
    var overlays = {};

    //単配列の宣言
    var array = [];

    //マップ生成関数
    function mapGenerate(lat, lng) {
      map = L.map('map', {
        center: [36, 139],
        zoom: 10
      }).setView([lat, lng], 8);
      osm.addTo(map);
    }

    //マーカー生成関数
    function markerGenerate(ary) {
      var layer = L.markerClusterGroup();
      for (var i = 0; i < ary.length; i++) {
        L.marker([ary[i][0], ary[i][1]]).bindPopup(array[i][3]).addTo(layer);
      }
      return layer;
    }

    // function clusterGenerate(ary) {
    //   var layer = L.markerClusterGroup();
    //   markers.addLayer(L.marker());
    //   map.addLayer(markers);
    // }

    //ヒートマップ生成関数
    // function heatGenerate(ary) {
    //   // 配列の3列目を削除
    //   for (var i = 0; i < ary.length; i++) {
    //     ary[i].splice(3, 1);
    //   }
    //   var layer = L.layerGroup();
    //   L.heatLayer(ary, {
    //     radius: 30,
    //     maxOpacity: 0.5
    //   }).addTo(layer);
    //   return layer;
    // }

    //マーカーグループ生成関数
    function controlGenerate(name, markerArray) {
      overlays[name] = markerGenerate(markerArray);
    //   overlays[name + '_heatmap'] = heatGenerate(markerArray);
    }

    // PHPで取得したデータを格納
    var data = <?php echo $report; ?>;

    <?php
if (empty($researchYear)) {
    echo 'var year = "ALL";';
} else {
    echo 'var year = "ALL";';
}
?>

    // MAP用の配列生成
    for(var i in data){
      array[i] = [];
      array[i][0] = data[i].lat;
      array[i][1] = data[i].lng;
      array[i][2] = data[i].group_name;
      array[i][3] = data[i].comment;
    }

    // Map生成　必ず最初に実行
    mapGenerate(array[0][0], array[0][1]);
    // マーカー生成
    controlGenerate(year, array);
    // 初期表示設定
    overlays[year].addTo(map);
    //コントローラーの追加
    L.control.layers(baseLayers, overlays).addTo(map);
  </script>
</body>

</html>


