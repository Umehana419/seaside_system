<?php
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名

// ユーザ名の初期化
$userName = "ゲストユーザ";
// エラーメッセージの初期化
$errorMessage = "";
// 設定表示変数
$settingDisplay = "none";
$login = false;

// ログイン状態チェック
if (isset($_SESSION["ID"])) {

    // IDの格納
    $loginID = $_SESSION['ID'];
    $loginName = $_SESSION['name'];
    $login = true;
}

// パラメータのチェック
if (isset($_GET['user'])) {
    $id = $_GET['user']; // パラーメタIDの格納
    if ($loginID == $id) {
        $settingDisplay = "";
    }

    // 認証
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // ユーザ情報の取得
        $stmt = $pdo->prepare('SELECT * FROM user WHERE userID = ?');
        $stmt->execute(array($id));

        // ユーザの存在確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userName = $row['name'];
            $address = $row['address'];

            // コミュニティ所属状況の取得
            $stmt = $pdo->prepare('SELECT * FROM community_member LEFT JOIN community ON community_member.communityID = community.communityID WHERE userID = ?');
            $stmt->execute(array($id));

            $community = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $community[] = array('id' => $row['communityID'], 'name' => $row['name']);
            }

            // 報告した調査一覧を取得
            $stmt = $pdo->prepare('SELECT * FROM report INNER JOIN research ON report.researchID = research.researchID WHERE report.userID = ?');
            $stmt->execute(array($id));
            echo a;

            $research = array(); // 調査情報入れる変数
            $research_count = 0; // 調査数

            // 調査情報格納
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $research[] = array('researchID' => $row['researchID'], 'researchStart' => $row['researchStart'], 'researchEnd' => $row['researchEnd'], 'researchName' => $row['name']);
                $research_count++;
            }

            $reportCount = 0;
            $validationCount;
            $badgeAry = array();

            // 報告数取得
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM report WHERE userID = ?');
            $stmt->execute(array($id));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $reportCount = $row["COUNT(*)"];

            // 検証数取得
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM validation WHERE userID = ?');
            $stmt->execute(array($id));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $validationCount = $row["COUNT(*)"];

            // バッジ情報取得
            $stmt = $pdo->prepare('SELECT * FROM badge WHERE userID = ?');
            $stmt->execute(array($id));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $badgeAry = $row;
        } else {
            $_SESSION['message'] = "このユーザは存在しません。";
            header("Location: index.php");  // メイン画面へ遷移
            exit();
        }
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラーが発生しました。';
        // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
        // echo $e->getMessage();
    }
} else {
    $_SESSION['message'] = "このユーザは存在しません。";
    header("Location: index.php");  // メイン画面へ遷移
    exit();
}

