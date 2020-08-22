<?php
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名

// ユーザ名の初期化
$loginName = "ゲストユーザ";
// エラーメッセージの初期化
$errorMessage = "";
// メッセージの確認と初期化
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $_SESSION['message'] = "";
}
$login = false;

$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

try {
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

    // 調査情報テーブルにコミュニティ情報結合
    $stmt = $pdo->prepare('SELECT *, community.name AS communityName, research.name AS researchName FROM research LEFT JOIN community ON research.communityID = community.communityID WHERE private = 1 ORDER BY researchID');
    $stmt->execute();

    $researchName = array();
    $month = (int)date("m");
    $research = array(); // 調査情報入れる変数
    $researchMapDate = array();
    $researchCount = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($researchName, $row['name']);
        $research[] = array('researchName' => $row['researchName'], 'communityName' => $row['communityName'], 'researchID' => $row['researchID'], 'researchStart' => $row['researchStart'], 'researchEnd' => $row['researchEnd'], 'researchOverview' => $row['researchOverview']);
        $researchCount++;
    }

    // 調査情報テーブルにコミュニティ情報結合
    $stmt = $pdo->prepare('SELECT * FROM community');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $latlng = explode(',', $row['point']);
        $comment = $row['overview'];
        $link = '<a href="community.php?id='. $row['communityID'] .'">コミュニティ情報を確認</a>';
        $communityMapDate[] = array('communityName' => $row['name'], 'lat' => $latlng[0], 'lng' => $latlng[1], 'comment' => $comment, 'link' => $link);
        // echo $comment;
    }

    // Javascriptで扱えるようにJSON形式にエンコード
    $researchMapDate = json_encode($communityMapDate);

    // 報告数カウント
    $stmt = $pdo->prepare('SELECT COUNT(reportID) FROM report');
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $reportCount = $row["COUNT(reportID)"];

    $validationCount = 0;

    // 報告数カウント
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM validation');
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $validationCount = $row["COUNT(*)"];
} catch (PDOException $e) {
    $errorMessage = 'データベースエラーが発生しました。';
    // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
    // echo $e->getMessage();
}

