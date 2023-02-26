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
// 管理者設定の表示設定
$settingDisplay = "none";
// 検証ボタンの表示設定
$validationDisplay = "";
$login = false;

// パラメータの確認
if (isset($_GET['id'])) {
    if (isset($_GET['report'])) {
        $activityID = $_GET['id'];
        $activ_reID = $_GET['report'];

        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            // 調査情報の取得
            $stmt = $pdo->prepare('SELECT * FROM activity WHERE activityID = ?');
            $stmt->execute(array($activityID));

            // 取得確認
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // $communityID = $row['communityID'];
                // $communityName = $row['name'];
                $activ_name = $row['activ_name'];
                $activ_details = $row['activ_details'];
                $start = $row['researchStart'];
                $end = $row['researchEnd'];
                $activ_target = $row['activ_target'];
                $activ_overview = $row['activ_overview'];
                // $targetType = $row['targetType'];

                // 報告情報の取得
                $stmt = $pdo->prepare('SELECT * FROM activ_report LEFT JOIN activ_media on activ_report.activ_reID = activ_media.activ_reID WHERE activityID = ? && activ_report.activ_reID = ?');
                $stmt->execute(array($activityID, $activ_reID));

                $report = array();
                $question = array();
                $answer = array();
                $media = array();

                // 報告確認
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $report = array(
                    //   'id' => $row['activ_reID'],
                      'activ_name' => $row['activ_name'],
                      'lng' => $row['lng'],
                      'lat' => $row['lat'],
                      'date' => $row['date'],
                    //   'private' => $row['private'],
                    'people_num' => $row['people_num'],
                    'comment' => $row['comment'],
                      'group_name' => $row['group_name'],
                      'user_people' => $row['user_people']);

                    // 調査項目の取得
                    // $stmt = $pdo->prepare('SELECT * FROM research_question WHERE researchID = ?');
                    // $stmt->execute(array($researchID));

                    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    //     array_push($question, $row['question']);
                    // }

                    // 調査結果の取得
                    // $stmt = $pdo->prepare('SELECT * FROM report_answer WHERE reportID = ?');
                    // $stmt->execute(array($report['id']));

                    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    //     array_push($answer, $row['answer']);
                    // }

                    $stmt = $pdo->prepare('SELECT * FROM activ_media WHERE activ_reID = ?');
                    $stmt->execute(array($activ_reID));

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                       $media_be1 = $row['media_be1'];
                       $media_be2 = $row['media_be2'];
                       $media_be3 = $row['media_be3'];
                       $media_now1 = $row['media_now1'];
                       $media_now2 = $row['media_now2'];
                       $media_now3 = $row['media_now3'];
                       $media_af1 = $row['media_af1'];
                       $media_af2 = $row['media_af2'];
                       $media_af3 = $row['media_af3'];
                    }

                    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    //
                    // // 調査項目がなくなるまで
                    // while (isset($row['question' . "$i"])) {
                    //     array_push($question, $row['question' . "$i"]);
                    //     $i++;
                    // }

                    // 検証情報の取得
                    // $stmt = $pdo->prepare('SELECT * FROM validation WHERE reportID = ?');
                    // $stmt->execute(array($reportID));

                    // $agree = 1;
                    // $disagree = 1;

                    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    //     if ($row['result'] == 1) {
                    //         $agree++;
                    //     } else {
                    //         $disagree++;
                    //     }
                    // }
                } else {
                    $_SESSION['message'] = "この報告は存在しません。";
                    header("Location: index.php");  // メイン画面へ遷移
                    exit();
                }
            } else {
                $_SESSION['message'] = "この調査は存在しません。";
                header("Location: index.php");  // メイン画面へ遷移
                exit();
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラーが発生しました。';
            $e->getMessage(); // エラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "この報告は存在しません。";
        header("Location: index.php");  // メイン画面へ遷移
        exit();
    }
} else {
    $_SESSION['message'] = "この調査は存在しません。";
    header("Location: index.php");  // メイン画面へ遷移
    exit();
}

// ログイン確認
// if (isset($_SESSION["ID"])) {
//     // IDの格納
//     $loginID = $_SESSION['ID'];
//     $loginName = $_SESSION['name'];
//     $login = true;

//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // ユーザ情報の取得
//         $stmt = $pdo->prepare('SELECT * FROM community_member WHERE communityID = ? && userID = ?');
//         $stmt->execute(array($communityID, $loginID));

//         // 取得確認
//         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             if (strcmp($row['userRole'], "admin") == 0) {
//                 $settingDisplay = "";
//             }
//         }
//         // 検証情報の取得
//         $stmt = $pdo->prepare('SELECT * FROM validation WHERE reportID = ? && userID = ?');
//         $stmt->execute(array($reportID, $loginID));

