﻿<?php
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

// パラメータの確認

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
        $stmt = $pdo->prepare('SELECT * FROM activity WHERE activityID = 1');
        $stmt->execute(array($activityID));

        // 取得確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $month = (int)date("m");
            // 期間内か確認
 //           if ($row['researchStart'] <= $row['researchEnd']) {
 //               if (($row['researchStart'] <= $month) && ($month <= $row['researchEnd'])) {
                    $activ_name = $row['activ_name'];
                    $activ_details = $row['activ_details'];
                    $start = $row['researchStart'];
                    $end = $row['researchEnd'];
                    $activ_target = $row['activ_target'];
		    $activ_overview = $row['activ_overview'];
//                } else {
//                    $_SESSION['message'] = "この調査は期間外のため報告できません。";
//                    header("Location: index.php");  // メイン画面へ遷移
//                    exit();
 //               }
//            } else {
 //               if (($row['researchStart'] <= $month) && ($month >= $row['researchEnd'])) {
 //                  $activ_name = $row['activ_name'];
 //                   $activ_details = $row['activ_details'];
//                    $start = $row['researchStart'];
//                    $end = $row['researchEnd'];
//                    $activ_target = $row['activ_target'];
//		    $activ_overview = $row['activ_overview'];
//                } else {
//                    $_SESSION['message'] = "この調査は期間外のため報告できません。";
//                    header("Location: index.php");  // メイン画面へ遷移
//                    exit();
//                }
//            }
//        } else {
//            $_SESSION['message'] = "この調査は存在しません。";
//            header("Location: index.php");  // メイン画面へ遷移
//            exit();
//        }
//    } catch (PDOException $e) {
//        $errorMessage = 'データベースエラーが発生しました。';
//        // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
 //       // echo $e->getMessage();
//    }
//} else {
//    $_SESSION['message'] = "この調査は存在しません。";
//    header("Location: index.php");  // メイン画面へ遷移
 //   exit();
}

