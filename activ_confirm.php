<?php
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名

// ユーザ名の初期化
$loginName = "ゲストユーザ";
// エラーメッセージ
$errorMessage = "";
if (empty($_SESSION["activ_name"])) {  // 値が空のとき
    $errorMessage = '調査名が未入力です。';
}

$login = false;

$activ_name = $_SESSION['activ_name'];
$start = $_SESSION['start'];
$end = $_SESSION['end'];
$activ_details = $_SESSION['activ_details'];
$activ_target = $_SESSION['activ_target'];
$activ_overview = $_SESSION['activ_overview'];

// // 調査情報格納状態チェック
// if (!isset($_SESSION["activ_name"])) {
//     $_SESSION['message'] = 'エラーが発生したため、再度コミュニティページから新規調査作成を行ってください。';
//     header("Location: index.php");  // ログイン画面へ遷移
//     exit();
// }
// 
// 登録ボタンが押されたら
if (isset($_POST["regist"])) {
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

// エラー処理
    try {
 
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//}

//        $directory = 'img/research/' . $targetPicture;
//
//        if (!copy('tmp/research/' . $targetPicture, $directory)) {
//           echo "ファイルコピーエラー";
//            exit();
//        }

        $date = date("Ymd");



        // 基本設定テーブル（research）にインサート

   echo  $activ_name, $start, $end, $activ_details, $activ_target, $activ_overview, $date;

        $stmt = $pdo->prepare("INSERT INTO activity(activ_name, activ_details, researchStart, researchEnd, date, activ_target, activ_overview) VALUES (?, ?, ?, ?, ?, ?, ?)");
       $stmt->execute(array( $activ_name, $activ_details, $start, $end, $date, $activ_target, $activ_overview));
	$id = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$idに入れる
 //   // 追加質問テーブルにインサート
//         $stmt = $pdo->prepare("INSERT INTO research_question(researchID, type, question, options) VALUES (?, ?, ?, ?)");
// 
//         foreach ($searchAry as $key => $value) {
//             $stmt->execute(array($id, $value['type'], $value['question'], $value['option']));
//         }

        unset($_SESSION['activ_name'], $_SESSION['start'], $_SESSION['end'], $_SESSION['activ_details'], $_SESSION['activ_target'], $_SESSION['activ_overview']);

        header("Location: activ_research-top.php?id= $id");

        // $errorMessage = $colums;
    } catch (PDOException $e) {
       $errorMessage;
        // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
         echo $e->getMessage();
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
          <!-- <li><a href="mypage.php?user=<?php //echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li> -->
          <!-- <li><a href="runkingu.php"><i class="fa fa-pie-chart"></i><span>ランキング</span></a></li> -->
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
        if (!empty($errorMessage)) {
            $errorMessage = htmlspecialchars($errorMessage, ENT_QUOTES);
            echo <<<EOM
            <div class="callout callout-warning">
            <h4>ERROR</h4>
            <p>$errorMessage</p>
            </div>
EOM;
        }
        ?>

        <h3>
          設定確認
          <small>Confirmation</small>
        </h3>

        <div class="box box-success box-solid">
          <div class="box-header with-border">
            <i class="fa fa-text-width"></i>

            <h3 class="box-title">この設定で新規調査を作成します</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <dl class="dl-horizontal">
              <h4>基本設定: </h4>
             
              <dt>調査名: </dt>
              <dd><?php echo $activ_name; ?></dd>
              <dt>調査概要: </dt>
              <dd><?php echo $activ_details; ?></dd>
              <dt>調査期間: </dt>
              <dd><?php echo $start . "月　～　" . $end . "月"; ?></dd>
              <dt>対象名: </dt>
              <dd><?php echo $activ_target; ?></dd>
              <dt>対象概要: </dt>
              <dd><?php echo $activ_overview; ?></dd>
                           
            </dl>
            <form action="" method="post">
              <button type="submit" name="regist" class="btn btn-danger btn-block btn-flat">新規調査作成</button>
            </form>
          </div>
          <!-- /.box-body -->
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
</body>

</html>