//         // 検証済みであれば検証ボタンを無効化
//         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $validationDisplay = "disabled";
//         }
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラーが発生しました。';
//         $e->getMessage(); // エラー内容を参照可能（デバッグ時のみ表示）
//         echo $e->getMessage();
//     }
// } else {
//     $_SESSION['message'] = "検証を行うにはログインが必要です。";
//     header("Location: login.php");  // メイン画面へ遷移
//     exit();
// }

// // 賛成ボタンが押されたときの処理
// if (isset($_POST['agree'])) {
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // 検証譲歩の取得
//         $stmt = $pdo->prepare('SELECT * FROM validation WHERE reportID = ? && userID = ?');
//         $stmt->execute(array($reportID, $loginID));

//         // 検証済みか確認（省略可能？）
//         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $_SESSION['message'] = "すでに検証を行っています。";
//             header("Location: login.php");  // メイン画面へ遷移
//             exit();
//         } else {
//             // 結果をインサート
//             $stmt = $pdo->prepare('INSERT INTO validation(reportID, userID, result) VALUES (?, ?, ?)');
//             $stmt->execute(array($reportID, $loginID, 1));
//             header("Location: research-report.php?id=" . $researchID); // 報告一覧に戻る
//             exit();
//         }
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラーが発生しました。';
//         // $e->getMessage(); // エラー内容を参照可能（デバッグ時のみ表示）
//     // echo $e->getMessage();
//     }
// }

// // 反対ボタンが押されたときの処理
// if (isset($_POST['disagree'])) {
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         $stmt = $pdo->prepare('SELECT * FROM validation WHERE reportID = ? && userID = ?');
//         $stmt->execute(array($reportID, $loginID));

