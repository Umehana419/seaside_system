<?php
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名

// ユーザ名の初期化
$loginName = "ゲストユーザ";
// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$login = false;

// ログイン状態チェック
if (isset($_SESSION["ID"])) {
    // IDの格納
    $loginID = $_SESSION['ID'];
    $loginName = $_SESSION['name'];
    $login = true;
} else {
    $_SESSION['message'] = '新規コミュニティ作成はログインが必要です。';
    header("Location: login.php");  // ログイン画面へ遷移
    exit();
}

echo $loginID;
echo $date = date("Y/m/d");

// サインアップボタンが押された場合
if (isset($_POST["regist"])) {
    if (empty($_POST["name"])) {  // 値が空のとき
        $errorMessage = 'コミュニティ名が未入力です。';
    }

    // 必須項目のチェック
    if (!empty($_POST["name"])) {
        // 入力したユーザIDとパスワードを格納
        $name = $_POST["name"];
        $overview = $_POST["overview"];
        $point = $_POST["point"];
        $tag = $_POST["tag"];
        $policy = $_POST["policy"];

        // ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("INSERT INTO community(name, adminID, overview, point, tag, policy) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute(array($name, $loginID, $overview, $point, $tag, $policy));
            $id = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$idに入れる

            $stmt = $pdo->prepare("INSERT INTO community_member(communityID, userID, userRole, date) VALUES (?, ?, ?, ?)");
            $stmt->execute(array($id, $loginID, "admin", $date));

            header("Location: community.php?id=" . $id);  // コミュニティ画面へ遷移
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
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
  <title>市民参加型調査支援システム | コミュニティ作成</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="plugins/bootstrap-slider/slider.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/skin-black.min.css">

  <!--leaflet's stylesheet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>

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
          <li><a href="runkingu.php"><i class="fa fa-pie-chart"></i><span>ランキング</span></a></li>
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
          新規コミュニティ作成
          <small>Create New Community</small>
        </h3>
        <form action="" method="post">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">コミュニティ設定</h3>
            </div>
            <div class="box-body">
              <label>コミュニティ名</label>
              <small>　調査地や調査対象などがわかるような名前にしてください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" class="form-control pull-right require" name="name">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <label>コミュニティ概要</label>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-pencil"></i>
                  </div>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="overview"></textarea>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <label>主な活動地点</label>
              <small>　マップ上をクリックすることで座標が入力され、コミュニティの主な活動地点を設定することができます。ここで設定した活動地点は、トップページのコミュニティ一覧マップに表示されます。</small>
              <div class="margin" id="map" style="width: 100%; height: 500px"></div>
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input id="target" type="text" class="form-control pull-right require" name="point" value="39.7,140.8" readonly>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <label>登録タグ</label>
              <small>　コミュニティの情報をタグとして登録できます。複数のタグを登録する場合は半角カンマで区切ってください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-star"></i>
                  </div>
                  <input type="text" class="form-control pull-right require" name="tag" placeholder="例)外来生物,岩手県,植物調査">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <label>注意事項</label>
              <small>　コミュニティの注意事項を設定できます。注意事項はユーザがこのコミュニティに参加するときに表示されます。メンバーの除名ポリシーや、検証者認定基準などの記載を想定しています。</small>
              <div class="form-group has-warning">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-pencil"></i>
                  </div>
                  <textarea class="form-control require" rows="3" placeholder="Enter ..." name="policy"></textarea>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <!-- <label>Twitter連携</label>
              <p>　コミュニティで運用するTwitterアカウントがある場合、連携することで調査
                状況や報告結果をTwitterに投稿することができます。</p>
              <div class="form-group">
                <a class="btn btn-block btn-social btn-twitter">
                  <i class="fa fa-twitter"></i> Sign in with Twitter</a>
              </div> -->
              <!-- /.form group -->
            </div>
            <!-- /.box-body -->
          </div>
          <button type="submit" class="btn btn-block btn-success btn-lg" name="regist">コミュニティ作成</button>
        </form>

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
  <!-- Select2 -->
  <script src="bower_components/select2/dist/js/select2.full.min.js"></script>
  <!-- InputMask -->
  <script src="plugins/input-mask/jquery.inputmask.js"></script>
  <script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <!-- date-range-picker -->
  <script src="bower_components/moment/min/moment.min.js"></script>
  <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- bootstrap time picker -->
  <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="plugins/iCheck/icheck.min.js"></script>
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
  <!-- Bootstrap slider -->
  <script src="plugins/bootstrap-slider/bootstrap-slider.js"></script>
  <script>
    $(function() {

      // フォーム入力チェック
      $('input.require, textarea.require').on('blur', function() {
        let error;
        let value = $(this).val();
        if (value == "") {
          error = true;
        } else if (!value.match(/[^\s\t]/)) {
          error = true;
        }

        if (error) {
          // エラー時の処理
          if (!($(this).parent().parent().hasClass('has-warning'))) {
            $(this).parent().parent().addClass('has-warning');
            $(this).parent().parent().removeClass('has-success');
          }

          // エラーで、エラーメッセージがなかったら
          if (!$(this).parent().nextAll('span.help-block').length) {
            //メッセージを後ろに追加
            $(this).parent().after('<span class="help-block">この項目の入力は必須です。</span>');
          }
        } else {
          // 正常時の処理
          if ($(this).parent().parent().hasClass('has-warning')) {
            $(this).parent().parent().removeClass('has-warning');
            $(this).parent().parent().addClass('has-success');
          }

          // エラーじゃないのにメッセージがあったら
          if ($(this).parent().nextAll('span.help-block').length) {
            // エラーメッセージを消す
            $(this).parent().nextAll('span.help-block').remove();
          }
        }
      });

      // submitチェック
      $('form').on('submit', function() {
        // 必須項目のエラー数チェック
        let error = $(this).find('div.has-warning').length;
        if (error) {
          alert("未入力の項目があります。");
          return false;
        }
      });
    })

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
      }).setView([lat, lng], 5);
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
    for (var i = 0; i < 2; i++) {
      //配列の要素数を指定する
      array[i] = [];
      array[i][0] = 39.7;
      array[i][1] = 140.8;
      array[i][2] = 100;
      array[i][3] = "39.7 140.8";
    }

    //Map生成　必ず最初に実行
    mapGenerate(39.7, 140.8);
    controlGenerate("2015", array);
    //初期表示設定
    overlays["2015"].addTo(map);
    map.on('click', function(e) {
      map.removeLayer(overlays["2015"]);
      //クリック位置経緯度取得
      lat = e.latlng.lat;
      lng = e.latlng.lng;
      //経緯度表示
      // alert("lat: " + lat + ", lng: " + lng);
      overlays["2015"] = new L.Marker(e.latlng);
      map.addLayer(overlays["2015"]);
      overlays["2015"].bindPopup("経度:"+lat+"<br>緯度:"+lng).openPopup();
      document.getElementById("target").value = lat+","+lng;
    })
  </script>
</body>

</html>
