<?php
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031o120";  // ユーザー名
$db['pass'] = "g031o120PW";  // ユーザー名のパスワード
$db['dbname'] = "g031o120";  // データベース名

// ユーザ名の初期化
$loginName = "ゲストユーザ";
// エラーメッセージの初期化
$errorMessage = "";
$login = false;

// メッセージの確認と初期化
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $_SESSION['message'] = "";
}

// パラメータの確認
if (isset($_GET['id'])) {
    $researchID = $_GET['id'];

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
        $stmt = $pdo->prepare('SELECT * FROM research WHERE researchID = ?');
        $stmt->execute(array($researchID));

        // 取得確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $researchName = $row['name'];
            $researchOverview = $row['researchOverview'];
            $start = $row['researchStart'];
            $end = $row['researchEnd'];
            $targetName = $row['targetName'];
            $targetPicture = $row['targetImageURL'];
            $targetOverview = $row['targetOverview'];
            $targetType = $row['targetType'];
        } else {
            $_SESSION['message'] = "この調査は存在しません。";
            header("Location: index.php");  // メイン画面へ遷移
            exit();
        }
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラーが発生しました。';
        // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
        // echo $e->getMessage();
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
} else {
    $loginID = 0;
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>いきもの調査</title>
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
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- mod style -->
  <link rel="stylesheet" href="dist/css/mod.css">
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
        <span class="logo-lg">いきもの調査</span>
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
          <div class="callout callout-success">
            <h4>報告成功</h4>
            <p>$errorMessage</p>
            <p>$message</p>
          </div>
EOM;
        }
        ?>

        <h3>
          調査
          <small>Search</small>
        </h3>

        <div class="row">
          <div class="col-md-3">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">調査について</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <strong><i class="fa fa-search margin-r-5"></i> 調査名</strong>
                <p class="text-muted"><?php echo $researchName; ?></p>

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

            <div class="box box-solid box-success">
              <div class="box-header with-border">
                <h3 class="box-title">調査を行う</h3>
                <div class="box-tools pull-right">
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <p>　「発見報告を行う」ボタンから対象生物の発見報告を行うことができます。<br>　調査実施者によって質問が設定されている場合はそれらの質問に回答後、位置情報などの基本調査項目を入力します。</p>
                <button type="button" class="btn btn-primary btn-block btn-lg" onclick="location.href='search-ikimono-bingo.php?id=<?php echo $researchID; ?>'">発見報告を行う</button>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" class="btn btn-gray btn-block btn-flat" onclick="location.href='research-top.php?id=<?php echo $researchID; ?>'">調査トップに戻る</button>
              </div>
            </div>
          </div>

          <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">対象種画像</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <h3>対象種：<?php echo $targetName; ?></h3>
                <hr>
                <h5>[対象種概要]</h5>
                <p><?php echo $targetOverview; ?></p>
                <div class="margin">
                  <img src="<?php echo $targetPicture; ?>" class="img-responsive" alt="">

                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
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
  <!-- ChartJS -->
  <script src="bower_components/chart.js/Chart.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <script type="text/javascript">
    const ACTIVE_CLASS = "block--active";
    const TRANSITION_CLASS = "block--transition";

    const getTransforms = (a, b) => {
      const scaleY = a.height / b.height;
      const scaleX = a.width / b.width;

      // dividing by 2 centers the transform since the origin
      // is centered not top left
      const translateX = a.left + a.width / 2 - (b.left + b.width / 2);
      const translateY = a.top + a.height / 2 - (b.top + b.height / 2);

      // nothing particularly clever here, just using the
      // translate amount to estimate a rotation direction/amount.
      // ends up feeling pretty natural to me.
      const rotate = translateX;

      return [
        `translateX(${translateX}px)`,
        `translateY(${translateY}px)`,
        `rotate(${rotate}deg)`,
        `scaleY(${scaleY})`,
        `scaleX(${scaleX})`
      ].join(" ");
    };

    const animate = (block, transforms, oldTransforms) => {
      block.style.transform = transforms;
      block.getBoundingClientRect(); // force redraw
      block.classList.add(TRANSITION_CLASS);
      block.style.transform = oldTransforms;
      block.addEventListener(
        "transitionend",
        () => {
          block.removeAttribute("style");
        }, {
          once: true
        }
      );
    };

    [...document.querySelectorAll(".block")].forEach(block => {
      const buttonForBlock = block.querySelector(".block-content__button");
      block.addEventListener("click", event => {
        if (
          block.classList.contains(ACTIVE_CLASS) &&
          event.target !== buttonForBlock
        ) {
          return;
        }

        block.classList.remove(TRANSITION_CLASS);
        const inactiveRect = block.getBoundingClientRect();
        const oldTransforms = block.style.transform;

        block.classList.toggle(ACTIVE_CLASS);
        const activeRect = block.getBoundingClientRect();
        const transforms = getTransforms(inactiveRect, activeRect);

        animate(block, transforms, oldTransforms);
      });
    });
  </script>
</body>

</html>