//         // 検証済みか確認（省略可能？）
//         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $_SESSION['message'] = "すでに検証を行っています。";
//             header("Location: login.php");  // メイン画面へ遷移
//             exit();
//         } else {
//             // 検証結果をインサート
//             $stmt = $pdo->prepare('INSERT INTO validation(reportID, userID, result) VALUES (?, ?, ?)');
//             $stmt->execute(array($reportID, $loginID, 0));
//             header("Location: research-report.php?id=" . $researchID); // 報告一覧に戻る
//             exit();
//         }
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラーが発生しました。';
//         // $e->getMessage(); // エラー内容を参照可能（デバッグ時のみ表示）
//     // echo $e->getMessage();
//     }
// }
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
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- Leaflet style -->
  <style>
    #map {
      width: 100%;
      height: 600px;
      margin: 10px;
    }
  </style>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
  <!-- leaflet root machine -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <!-- <link rel="stylesheet" href="plugins/leaflet/leaflet.css"> -->
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

        <h3>
          報告を確認する
          <small>Report List</small>
        </h3>

        <div class="row">
          <div class="col-md-3">

            <div class="box box-primary box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">調査概要</h3>

                <div class="box-tools">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <strong><i class="fa fa-search margin-r-5"></i> 調査名</strong>
                <p class="text-muted"><?php echo $activ_name; ?></p>

            
                <strong><i class="fa fa-book margin-r-5"></i> 概要</strong>
                <p class="text-muted"><?php echo $activ_details; ?></p>

                <strong><i class="fa fa-map-marker margin-r-5"></i> 対象名</strong>
                <p class="text-muted"><?php echo $activ_target; ?></p>

                <strong><i class="fa fa-map-marker margin-r-5"></i> 対象概要</strong>
                <p class="text-muted"><?php echo $activ_overview; ?></p>

                <strong><i class="fa fa-search margin-r-5"></i> 調査タイプ</strong>
                <p class="text-muted">
                  <?php
                 
                        echo "通年";
                    
                        
                
                    ?>
                </p>
                <strong><i class="fa fa-file-text-o margin-r-5"></i> 期間</strong>
                <p class="text-muted"><?php echo $start; ?>月 ～ <?php echo $end; ?>月</p>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

           
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">報告詳細</h3>

                <div class="box-tools pull-right">
                  <!-- <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Previous"><i class="fa fa-chevron-left"></i></a>
                  <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Next"><i class="fa fa-chevron-right"></i></a> -->
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <div class="mailbox-read-info">
                  <h5><a href="activ_research-top.php?id=<?php echo $activityID; ?>"><i class="fa fa-angle-left margin-r-5"></i> 報告一覧に戻る</a>
                    <!-- <span class="mailbox-read-time pull-right">報告日：2019年8月8日</span> -->
                  </h5>
                </div>
                <!-- /.mailbox-read-info -->
                <div class="mailbox-controls with-border text-center">
                  <!-- <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Reply"
                      onclick="location.href='research-report-detail.php?id=<?php echo $researchID; ?>&report=<?php echo $reportID-1; ?>'">
                      <i class="fa fa-reply"></i></button>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Forward"
                      onclick="location.href='research-report-detail.php?id=<?php echo $researchID; ?>&report=<?php echo $reportID+1; ?>'">
                      <i class="fa fa-share"></i></button>
                  </div> -->
                  <!-- <div class="margin">
                    <button type="button" class="btn btn-primary btn-block" onclick="rooting()">現在地からのルートを表示</button>
                  </div> -->
                  <!-- /.mailbox-controls -->
                  <div class="mailbox-read-message">

                    <!-- <div id='map'></div> -->

                    <hr>

                    <div class="row">
                      <div class="col-sm-3 col-xs-6">
                        <strong><i class="fa fa-book margin-r-5"></i> 活動団体名</strong>
                        <p class="text-muted"><?php echo $report['group_name']; ?></p>
                      </div>

                      <div class="col-sm-3 col-xs-6">
                        <strong><i class="fa fa-book margin-r-5"></i> 活動日</strong>
                        <p class="text-muted"><?php echo $report['date']; ?></p>
                      </div>

                      <div class="col-sm-3 col-xs-6">
                        <strong><i class="fa fa-book margin-r-5"></i> 参加人数</strong>
                        <p class="text-muted"><?php echo $report['people_num']; ?>人</p>
                      </div>

                      <div class="col-sm-3 col-xs-6">
                        <strong><i class="fa fa-book margin-r-5"></i> コメント</strong>
                        <p class="text-muted"><?php echo $report['comment']; ?></p>
                      </div>

                      <div class="col-sm-3 col-xs-6">
                        <strong><i class="fa fa-book margin-r-5"></i> 報告者</strong>
                        <p class="text-muted"><?php echo $report['user_people']; ?></p>
                      </div>
                    
                    </div>

                  </div>
                  <!-- /.mailbox-read-message -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
                <!-- /.box-footer -->
                <div class="box-footer">
                  <!-- <div style="display: <?php echo $settingDisplay; ?>">
                    <form class="" action="" method="post">
                      <button type="submit" name="delete" class="btn btn-default"><i class="fa fa-trash-o"></i> この報告を削除する</button>
                    </form>
                  </div> -->
                </div>
                <!-- /.box-footer -->
              </div>
            </div>

            <div class="box box-solid bg-green no-padding">
              <div class="box-header with-border">
                <h3 class="box-title">報告詳細</h3>

                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="row margin">
                  <div class="col-md-9">
                    <h4>報告画像</h4>
                    
                    <p>活動前画像</p><br>
                     <img src="<?php echo $media_be1;?>" width="200" height="200"
                     alt="title" onerror="this.style.display='none'"/>
                     <img src="<?php echo $media_be2;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                     <img src="<?php echo $media_be3;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                 <br><br>
                 <p>活動中画像</p><br>
                     <img src="<?php echo $media_now1;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                     <img src="<?php echo $media_now2;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                     <img src="<?php echo $media_now3;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                 <br><br>
                 <p>活動後画像</p><br>
                     <img src="<?php echo $media_af1;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                     <img src="<?php echo $media_af2;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                     <img src="<?php echo $media_af3;?>" width="200" height="200"　
                     alt="title" onerror="this.style.display='none'"/>
                 <br>
                 
                  </div>

                 
                </div>
              </div>
              <!-- /.box-body -->
            </div>
          </div>
        </div>

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
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!-- DataTables -->
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- ChartJS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>
  <!-- leaflet script -->
  <script src="plugins/leaflet/leaflet.js" charset="utf-8"></script>
  <!-- leaflet heatmap script -->
  <script src="plugins/leaflet/leaflet-heat.js" charset="utf-8"></script>
  <!-- leaflet root machine script -->
  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <script>
   // var ctx = document.getElementById("myChart").getContext('2d');
    

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


    //for文で要素を格納する
    for (var i = 0; i < 1; i++) {
      //配列の要素数を指定する
      array[i] = [];
      array[i][0] = <?php echo $report['lat']; ?> ;
      array[i][1] = <?php echo $report['lng']; ?> ;
      array[i][2] = <?php echo $report['group_name']; ?> ;
      array[i][3] = <?php echo $report['date']; ?>;
    }

    //Map生成　必ず最初に実行
    mapGenerate(array[0][0], array[0][1]);
    controlGenerate("2015", array);

    //初期表示設定
    overlays["2015"].addTo(map);

    // 位置情報取得用JS
    function rooting() {
      navigator.geolocation.getCurrentPosition(getPosition);
    }

    function getPosition(position) {
      var lng = position.coords.longitude;
      var lat = position.coords.latitude;

      L.Routing.control({
        waypoints: [
          L.latLng(lat, lng),
          L.latLng(array[0][0], array[0][1])
        ]
      }).addTo(map);
    }
  </script>
</body>

</html>
