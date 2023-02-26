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
$login = false;
if (isset($_GET['id'])) {
    $debris_reID = $_GET['id'];
    $debris_reID = preg_replace('/[^0-9]/', '', $debris_reID);
//  echo $debris_reID;
// パラメータの確認
// if (isset($_GET['id'])) {
//   $activityID = $_GET['id'];
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//    try {
        // $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
//         $stmt = $pdo->prepare('SELECT * FROM activity WHERE activityID = ?');
//         $stmt->execute(array($activityID));
// echo $activityID;
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

if (isset($_POST["regist"])) {
    // $_SESSION['upfile'] = htmlspecialchars($_POST['upfile'], ENT_QUOTES);
    // $upfile = $_SESSION['upfile'];
    // echo "$upfile";
    
  if (is_uploaded_file($_FILES[upfile1]["tmp_name"])) {
    if (move_uploaded_file ($_FILES[upfile1]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile1]["name"])) {
   // chmod("files/" . date("Ymd-His") . $_FILES["upfile1"]["name"], 0644);
    echo $_FILES[upfile1]["name"] . "をアップロードしました。";
    echo "" .date("Ymd-His") . $_FILES["upfile1"]["name"];
  } 
}
  if (is_uploaded_file($_FILES[upfile2]["tmp_name"])) {
    if (move_uploaded_file ($_FILES[upfile2]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile2]["name"])) {
   // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
    echo $_FILES[upfile2]["name"] . "をアップロードしました。";
    echo "" .date("Ymd-His") . $_FILES["upfile2"]["name"];
  } 
}
  if (is_uploaded_file($_FILES[upfile3]["tmp_name"])) {
    if (move_uploaded_file ($_FILES[upfile3]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile3]["name"])) {
   // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
    echo $_FILES[upfile3]["name"] . "をアップロードしました。";
    echo "" .date("Ymd-His") . $_FILES["upfile3"]["name"];
}
  } else {
    echo "ファイルが選択されていません。";
  }


  $stmt = $pdo->prepare("INSERT INTO debris_media(debris_reID, debris_media1, debris_media2, debris_media3) VALUES (?,?,?,?)");
  $stmt->execute(array($debris_reID, "files/" .date("Ymd-His") . $_FILES[upfile1]["name"], "files/" .date("Ymd-His") . $_FILES[upfile2]["name"], "files/" .date("Ymd-His") . $_FILES[upfile3]["name"]));
//   $id = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$idに入れる
// echo $id;

header("Location: debris_type.php?id=$debris_reID");

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
            <!-- <li class="dropdown user user-menu"> -->
              <!-- Menu Toggle Button -->
              <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown"> -->
                <!-- The user image in the navbar-->
                <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <!-- <span class="hidden-xs"><?php echo htmlspecialchars($loginName, ENT_QUOTES); ?></span> -->
              <!-- </a> -->
              <!-- <ul class="dropdown-menu"> -->
                <!-- The user image in the menu -->
                <!-- <li class="user-header"> -->
                  <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                  <!-- <p> -->
                    <!-- <?php// echo htmlspecialchars($loginName, ENT_QUOTES); ?> -->
                  <!-- </p> -->
                <!-- </li> -->
                <!-- Menu Body -->
                <!-- <li class="user-body"> -->
                  <!-- <div class="row"> -->
                    <!-- <div class="col-xs-12 text-center"> -->
                      <!-- <a href="mypage.php?user=<?php// echo $loginID; ?>">マイページ</a> -->
                    <!-- </div> -->
                  <!-- </div> -->
                  <!-- /.row -->
                <!-- </li> -->
                <!-- Menu Footer-->
                <!-- <li class="user-footer"> -->
                  <!-- <div class="pull-right"> -->
                    <?php
                    // if ($login) {
                    //     echo '<a href="logout.php" class="btn btn-default btn-flat">ログアウト</a>';
                    // } else {
                    //     echo '<a href="login.php" class="btn btn-default btn-flat">ログイン</a>';
                    // }
                    ?>
                  <!-- </div> -->
                <!-- </li> -->
              <!-- </ul> -->
            <!-- </li> -->
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
            <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
          </div>
          <div class="pull-left info">
            <p><?php// echo htmlspecialchars($loginName, ENT_QUOTES); ?></p>
            <!-- Status -->
            <!-- <a href="#">アカウントレベル0</a> -->
          </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">　</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.php"><i class="fa fa-link"></i><span>トップ</span></a></li>
          <!-- <li><a href="mypage.php?user=<?php //echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li> -->
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
        <?php
//         if (!empty($errorMessage) || !empty($message)) {
//             $errorMessage = htmlspecialchars($errorMessage, ENT_QUOTES);
//             $message = htmlspecialchars($message, ENT_QUOTES);
//             echo <<<EOM
//           <div class="callout callout-warning">
//           <h4>ERROR</h4>
//           <p>$errorMessage</p>
//           <p>$message</p>
//           </div>
// EOM;
//         }
        ?>
        <!-- Info boxes -->
        




<form  method="post" enctype="multipart/form-data">

<h1>海岸の状況を撮って下さい</h1>
<h1>添付画像の説明</h1>
<p>海岸漂着物の写真を添付して下さい</p>
<p>サンプルとして以下に画像を置いています。参考になるにして下さい
<p>※アップする画像は（jpg、jpeg、PNG）でアップロードして下さい</p>
<img src="img_files/debris1.jpeg" width="300" height="200">
<img src="img_files/debris2.jpeg" width="300" height="200">
<br>
<h2></h2>
	<label >海岸漂着ごみのアップロード</label>
	<input type="file"  name="upfile1" ><br>
  
	<input type="file"  name="upfile2" ><br>
   
	<input type="file"  name="upfile3" ><br>
    <input type="submit" name="regist" class="btn btn-block btn-success btn-lg">
</form>




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