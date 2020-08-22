<?php
// セッション開始
session_start();

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$login = false;
// ユーザ名の初期化
$loginName = "ゲストユーザ";

// サインアップボタンが押された場合
if (isset($_POST["newResearch"])) {
    // $_SESSION['researchName'] = htmlspecialchars($_POST['researchName'], ENT_QUOTES);
    $searchAry = array();

    for ($i=1; $i < 9; $i++) {
        if (strcmp($_POST[$i], "free") == 0) {
            $searchAry[] = array('type' => $_POST["$i"], 'question' => $_POST['free' . "$i"], 'option' => 0);
        } elseif (strcmp($_POST[$i], "select") == 0) {
            $searchAry[] = array('type' => $_POST["$i"], 'question' => $_POST['select' . "$i"], 'option' => $_POST['selectQ' . "$i"]);
        // $searchAry[$j][0] = $_POST["$i"];
            // $searchAry[$j][1] = $_POST['select' . "$i"];
            // $searchAry[$j][2] = $_POST['selectQ' . "$i"];
        } elseif (strcmp($_POST[$i], "check") == 0) {
            $searchAry[] = array('type' => $_POST["$i"], 'question' => $_POST['check' . "$i"], 'option' => 0);
            // $searchAry[$j][0] = $_POST["$i"];
            // $searchAry[$j][1] = $_POST['check' . "$i"];
            // $j++;
        }
    }
    $_SESSION['searchAry'] = $searchAry;


    // for ($i=1; $i < 9; $i++) {
    //     if (strcmp($_POST[$i], "free") == 0) {
    //         $_SESSION['type' . "$i"] = $_POST["$i"];
    //         $_SESSION['question' . "$i"] = $_POST['free' . "$i"];
    //     } elseif (strcmp($_POST[$i], "select") == 0) {
    //         $_SESSION['type' . "$i"] = $_POST["$i"];
    //         $_SESSION['question' . "$i"] = $_POST['select' . "$i"];
    //         $_SESSION['option' . "$i"] = $_POST['selectQ' . "$i"];
    //     } elseif (strcmp($_POST[$i], "check") == 0) {
    //         $_SESSION['type' . "$i"] = $_POST["$i"];
    //         $_SESSION['question' . "$i"] = $_POST['check' . "$i"];
    //     } elseif (strcmp($_POST[$i], "none") == 0) {
    //         $_SESSION['type' . "$i"] = $_POST["$i"];
    //     }
    // }

    header("Location: newresearch-confirm.php");
}