// 登録ボタンが押されたら
if (isset($_POST["regist"])) {
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
           $_SESSION['lng'] = htmlspecialchars($_POST['lng'], ENT_QUOTES);
            $_SESSION['lat'] = htmlspecialchars($_POST['lat'], ENT_QUOTES);
            $_SESSION['group_name'] = htmlspecialchars($_POST['group_name'], ENT_QUOTES);
            $_SESSION['people_num'] = htmlspecialchars($_POST['people_num'], ENT_QUOTES);
            $_SESSION['comment'] = htmlspecialchars($_POST['comment'], ENT_QUOTES);
   
	$lng = $_SESSION['lng'];
$lat = $_SESSION['lat'];
$group_name = $_SESSION['group_name'];
$people_num = $_SESSION['people_num'];
$comment = $_SESSION['comment'];
// エラー処理
//    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//        $directory = 'img/research/' . $targetPicture;

//        if (!copy('tmp/research/' . $targetPicture, $directory)) {
//            echo "ファイルコピーエラー";
 //           exit();
//        }

 $date = date("Ymd");
echo $lng, $lat, $date, $group_name, $people_num, $comment;
        // 基本設定テーブル（research）にインサート
        $stmt = $pdo->prepare("INSERT INTO activ_report(activityID, userID, lng, lat, date, typeID, group_name, people_num, comment) VALUES (1,1,?,?,?,1,?,?,?)");
        $stmt->execute(array($lng, $lat, $date, $group_name, $people_num, $comment));
        $id = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$idに入れる
echo $id;
$_SESSION['img1'] = htmlspecialchars($_POST['img1'], ENT_QUOTES);
            $_SESSION['img2'] = htmlspecialchars($_POST['img2'], ENT_QUOTES);
            $_SESSION['img3'] = htmlspecialchars($_POST['img3'], ENT_QUOTES);
            $_SESSION['img4'] = htmlspecialchars($_POST['img4'], ENT_QUOTES);
            $_SESSION['img5'] = htmlspecialchars($_POST['img5'], ENT_QUOTES);
            $_SESSION['img6'] = htmlspecialchars($_POST['img6'], ENT_QUOTES);
            $_SESSION['img7'] = htmlspecialchars($_POST['img7'], ENT_QUOTES);
            $_SESSION['img8'] = htmlspecialchars($_POST['img8'], ENT_QUOTES);
            $_SESSION['img9'] = htmlspecialchars($_POST['img9'], ENT_QUOTES);
$img1 = $_SESSION['img1'];
$img2 = $_SESSION['img2'];
$img3 = $_SESSION['img3'];
$img4 = $_SESSION['img4'];
$img5 = $_SESSION['img5'];
$img6 = $_SESSION['img6'];
$img7 = $_SESSION['img7'];
$img8 = $_SESSION['img8'];
$img9 = $_SESSION['img9'];
$stmt = $pdo->prepare("INSERT INTO activ_media(activ_reID, media_be1, media_be2, media_be3, media_now1, media_now2, media_now3, media_af1, media_af2, media_af3) VALUES (?,?,?,?,?,?,?,?,?,?");
        $stmt->execute(array($id, $img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8, $img9));
echo $img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8, $img9;
//}
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
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="plugins/bootstrap-slider/slider.css">
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
          基本調査項目
          <small>Create New Research</small>
        </h3>

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

        <div class="box box-success box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">報告する調査を確認してください。</h3>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <strong><i class="fa fa-search margin-r-5"></i> 調査名</strong>
            <p class="text-muted"><?php echo $activ_name; ?></p>

            <strong><i class="fa fa-book margin-r-5"></i> 概要</strong>
            <p class="text-muted"><?php echo $activ_details; ?></p>

            <strong><i class="fa fa-map-marker margin-r-5"></i> 対象</strong>
            <p class="text-muted"><?php echo $activ_target; ?></p>

 	<strong><i class="fa fa-map-marker margin-r-5"></i> 対象概要</strong>
            <p class="text-muted"><?php echo $activ_overview; ?></p>



            <strong><i class="fa fa-file-text-o margin-r-5"></i> 期間</strong>
            <p class="text-muted"><?php echo $start; ?>月 ～ <?php echo $end; ?>月</p>
          </div>
          <!-- /.box-body -->
        </div>


        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">基本調査項目を入力して調査を完了します。</h3>
          </div>
          <div class="box-body">
            <form action="" id="form" method="post">
              <input type="text" name="researchID" value="<?php echo $researchID; ?>" hidden>
              <input type="text" name="loginID" value="<?php echo $loginID; ?>" hidden>
              <label>行った場所</label>
              <small>　行った場所をGPSを用いて入力します。GPSを有効にして「位置情報を取得」を押してください。</small>
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input id="target1" type="text" name="lng" class="form-control pull-left" value="" readonly>
                  <input id="target2" type="text" name="lat" class="form-control pull-left" value="" readonly>
                </div>
                <button type="button" class="btn btn-block btn-info btn-md margin" onclick="getGeolocation()">位置情報を取得</button>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <div class="form-group">
                <label>行った日時</label>
                <small>　下の日時が合っているか確認してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar-check-o"></i>
                  </div>
                  <input type="text" name="date" class="form-control pull-right" value="<?php echo date("Y/m/d H:i:s"); ?>" readonly>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

                     
              <div class="form-group">
                <label>活動前様子の画像</label>
                <small>　下のボタンを押して活動の画像を撮影します。</small>
                <div class="input-group date">
                  <!-- <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div> -->
                  <input type="file" name="img1">
                  <input type="file" name="img2">
                  <input type="file" name="img3">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <img src="" id="img1" class="img-responsive">
              <img src="" id="img2" class="img-responsive">
              <img src="" id="img3" class="img-responsive">
              <!-- 圧縮するために一度キャンバスで描画する（非表示要素） -->
              <div style="display: none">
                <canvas id="canvas1"></canvas>
                <canvas id="canvas2"></canvas>
                <canvas id="canvas3"></canvas>
              </div>
      <div class="form-group">
                <label>活動中様子の画像</label>
                <small>　下のボタンを押して活動の画像を撮影します。</small>
                <div class="input-group date">
                  <!-- <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div> -->
                  <input type="file" name="img4">
                  <input type="file" name="img5">
                  <input type="file" name="img6">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <img src="" id="img4" class="img-responsive">
              <img src="" id="img5" class="img-responsive">
              <img src="" id="img6" class="img-responsive">
              <!-- 圧縮するために一度キャンバスで描画する（非表示要素） -->
              <div style="display: none">
                <canvas id="canvas4"></canvas>
                <canvas id="canvas5"></canvas>
                <canvas id="canvas6"></canvas>
              </div>

      <div class="form-group">
                <label>活動後様子の画像</label>
                <small>　下のボタンを押して活動の画像を撮影します。</small>
                <div class="input-group date">
                  <!-- <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div> -->
                  <input type="file" name="img7">
                  <input type="file" name="img8">
                  <input type="file" name="img9">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <img src="" id="img7" class="img-responsive">
              <img src="" id="img8" class="img-responsive">
              <img src="" id="img9" class="img-responsive">
              <!-- 圧縮するために一度キャンバスで描画する（非表示要素） -->
              <div style="display: none">
                <canvas id="canvas7"></canvas>
                <canvas id="canvas8"></canvas>
                <canvas id="canvas9"></canvas>
              </div>
  <label>活動団体名</label>
              <small>　活動した団体名を入力してください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-star"></i>
                  </div>
                  <input type="text" class="form-control pull-right require" name="group_name">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <div class="form-group">
  <label>参加人数</label>
              <small>　参加人数を入力してください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-star"></i>
                  </div>
                  <input type="text" class="form-control pull-right require" name="people_num">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <div class="form-group">
  <label>コメント</label>
              <small>　コメントを入力してください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-star"></i>
                  </div>
                  <input type="text" class="form-control pull-right require" name="comment">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <div class="form-group">


          </div>
          <!-- /.box-body -->
        </div>
        <button type="submit"  name="regist" class="btn btn-block btn-success btn-lg" >調査結果を報告する</button>
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
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- Bootstrap slider -->
  <script src="plugins/bootstrap-slider/bootstrap-slider.js"></script>
  <script>
    // スライダー用JS
    $(function() {
      /* BOOTSTRAP SLIDER */
      $("#slider").slider();
      $("#slider").on("slide", function(slideEvt) {
      	$("#sliderVal").text(slideEvt.value);
      });
    })

    // 位置情報取得用JS
    function getGeolocation() {
      navigator.geolocation.getCurrentPosition(getPosition);
      $("#submit").prop("disabled", false);
    }

    function getPosition(position) {
      var lng = position.coords.longitude;
      var lat = position.coords.latitude;

      document.getElementById("target1").value = lng;
      document.getElementById("target2").value = lat;
    }

    // プレビュー表示
    $('input[type=file]').change(function() {
      var file = $(this).prop("files")[0];
      var name = $(this).attr('name');

      //画像ファイルかチェック
      if (file["type"] != "image/jpeg" && file["type"] != "image/png" && file["type"] != "image/gif") {
        alert("jpgかpngかgifファイルを選択してください");
        $(this).val('');
        return false;
      }

      var fr = new FileReader();
      fr.onload = function() {
        //選択した画像をimg要素に表示
        $('#'+name).attr("src", fr.result);
      };
      fr.readAsDataURL(file);

    });

    // 圧縮・送信
    function imgUpload() {
      //加工後の横幅を800pxに設定
      var processingWidth = 800;

      //加工後の容量を100KB以下に設定
      var processingCapacity = 100000;

      //アップロード用blobをformDataに設定
      var form = $("#form").get(0);
      var formData = new FormData(form);

      //ファイル選択済みかチェック
      // var fileCheck = $('input[type=file]').val().length;
      // if (fileCheck === 0) {
      //   alert("画像ファイルを選択してください");
      //   return false;
      // }

      var fileCheck = 0;

      for (var i = 1; i <= 3; i++) {
        if ($("#img"+i).attr("src")) {
          //imgタグに表示した画像をimageオブジェクトとして取得
          var image = new Image();
          image.src = $("#img"+i).attr("src");

          var h;
          var w;

          //原寸横幅が加工後横幅より大きければ、縦横比を維持した縮小サイズを取得
          if (processingWidth < image.width) {
            w = processingWidth;
            h = image.height * (processingWidth / image.width);

            //原寸横幅が加工後横幅以下なら、原寸サイズのまま
          } else {
            w = image.width;
            h = image.height;
          }

          //取得したサイズでcanvasに描画
          var canvas = $("#canvas"+i);
          var ctx = canvas[0].getContext("2d");
          $("#canvas"+i).attr("width", w);
          $("#canvas"+i).attr("height", h);
          ctx.drawImage(image, 0, 0, w, h);

          //canvasに描画したデータを取得
          var canvasImage = $("#canvas"+i).get(0);

          //オリジナル容量(画質落としてない場合の容量)を取得
          var originalBinary = canvasImage.toDataURL("image/jpeg"); //画質落とさずバイナリ化
          var originalBlob = base64ToBlob(originalBinary); //画質落としてないblobデータをアップロード用blobに設定
          console.log(originalBlob["size"]);

          //オリジナル容量blobデータをアップロード用blobに設定
          var uploadBlob = originalBlob;

          //オリジナル容量が加工後容量以上かチェック
          if (processingCapacity <= originalBlob["size"]) {
            //加工後容量以下に落とす
            var capacityRatio = processingCapacity / originalBlob["size"];
            var processingBinary = canvasImage.toDataURL("image/jpeg", capacityRatio); //画質落としてバイナリ化
            uploadBlob = base64ToBlob(processingBinary); //画質落としたblobデータをアップロード用blobに設定
            console.log(capacityRatio);
            console.log(uploadBlob["size"]);
          }

          formData.append("img"+i, uploadBlob);
          fileCheck++;
        }

      }

      if (fileCheck === 0) {
        alert("画像ファイルを最低でも1枚選択してください");
        return false;
      }

      //formDataをPOSTで送信
      $.ajax({
        async: false,
        type: "POST",
        url: "post.php",
        data: formData,
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        error: function(XMLHttpRequest) {
          console.log(XMLHttpRequest);
          alert("アップロードに失敗しました");
        },
        success: function(res) {
          if (res !== "OK") {
            console.log(res);
            alert("アップロードに失敗しました");
          } else {
            location.href = 'search-top.php?id=<?php echo $researchID; ?>';
          }
        }
      });
    }

    // 引数のBase64の文字列をBlob形式にする
    function base64ToBlob(base64) {
      var base64Data = base64.split(',')[1], // Data URLからBase64のデータ部分のみを取得
        data = window.atob(base64Data), // base64形式の文字列をデコード
        buff = new ArrayBuffer(data.length),
        arr = new Uint8Array(buff),
        blob,
        i,
        dataLen;
      // blobの生成
      for (i = 0, dataLen = data.length; i < dataLen; i++) {
        arr[i] = data.charCodeAt(i);
      }
      blob = new Blob([arr], {
        type: 'image/jpeg'
      });
      return blob;
    }
  </script>
</body>

</html>