// 設定更新ボタンが押されたとき
if (isset($_POST['update'])) {
    $updateName = $_POST['name'];

    // 認証
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    // エラー処理
    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // コミュニティテーブルの情報を更新
        $stmt = $pdo->prepare("UPDATE user set name = ? WHERE userID = ?");
        $stmt->execute(array($updateName, $loginID));

        // 更新処理
        header("Location: logout.php");
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラー';
        // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
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

  <!--leaflet's stylesheet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
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
          <li><a href="index.php"><i class="fa fa-link"></i><span>トップ</span></a></li>
          <li class="active"><a href="mypage.php?user=<?php echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li>
          <li><a href="runkingu.php"><i class="fa fa-pie-chart"></i><span>ランキング</span></a></li>
          <li><a href="about.html"><i class="fa fa-pie-chart"></i><span>このシステムについて</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <h3>
          プロフィール
          <small>User Profile</small>
        </h3>
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
              <div class="box-body box-profile">
                <h3 class="profile-username text-center"><?php echo $userName; ?></h3>
                <!-- <p class="text-muted text-center"><a href="community.php?id=<?php echo $communityID; ?>"><?php echo htmlspecialchars($communityName, ENT_QUOTES)?> </a> -->
                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>報告数</b><a class="pull-right"><?php echo $reportCount; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>検証数</b><a class="pull-right"><?php echo $validationCount; ?></a>
                  </li>
                </ul>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">自己紹介</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <strong><i class="fa fa-cubes margin-r-5"></i> 参加しているコミュニティ</strong>
                <p class="text-muted">
                <?php
                foreach ($community as $key => $value) {
                  echo '<a href="community.php?id=' . $value['id'] . '"> [' . $value['name'] . '] </a>';
                }
                ?>
                </p>
                <!-- <strong><i class="fa fa-map-marker margin-r-5"></i> 主な活動地点</strong>
                <p class="text-muted"><?php echo $address; ?></p>
                <hr>
                <strong><i class="fa fa-file-text-o margin-r-5"></i> その他</strong>
                <p></p> -->
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <button type="button" name="button" class="btn btn-info btn-lg btn-block" onclick="location.href='newcommunity.php'">新規コミュニティ作成へ</button>
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">アクティビティ</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" style="" aria-expanded="false">バッジ</a></li>
                <li class="" style="display: <?php echo $settingDisplay; ?>"><a href="#settings" data-toggle="tab" style="" aria-expanded="false">設定変更</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="activity">

                  <strong><i class="fa fa-search margin-r-5"></i> 参加している調査一覧</strong>
                  <div class="box-body table-responsive no-padding">
                    <table id="example2" class="table table-bordered table-hover dataTable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>調査期間</th>
                          <th>ステータス</th>
                          <th>調査名</th>
                          <th>調査リンク</th>
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
                            <tr>
                              <td>{$val['researchID']}</td>
                              <td>{$val['researchStart']} ~ {$val['researchEnd']}</td>
                              <td><span class="label label-{$statusCollor}">{$status}</span></td>
                              <td>{$val['researchName']}</td>
                              <td><button type="button" class="btn btn-info btn-flat" onclick="location.href='research-top.php?id={$val['researchID']}'">Go!</button></td>
                            </tr>
EOM;
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                  <strong><i class="fa fa-trophy margin-r-5"></i> 獲得称号一覧</strong>
                  <p>※取得済みの称号が表示されない場合は再度ログインを行ってください。</p>
                  <div class="row margin">
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge1'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/1.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>READY</h4>
                        <p class="text-muted">
                          アカウントを作成する
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge2'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/2.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>ECOSYSTEM</h4>
                        <p class="text-muted">
                        　コミュニティに所属する
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge3'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/3.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>WITNESS</h4>
                        <p class="text-muted">
                          調査報告を1回行う
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge4'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/4.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>POINT MAN</h4>
                        <p class="text-muted">
                          調査報告を10回行う
                        </p>
                      </div>
                    </div>
                  </div>

                  <div class="row margin">
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge5'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/5.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>PATHFINEDER</h4>
                        <p class="text-muted">
                          調査報告を30回行う
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge6'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/6.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>TRAILBLAZER</h4>
                        <p class="text-muted">
                          調査報告を100回行う
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge7'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/7.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>MAYBE</h4>
                        <p class="text-muted">
                          検証を1回行う
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge8'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/8.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>ADVISOR</h4>
                        <p class="text-muted">
                          検証を10回行う
                        </p>
                      </div>
                    </div>
                  </div>

                  <div class="row margin">
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge9'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/9.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>REFREE</h4>
                        <p class="text-muted">
                          検証を30回行う
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                      <?php
                      if ($badgeAry['badge10'] == 1) {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/10.svg" alt="User profile picture">';
                      } else {
                        echo '<img class="img-responsive img-circle" src="dist/img/badge/0.svg" alt="User profile picture">';
                      }
                      ?>
                      <div class="text-center">
                        <h4>ENCYCLOPEDIA</h4>
                        <p class="text-muted">
                          検証を100回行う
                        </p>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="settings" style="display: <?php echo $settingDisplay; ?>">
                  <p>アカウント名を変更した場合、自動的にログアウトされるため、再度ログインをお願いします。</p>
                  <form class="form-horizontal" action="" method="POST">
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 control-label">名前</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" value="<?php echo $userName; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger" name="update">保存</button>
                      </div>
                    </div>
                  </form>
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
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
  <!-- DataTables -->
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- ChartJS -->
  <script src="bower_components/chart.js/Chart.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <script type="text/javascript">
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
  });
  </script>
</body>

</html>