// 調査情報格納状態チェック
if (!isset($_SESSION["communityID"])) {
  $_SESSION['message'] = 'エラーが発生したため、再度コミュニティページから新規調査作成を行ってください。';
  header("Location: index.php");  // ログイン画面へ遷移
  exit();
}

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
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>いきもの調査 | 調査詳細設定画面</title>
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
          <li><a href="runkingu.php"><i class="fa fa-pie-chart"></i><span>ランキング</span></a></li>
          <li><a href="about.html"><i class="fa fa-pie-chart"></i><span>このシステムについて</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          新規調査作成
          <small>Create New Research</small>
        </h1>
        <p>調査項目を自由に設定することができます。5つまで調査項目を追加することが
          可能で、回答方法を個別に設定できます。<b>ビンゴ調査を行う場合は必ず5つ全て設定してください。</b></p>
      </section>

      <!-- Main content -->
      <section class="content">
        <form action="" method="post">
          <div class="callout callout-warning">
            <h4>WARNING</h4>
            <p><b>　基本項目（見つけた場所、見つけた日時、生息数、生物の画像）はすでに設定され
                ているため、これらの調査項目の追加は必要ありません。</b></p>
          </div>

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

          <h4>調査項目 1</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="1" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="1" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="1" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="1" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free1" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free1">
                </div>
              </div>
              <div id="select1" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select1">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ1">
                  </div>
                </div>
              </div>
              <div id="check1" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check1">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 2</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="2" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="2" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="2" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="2" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free2" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free2">
                </div>
              </div>
              <div id="select2" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select2">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ2">
                  </div>
                </div>
              </div>
              <div id="check2" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check2">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 3</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="3" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="3" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="3" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="3" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free3" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free3">
                </div>
              </div>
              <div id="select3" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select3">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ3">
                  </div>
                </div>
              </div>
              <div id="check3" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check3">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 4</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="4" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="4" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="4" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="4" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free4" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free4">
                </div>
              </div>
              <div id="select4" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select4">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ4">
                  </div>
                </div>
              </div>
              <div id="check4" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check4">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 5</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="5" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="5" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="5" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="5" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free5" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free5">
                </div>
              </div>
              <div id="select5" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select5">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ5">
                  </div>
                </div>
              </div>
              <div id="check5" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check5">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 6</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="6" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="6" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="6" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="6" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free6" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free6">
                </div>
              </div>
              <div id="select6" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select6">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ6">
                  </div>
                </div>
              </div>
              <div id="check6" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check6">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 7</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="7" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="7" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="7" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="7" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free7" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free7">
                </div>
              </div>
              <div id="select7" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select7">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ7">
                  </div>
                </div>
              </div>
              <div id="check7" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check7">
                </div>
              </div>
            </div>
          </div>
          <h4>調査項目 8</h4>
          <div class="box box-solid">
            <div class="box-header with-border">
              <div class="col-xs-3"><input id="1" type="radio" name="8" value="free" />
                <lavel> 自由記述式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="8" value="select" />
                <lavel> 選択式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="8" value="check" />
                <lavel> チェック式</lavel>
              </div>
              <div class="col-xs-3"><input type="radio" name="8" value="none" checked />
                <lavel> なし</lavel>
              </div>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="box-body">
              <div id="free8" style="display: none;">
                <p>　自由記述式では、回答者は設定された質問に対する回答を自由に記述することができます。予想される回答が多岐にわたる場合や、正確性が求められない質問に最適です。一般的に、生物生息環境の説明や、その他自由項目欄として利用されます。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="free8">
                </div>
              </div>
              <div id="select8" style="display: none;">
                <p>　選択式では、回答者は用意された選択肢から1つを選び回答します。回答が限定される場合、回答者の誤入力を防ぎたいときに最適です。一般的に、天気や生物の行動などの回答に利用されます。</p>
                <div class="form-group"><label>質問内容</label><small>　質問内容を入力してください</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="select8">
                  </div>
                </div>
                <div class="form-group"><label>選択肢</label><small>　選択肢を,(カンマ)で区切って入力してください。</small>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-star"></i></div><input type="text" class="form-control pull-right" name="selectQ8">
                  </div>
                </div>
              </div>
              <div id="check8" style="display: none;">
                <p>　チェック式では、回答者は設定された質問に対する回答を「はい」か「いいえ」で回答します。この方式のみ画像を添付することができ、画像は回答時に質問と同時に表示されます。類似生物との比較・確認や、フィールドビンゴ調査での利用を想定しています。</p><label>質問内容</label><small>　質問内容を入力してください。</small>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div><input type="text" class="form-control pull-right" name="check8">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-block btn-gray btn-lg disabled">概要設定に戻る</button>
            </div>
            <div class="col-md-6">
              <button type="submit" name="newResearch" class="btn btn-block btn-success btn-lg">設定確認へ</button>
            </div>
          </div>
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
    $('input').on('ifChecked', function(event) {
      // 変化があったときの処理
      var option = $(this).val();
      var number = $(this).attr('name');
      var showId = '#' + option + number;
      if (option == "free") {
        var hideId1 = '#' + 'select' + number;
        var hideId2 = '#' + 'check' + number;
        $(showId).show();
        $(hideId1).hide();
        $(hideId2).hide();
      } else if (option == "select") {
        var hideId1 = '#' + 'free' + number;
        var hideId2 = '#' + 'check' + number;
        $(showId).show();
        $(hideId1).hide();
        $(hideId2).hide();
      } else if (option == "check") {
        var hideId1 = '#' + 'select' + number;
        var hideId2 = '#' + 'free' + number;
        $(showId).show();
        $(hideId1).hide();
        $(hideId2).hide();
      } else if (option == "none") {
        var hideId1 = '#' + 'select' + number;
        var hideId2 = '#' + 'free' + number;
        var hideId3 = '#' + 'check' + number;
        $(hideId3).hide();
        $(hideId1).hide();
        $(hideId2).hide();
      }
    });

    $(document).ready(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square',
        radioClass: 'iradio_square-red',
        increaseArea: '20%' // optional
      });
    });

    $(function() {

      //Initialize Select2 Elements
      $('.select2').select2()

      /* BOOTSTRAP SLIDER */
      $('.slider').slider()

      //Datemask dd/mm/yyyy
      $('#datemask').inputmask('mm/dd', {
        'placeholder': 'mm/dd/yyyy'
      })
      //Datemask2 mm/dd/yyyy
      $('#datemask2').inputmask('mm/dd/yyyy', {
        'placeholder': 'mm/dd/yyyy'
      })
      //Money Euro
      $('[data-mask]').inputmask()

      //Date range picker
      $('#reservation').daterangepicker({
        format: 'YYYY/MM/DD',
        showDropdowns: false,
        ranges: {
          '直近30日': [moment().subtract('days', 29), moment()],
          '今月': [moment().startOf('month'), moment().endOf('month')],
          '先月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        }
      })
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        format: 'MM/DD/YYYY h:mm A'
      })
      //Date range as a button
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

      //Date picker
      $('#datepicker').datepicker({
        autoclose: true
      })

      //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      })
      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
      })
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      })

      //Colorpicker
      $('.my-colorpicker1').colorpicker()
      //color picker with addon
      $('.my-colorpicker2').colorpicker()

      //Timepicker
      $('.timepicker').timepicker({
        showInputs: false
      })
    })
  </script>
</body>

</html>