// ログイン状態チェック
if (isset($_SESSION["ID"])) {
    // IDの格納
    $loginID = $_SESSION['ID'];
    $loginName = $_SESSION['name'];
    $login = true;

    // 認証
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラーが発生しました。';
        // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
        // echo $e->getMessage();
    }
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
  <title>市民参加型調査支援システム | トップ</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/skin-black.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!--leaflet's stylesheet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

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
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo htmlspecialchars($loginName, ENT_QUOTES); ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                  <p>
                    <?php echo htmlspecialchars($loginName, ENT_QUOTES); ?>
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                  <div class="row">
                    <div class="col-xs-12 text-center">
                      <a href="mypage.php?user=<?php echo $loginID; ?>">マイページ</a>
                    </div>
                  </div>
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <?php
                    if ($login) {
                        echo '<a href="logout.php" class="btn btn-default btn-flat">ログアウト</a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-default btn-flat">ログイン</a>';
                    }
                    ?>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo htmlspecialchars($loginName, ENT_QUOTES); ?></p>
            <!-- Status -->
            <a href="#">アカウントレベル0</a>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">　</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.php"><i class="fa fa-link"></i><span>トップ</span></a></li>
          <li><a href="mypage.php?user=<?php echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li>
          <li><a href="ranking.php"><i class="fa fa-pie-chart"></i><span>ランキング</span></a></li>
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
        <?php
        if (!empty($errorMessage) || !empty($message)) {
            $errorMessage = htmlspecialchars($errorMessage, ENT_QUOTES);
            $message = htmlspecialchars($message, ENT_QUOTES);
            echo <<<EOM
          <div class="callout callout-warning">
          <h4>ERROR</h4>
          <p>$errorMessage</p>
          <p>$message</p>
          </div>
EOM;
        }
        ?>
        <!-- Info boxes -->
        <h3>
          お知らせ
          <small>News</small>
        </h3>
        <div class="row">
          <div class="col-md-12">
            <?php
            $i = 0;
            foreach ($researchName as $key => $value) {
                // code...
                echo '<ol class="breadcrumb">';
                echo '<li><a href="#"><i class="fa fa-dashboard"></i> New!</a></li>';
                echo '<li class="active">新規コミュニティ「'.$value.'」が作成されました！</li>';
                echo '</ol>';
                $i++;
                if ($i == 3) {
                    break;
                }
            }
            ?>
          </div>
        </div>


        <h3>
          調査一覧
          <small>Research List</small>
        </h3>
              <p>システムに登録された調査の一覧です。「調査ページへ」をクリックすると現在の集計状況を確認できます。ステータスが調査中となっている調査は誰でも参加することができます。調査ページにある「報告を行う」ボタンをクリックして、ぜひご参加ください。</p>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">

              
                <!-- <div class="box-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                    <div class="input-group-btn">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div> -->
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <table id="example2" class="table table-bordered table-hover dataTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>調査期間</th>
                      <th>ステータス</th>
                      <th>調査名・調査リンク</th>
                      <th>コミュニティ名</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      foreach ($research as $key => $val) {
                          $month = (int)date("m");
                          if ($val['researchStart'] <= $val['researchEnd']) {
                              // code...
                              if (($val['researchStart'] <= $month) && ($month <= $val['researchEnd'])) {
                                  $status = "調査中";
                                  $statusCollor = "success";
                              } else {
                                  $status = "調査期間外";
                                  $statusCollor = "danger";
                              }
                          } else {
                              if (($val['researchStart'] <= $month) && ($month >= $val['researchEnd'])) {
                                  $status = "調査中";
                                  $statusCollor = "success";
                              } else {
                                  $status = "調査期間外";
                                  $statusCollor = "danger";
                              }
                          }

                          echo <<<EOM
                          <tr role="row">
                            <td>{$val['researchID']}</td>
                            <td>{$val['researchStart']} ~ {$val['researchEnd']}</td>
                            <td><span class="label label-{$statusCollor}">{$status}</span></td>
                            <td>{$val['researchName']}<br>
                            <button type="button" class="btn btn-info btn-flat" onclick="location.href='research-top.php?id={$val['researchID']}'">調査ページへ</button></td>
                            <td>{$val['communityName']}</td>
                          </tr>
EOM;
                      }
                      ?>
                      <!-- <td>183</td>
                      <td>John Doe</td>
                      <td>11-7-2014</td>
                      <td><span class="label label-success">調査期間中</span></td>
                      <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                      <td><button href="reserch-top.php" type="button" class="btn btn-info btn-flat">Go!</button></td> -->
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>



 

        <h3>
          コミュニティ一覧
          <small>Community List</small>
        </h3>
 <p>現在システムに登録中のコミュニティ一覧です。コミュニティは複数の調査を設定可能です。ユーザー登録することで誰でもコミュニティを作成できます。コミュニティの詳細は「コミュニティ情報を確認」をクリックしてご確認ください。</p>


        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                          
                <!-- <div class="box-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                    <div class="input-group-btn">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div> -->
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <table id="example" class="table table-bordered table-hover dataTable">
                  <thead>
                    <tr>
                      <th>コミュニティ名</th>
                      <th>コミュニティ概要</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      foreach ($communityMapDate as $key => $val) {
                          echo <<<EOM
                          <tr role="row">
                            <td>{$val['communityName']}</td>
                            <td>{$val['comment']}
                             [{$val['link']}]</td>
                          </tr>
EOM;
                      }
                      ?>
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">コミュニティーの所在地マップ</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-9">
                    <div id="map" style="width: 100%; height: 500px"></div>
                  </div>
                  <div class="col-md-3">
                    <div class="row">
                      <div class="pad box-pane-right bg-green" style="min-height: 500px">
                        <div class="small-box">
                          <div class="inner">
                            <h3><?php echo $researchCount; ?><sup style="font-size: 20px">件</sup></h3>
                            <p>全調査数</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                        </div>
                        <hr>
                        <div class="small-box">
                          <div class="inner">
                            <h3><?php echo $reportCount ?><sup style="font-size: 20px">件</sup></h3>
                            <p>全報告数</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-search"></i>
                          </div>
                        </div>
                        <hr>
                        <div class="small-box">
                          <div class="inner">
                            <h3><?php echo $validationCount ?><sup style="font-size: 20px">件</sup></h3>
                            <p>全検証数</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                        </div>
                        <hr>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">

              </div>
              <!-- /.box-footer-->
            </div>
          </div>
        </div>

        
           </div>
        
              </section>
      <!-- /.content -->
        
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
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- Sparkline -->
  <script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
  <!-- jvectormap  -->
  <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- SlimScroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- ChartJS -->
  <script src="bower_components/chart.js/Chart.js"></script>
  <!-- DataTables -->
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <script>
    $(function() {
      $('#example2').DataTable({
        order: [
          [0, "desc"]
        ],
        // 件数切替の値を10～50の10刻みにする
        lengthMenu: [10, 20, 30, 40, 50],
        // 件数のデフォルトの値を50にする
        displayLength: 10,
        scrollX: true,
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false
      })

      $('#example').DataTable({
        order: [
          [0, "desc"]
        ],
        // 件数切替の値を10～50の10刻みにする
        lengthMenu: [10, 20, 30, 40, 50],
        // 件数のデフォルトの値を50にする
        displayLength: 10,
        scrollX: true,
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false
      })
    });

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
      }).setView([lat, lng], 4);
      osm.addTo(map);
    }

    //マーカー生成関数
    function markerGenerate(ary) {
      var layer = L.layerGroup();
      for (var i = 0; i < ary.length; i++) {
        L.marker([ary[i][0], ary[i][1]]).bindPopup(array[i][3]).addTo(layer);
      }
      return layer;
    }

    //ヒートマップ生成関数
    function heatGenerate(ary) {
      var layer = L.layerGroup();
      L.heatLayer(ary, {
        radius: 30
      }).addTo(layer);
      return layer;
    }

    //マーカーグループ生成関数
    function controlGenerate(name, markerArray) {
      overlays[name] = markerGenerate(markerArray);
      // overlays[name + '_heatmap'] = heatGenerate(markerArray);
    }

    var data = <?php echo $researchMapDate; ?>;

    // MAP用の配列生成
    for(var i in data){
      array[i] = [];
      array[i][0] = data[i].lat;
      array[i][1] = data[i].lng;
      array[i][2] = 0;
      array[i][3] = "<strong>" + data[i].communityName + "</strong><p>" + data[i].comment + "</p>";
    }

    //Map生成　必ず最初に実行
    mapGenerate(35, 135);
    controlGenerate("2015", array);
    //初期表示設定
    overlays["2015"].addTo(map);
  </script>
</body>

</html>
