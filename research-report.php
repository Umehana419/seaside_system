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
$login = false;

// パラメータの確認
if (isset($_GET['id'])) {
    $researchID = $_GET['id'];

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
        $stmt = $pdo->prepare('SELECT *, research.name as researchName FROM research LEFT JOIN community on research.communityID = community.communityID WHERE researchID = ?');
        $stmt->execute(array($researchID));

        // 取得確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $communityID = $row['communityID'];
            $communityName = $row['name'];
            $researchName = $row['researchName'];
            $researchOverview = $row['researchOverview'];
            $start = $row['researchStart'];
            $end = $row['researchEnd'];
            $targetName = $row['targetName'];
            $targetOverview = $row['targetOverview'];
            $targetType = $row['targetType'];

            $stmt = $pdo->prepare('SELECT * FROM report LEFT JOIN user on report.userID = user.userID WHERE researchID = ?');
            $stmt->execute(array($researchID));

            $reportAry = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reportAry[] = array('id' => $row['reportID'], 'name' => $row['name'], 'lng' => $row['lng'], 'lat' => $row['lat'], 'date' => $row['date'], 'private' => $row['private']);
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
    $_SESSION['message'] = "この調査は存在しません。";
    header("Location: index.php");  // メイン画面へ遷移
    exit();
}

// ログイン確認
if (isset($_SESSION["ID"])) {
    // IDの格納
    $loginID = $_SESSION['ID'];
    $loginName = $_SESSION['name'];
    $login = true;

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
        $stmt = $pdo->prepare('SELECT * FROM community_member WHERE communityID = ? && userID = ?');
        $stmt->execute(array($communityID, $loginID));

        // 取得確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (strcmp($row['userRole'], "admin") == 0) {
                $settingDisplay = "";
            }
        }
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラーが発生しました。';
        $e->getMessage(); // エラー内容を参照可能（デバッグ時のみ表示）
        echo $e->getMessage();
    }
} else {
    $_SESSION['message'] = "検証を行うにはログインが必要です。";
    header("Location: login.php");  // メイン画面へ遷移
    exit();
}

// 保存ボタンが押されたら
if (isset($_POST['save'])) {
    $private = $_POST['private'];

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
        $stmt = $pdo->prepare('SELECT * FROM community_member WHERE communityID = ? && userID = ?');
        $stmt->execute(array($communityID, $loginID));

        // 取得確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (strcmp($row['userRole'], "admin") == 0) {
                $stmt = $pdo->prepare('UPDATE report SET private = (private * -1) WHERE reportID = ?');

                foreach ($private as $key => $value) {
                    $stmt->execute(array($value));
                }

                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $researchID);
                exit();
            } else {
                $_SESSION['message'] = "非公開設定は管理者のみ行うことが可能です。";
                header("Location: login.php");  // メイン画面へ遷移
                exit();
            }
        } else {
            $_SESSION['message'] = "検証を行うにはログインが必要です。";
            header("Location: login.php");  // メイン画面へ遷移
            exit();
        }
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラーが発生しました。';
        $e->getMessage(); // エラー内容を参照可能（デバッグ時のみ表示）
        echo $e->getMessage();
    }

    foreach ($private as $key => $value) {
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

        <h3>
          報告一覧
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
                <p class="text-muted"><?php echo $researchName; ?></p>

                <strong><i class="fa fa-anchor margin-r-5"></i> 調査コミュニティ</strong>
                <p class="text-muted"><?php echo $communityName; ?></p>

                <strong><i class="fa fa-book margin-r-5"></i> 概要</strong>
                <p class="text-muted"><?php echo $researchOverview; ?></p>

                <strong><i class="fa fa-map-marker margin-r-5"></i> 対象種</strong>
                <p class="text-muted"><?php echo $targetName; ?></p>

                <strong><i class="fa fa-map-marker margin-r-5"></i> 対象種概要</strong>
                <p class="text-muted"><?php echo $targetOverview; ?></p>

                <strong><i class="fa fa-search margin-r-5"></i> 対象種タイプ</strong>
                <p class="text-muted">
                  <?php
                  if (strcmp($targetType, "mono")) {
                      echo "単生";
                  } else {
                      echo "群生";
                  }
                  ?>
                </p>
                <strong><i class="fa fa-file-text-o margin-r-5"></i> 期間</strong>
                <p class="text-muted"><?php echo $start; ?>月 ～ <?php echo $end; ?>月</p>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">カテゴリ</h3>

                <div class="box-tools">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                  <li class="trigger active" data-filter="all"><a href="#"><i class="fa fa-inbox"></i> 全て
                  <li class="trigger" data-filter="disagree"><a href="#" style=""><i class="fa fa-check-square-o"></i> 未承認</a></li>
                  <li class="trigger" data-filter="agree"><a href="#" style=""><i class="fa fa-check-square"></i> 承認済み</a></li>
                  <li class="trigger" data-filter="private" style="display: <?php echo $settingDisplay; ?>"><a href="#" style=""><i class="fa fa-trash"></i> 非公開</a>
                  </li>
                </ul>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /. box -->

            <button type="button" name="button" class="btn btn-block btn-info" onclick="location.href='research-top.php?id=<?php echo $researchID; ?>'">調査トップに戻る</button>

          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <form class="" action="" method="post">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">報告一覧</h3>

                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div>
                  <table id="example2" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th>報告日</th>
                        <th>ステータス</th>
                        <th>報告者</th>
                        <th>場所</th>
                        <th>検証</th>
                        <th style="display: <?php echo $settingDisplay; ?>">非公開</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                            $stmt = $pdo->prepare('SELECT * FROM validation WHERE reportID = ?');
                            $validation = "";
                            $filter = "";

                            foreach ($reportAry as $key => $value) {
                                if (($value['private'] == 1) || (strcmp($settingDisplay, "none") != 0)) {
                                    $agree = 0;
                                    $disagree = 0;
                                    $filter = "";
                                    $private = "";
                                    $stmt->execute(array($value['id']));

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        if ($row['result'] == 1) {
                                            $agree++;
                                        } else {
                                            $disagree++;
                                        }
                                    }

                                    if ($value['private'] == -1) {
                                        $private = '<span class="label label-danger">非公開</span>';
                                        $filter .= "private ";
                                    }

                                    if ($agree > $disagree) {
                                        $validation = '<span class="label label-success">承認済み</span>';
                                        $filter .= "agree ";
                                    } else {
                                        $validation = '<span class="label label-warning">未承認</span>';
                                        $filter .= "disagree ";
                                    }

                                    if (isset($value['name'])) {
                                      $name = $value['name'];
                                    } else {
                                      $name = "ゲストユーザ";
                                    }
                                    echo <<<EOM
                                    <tr role="row" class="odd filter {$filter}">
                                      <td>{$value['date']}</td>
                                      <td>
                                        {$validation}
                                        {$private}
                                      </td>
                                      <td>{$name}</td>
                                      <td>{$value['lat']}, {$value['lng']}</td>
                                      <td>
                                        <button type="button" class="btn btn-info btn-block btn-md" onclick="location.href='research-report-detail.php?id={$researchID}&report={$value['id']}'">検証へ</button>
                                      </td>
                                      <td style="display: {$settingDisplay}">
                                        <div class="text-center">
                                          <input type="checkbox" name="private[]" value="{$value['id']}">
                                        </div>
                                      </td>
                                    </tr>
EOM;
                                }
                            }
                            ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /. box -->
              <button type="submit" name="save" class="btn btn-danger btn-lg btn-block margin-bottom" style="display: <?php echo $settingDisplay; ?>">変更を保存</button>
            </form>
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
  <!-- SlimScroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!-- DataTables -->
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- ChartJS -->
  <script src="bower_components/chart.js/Chart.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>
  <script>
    $(document).ready(function() {
      $('.trigger').click(function() {
        var value = $(this).attr('data-filter');
        if (value == 'all') {
          $('.filter').show('1000');
        } else {
          $('.filter').not('.' + value).hide('1000');
          $('.filter').filter('.' + value).show('1000');
        }
        $(document).on('click', '.trigger', function() {
          $('.trigger').removeClass('active');
          $(this).addClass('active');
        });
      })
    })

    $(function() {
      //Enable iCheck plugin for checkboxes
      //iCheck for checkbox and radio inputs
      $('.mailbox-messages input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
      });

      $('input').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_square-red',
      });

      $.extend($.fn.dataTable.defaults, {
        language: {
          url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
        }
      });

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
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>

</html>
