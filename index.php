<?php
session_start();

$db['host'] = "localhost"; // DBサーバのURL
$db['user'] = "g031p015"; // ユーザー名
$db['pass'] = "g031p015PW"; // ユーザー名のパスワード
$db['dbname'] = "g031p015"; // データベース名

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
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    // 調査情報テーブルにコミュニティ情報結合
    // $stmt = $pdo->prepare('SELECT *, community.name AS communityName, research.name AS researchName FROM research LEFT JOIN community ON research.communityID = community.communityID WHERE private = 1 ORDER BY researchID');
    // $stmt->execute();

    // $researchName = array();
    // $month = (int) date("m");
    // $research = array(); // 調査情報入れる変数
    // $researchMapDate = array();
    // $researchCount = 0;

    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     array_push($researchName, $row['name']);
    //     $research[] = array('researchName' => $row['researchName'], 'communityName' => $row['communityName'], 'researchID' => $row['researchID'], 'researchStart' => $row['researchStart'], 'researchEnd' => $row['researchEnd'], 'researchOverview' => $row['researchOverview']);
    //     $researchCount++;
    // }

    // // 調査情報テーブルにコミュニティ情報結合
    // $stmt = $pdo->prepare('SELECT * FROM community');
    // $stmt->execute();

    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //    //
    // }
    //活動記録呼び出し
    $activityCount = 0;
    $activ_name = array();
    $activity = array(); // 調査情報入れる変数
    $stmt = $pdo->prepare('SELECT * FROM activity');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array($activ_name, $row['name']);
        $activity[] = array('activ_name' => $row['activ_name'], 'activityID' => $row['activityID'], 'researchStart' => $row['researchStart'], 'researchEnd' => $row['researchEnd'], 'activ_details' => $row['activ_details']);
        $activityCount++;
    }

//状況報告の呼び出し
    $mediaCount = 0;
    $media_name = array();
    $media = array(); // 調査情報入れる変数
    $stmt = $pdo->prepare('SELECT * FROM media_search');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // array_push($media_name, $row['name']);
        $media[] = array('media_name' => $row['media_name'], 'mediaID' => $row['mediaID'], 'media_start' => $row['media_start'], 'media_end' => $row['media_end'], 'media_details' => $row['media_details']);
        $mediaCount++;
    }

//ごみ報告の呼び出し
    $debrisCount = 0;
    $debris_name = array();
    $debris = array(); // 調査情報入れる変数
    $stmt = $pdo->prepare('SELECT * FROM debris');
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // array_push($debris_name, $row['name']);
        $debris[] = array('debris_name' => $row['debris_name'], 'debrisID' => $row['debrisID'], 'debris_start' => $row['debris_start'], 'debris_end' => $row['debris_end'], 'debris_details' => $row['media_details']);
        $debrisCount++;
    }
    $allcount = $activityCount + $debrisCount + $mediaCount;

    // 報告数カウント
    // $stmt = $pdo->prepare('SELECT COUNT(reportID) FROM report');
    // $stmt->execute();

    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // $reportCount = $row["COUNT(reportID)"];

    // $validationCount = 0;

    // 報告数カウント
    // $stmt = $pdo->prepare('SELECT COUNT(*) FROM validation');
    // $stmt->execute();

    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // $validationCount = $row["COUNT(*)"];
    //活動報告数カウント
    $stmt = $pdo->prepare('SELECT * FROM activ_report ');
    $stmt->execute();
    $searchCount = 0;
    // $reportCount = 0;
    $reportAry = array();
    $userCountAry = array();
    $reportDateAry = array();
    $lavel = "";
    $reportData = "";
    $reportDataSum = "";
    $sum = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $searchCount++;
        // $latlng = explode(',', $row['point']);
        // $comment = $row['activ_overviwe'];
        // $link = '<a href="community.php?id=' . $row['communityID'] . '">コミュニティ情報を確認</a>';
        // $communityMapDate[] = array('activ_name' => $row['name'], 'lat' => $latlng[0], 'lng' => $latlng[1], 'comment' => $comment, 'link' => $link);

        // 年月日以外の要素を除外して配列に格納
        array_push($reportDateAry, preg_replace('/\s\d\d:\d\d:\d\d/', '', $row['date']));
        // ユーザ情報の格納
        // array_push($userCountAry, $row['userID']);
        $_SESSION["login_id"] = "id00001";
        $comment = '<p>報告日:' . $row['date'] . '<br>活動団体名:' . $row['group_name'] . '</p>';
        $comment .= '<a href="activ-report-detail.php?id=' . $activityID . '&report=' . $row['activ_reID'] . '">詳細情報を確認</a>';

        // マップピン情報を格納
        $reportAry[] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'comment' => $comment, 'group_name' => $row['group_name']);
        // echo $comment;
    }

    // チャート用の情報処理
    foreach (array_count_values($reportDateAry) as $key => $value) {
        $sum += $value;
        // チャートラベルの作成
        $lavel .= '"' . $key . '",';
        // 1日単位のチャートデータの作成
        $reportData .= $value . ',';
        // 累計でのチャートデータの作成
        $reportDataSum .= $sum . ',';
    }
    // チャート用に作成したデータの最後のカンマを取り除く
    $lavel = rtrim($lavel, ',');
    $reportData = rtrim($reportData, ',');
    $reportDataSum = rtrim($reportDataSum, ',');

    // 重複した値の除去
    $result = array_unique($userCountAry);
    // ユーザ数の格納
    $userCount = count($result);

    $stmt = $pdo->prepare('SELECT * FROM media_report ');
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $searchCount++;
        // 年月日以外の要素を除外して配列に格納
        //  array_push($reportDateAry, preg_replace('/\s\d\d:\d\d:\d\d/', '', $row['date']));
        //  // ユーザ情報の格納
        //  // array_push($userCountAry, $row['userID']);
        //  $_SESSION["login_id"] = "id00001";
        //  $comment = '<p>報告日:' . $row['date'] . '<br>活動団体名:' . $row['media_user'] . '</p>';
        //  $comment .= '<a href="activ-report-detail.php?id=' . $activityID . '&report=' . $row['activ_reID'] . '">詳細情報を確認</a>';

        //  // マップピン情報を格納
        //  $reportAry[] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'comment' => $comment, 'group_name' => $row['media_user']);
        // echo $comment;
    }

    $stmt = $pdo->prepare('SELECT * FROM debris_report ');
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $searchCount++;
        // 年月日以外の要素を除外して配列に格納
        //  array_push($reportDateAry, preg_replace('/\s\d\d:\d\d:\d\d/', '', $row['date']));
        //  // ユーザ情報の格納
        //  // array_push($userCountAry, $row['userID']);
        //  $_SESSION["login_id"] = "id00001";
        //  $comment = '<p>報告日:' . $row['date'] . '<br>活動団体名:' . $row['debris_user'] . '</p>';
        //  $comment .= '<a href="activ-report-detail.php?id=' . $activityID . '&report=' . $row['activ_reID'] . '">詳細情報を確認</a>';

        //  // マップピン情報を格納
        //  $reportAry[] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'comment' => $comment, 'group_name' => $row['debris_user']);
        // echo $comment;
    }
    // Javascriptで扱えるようにJSON形式にエンコード
    if (empty($reportAry)) {
        // ダミーデータの格納
        $reportAry[] = array('lat' => 39.80263880587395, 'lng' => 141.13756477832797, 'comment' => "※このデータは報告が0件のときに表示されるダミーデータです。", 'group_name' => 0);
        // Javascriptで扱えるようにJSON形式に変換
        $report = json_encode($reportAry);
    } else {
        // Javascriptで扱えるようにJSON形式に変換
        $report = json_encode($reportAry);
    }

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
  $user_label = $_SESSION['user_label'];
  $login = true;

    // 認証
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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
        <span class="logo-lg">海洋漂着ごみ調査支援システム</span>
      </a>
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <!-- <div class="navbar-custom-menu"> -->
          <!-- <ul class="nav navbar-nav"> -->
            <!-- User Account Menu -->
            <!-- <li class="dropdown user user-menu"> -->
              <!-- Menu Toggle Button -->
              <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown"> -->
                <!-- The user image in the navbar-->
                <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <!-- <span class="hidden-xs"><?php //echo htmlspecialchars($loginName, ENT_QUOTES); ?></span> -->
              <!-- </a> -->
              <!-- <ul class="dropdown-menu"> -->
                <!-- The user image in the menu -->
                <!-- <li class="user-header"> -->
                  <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                  <!-- <p> -->
                    <!-- <?php //echo htmlspecialchars($loginName, ENT_QUOTES); ?> -->
                  <!-- </p> -->
                <!-- </li> -->
                <!-- Menu Body -->
                <!-- <li class="user-body"> -->
                  <!-- <div class="row"> -->
                    <!-- <div class="col-xs-12 text-center"> -->
                      <!-- <a href="mypage.php?user=<?php //echo $loginID; ?>">マイページ</a> -->
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
          <!-- </ul> -->
        <!-- </div> -->
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
          <!-- <li><a href="mypage.php?user=<?php// echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li> -->
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
        <!-- <h3>
          お知らせ
          <small>News</small>
        </h3>
        <div class="row">
          <div class="col-md-12">
            <?php
// $i = 0;
// foreach ($researchName as $key => $value) {
//     // code...
//     echo '<ol class="breadcrumb">';
//     echo '<li><a href="#"><i class="fa fa-dashboard"></i> New!</a></li>';
//     echo '<li class="active">新規コミュニティ「' . $value . '」が作成されました！</li>';
//     echo '</ol>';
//     $i++;
//     if ($i == 3) {
//         break;
//     }
// }
?>
          </div>
        </div> -->
<h3>このシステムについての評価<?php echo $user_label;?></h3>
<a href="https://forms.gle/hgWsNAKnBYobfvMFA">Google form への評価をお願いします</a>

        <h3>
          活動報告一覧
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
                <table id="example" class="table table-bordered table-hover dataTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>調査期間</th>
                      <th>ステータス</th>
                      <th>調査名・調査リンク</th>
                      <th>調査種類</th>
                    </tr>
                  </thead>
                  <tbody>






                      <?php

foreach ($activity as $key => $value) {?>

                          <tr role="row">
                            <td><?php echo $value['activityID'] ?></td>
                            <td><?php echo $value['researchStart'] ?> ~ <?php echo $value['researchEnd'] ?></td>
                            <td>調査中</td>
                            <td><?php echo $value['activ_name'] ?><br>
                            <button type="button" class="btn btn-info btn-flat" onclick="location.href='activ_research-top.php?id=<?php echo $value['activityID'] ?>'">調査ページへ</button></td>
                            <td>活動報告</td>
                          </tr>

<?php }?>

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
          状況報告一覧
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
                      <th>調査種類</th>
                    </tr>
                  </thead>
                  <tbody>






                  <?php

foreach ($media as $key => $value) {?>

                          <tr role="row">
                            <td><?php echo $value['mediaID'] ?></td>
                            <td><?php echo $value['media_start'] ?> ~ <?php echo $value['media_end'] ?></td>
                            <td>調査中</td>
                            <td><?php echo $value['media_name'] ?><br>
                            <button type="button" class="btn btn-info btn-flat" onclick="location.href='media_research-top.php?id=<?php echo $value['mediaID'] ?>'">調査ページへ</button></td>
                            <td>海岸状況報告</td>
                          </tr>

<?php }?>


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
          ごみ報告一覧
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
                <table id="example3" class="table table-bordered table-hover dataTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>調査期間</th>
                      <th>ステータス</th>
                      <th>調査名・調査リンク</th>
                      <th>調査種類</th>
                    </tr>
                  </thead>
                  <tbody>






                  <?php

foreach ($debris as $key => $value) {?>

                          <tr role="row">
                            <td><?php echo $value['debrisID'] ?></td>
                            <td><?php echo $value['debris_start'] ?> ~ <?php echo $value['debris_end'] ?></td>
                            <td>調査中</td>
                            <td><?php echo $value['debris_name'] ?><br>
                            <button type="button" class="btn btn-info btn-flat" onclick="location.href='debris_research-top.php?id=<?php echo $value['debrisID'] ?>'">調査ページへ</button></td>
                            <td>漂着ごみ報告</td>
                          </tr>

<?php }?>


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
          海洋ごみ詳細報告
          <small>Debris</small>
        </h3>
          <p>海洋ごみ詳細</p>


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
              <table id="example4" class="table table-bordered table-hover dataTable">
                  <thead>
                    <tr>
                    <th>ID</th>
                      <th>調査期間</th>
                      <th>ステータス</th>
                      <th>調査名・調査リンク</th>
                      <th>調査種類</th>
                    </tr>
                  </thead>
                  <tbody>

                          <tr role="row">
                            <td>1</td>
                            <td>1~12</td>
                            <td>調査中</td>
                            <td>海洋ごみ詳細報告<br>
                            <button type="button" class="btn btn-info btn-flat" onclick="location.href='syousai.php'">調査ページへ</button></td>
                            <td>海洋ごみ詳細報告</td>
                          </tr>

                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>

        <h3>
          地図統合閲覧
          <small>Debris</small>
        </h3>
          <p>活動報告・状況報告・漂着物報告の全ての報告を一つのマップにまとめて表示します。</p>


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
                      <th>調査名</th>
                      <th>調査リンク</th>
                    </tr>
                  </thead>
                  <tbody>

                          <tr role="row">
                            <td>地図統合閲覧</td>
                            <td><button type="button" class="btn btn-info btn-flat" onclick="location.href='allmap.php'">閲覧ページへ</button></td>
                          </tr>

                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>
<?php if($user_label == 1){?>
        <div class="row">

          <div class="col-md-9">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true" style="">調査項目</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" style="" aria-expanded="false">調査説明</a></li>
                </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="activity">
                  <div style="display: <?php echo $settingAdminDisplay; ?>">
                    <form class=""  method="post">
                      <input type="text" class="form-control" id="inputName" placeholder="Name" name="communityID" value="<?php echo htmlspecialchars($pageID, ENT_QUOTES) ?>" style="display: none">
                      <!-- <button type="submit" class="btn btn-success btn-block btn-lg">特定生物分布調査実施</button> -->
<button type="button" name="button" class="btn btn-success btn-block btn-lg" onclick="location.href='debris_search.php'">漂着物調査実施</button>
<button type="button" name="button" class="btn btn-success btn-block btn-lg" onclick="location.href='activity.php'">活動報告調査実施</button>
<button type="button" name="button" class="btn btn-success btn-block btn-lg" onclick="location.href='media_search.php'">状況報告調査実施</button>
<!-- <button type="submit" class="btn btn-success btn-block btn-lg">地図統合実施</button> -->
<!-- <button type="submit" class="btn btn-success btn-block btn-lg">詳細報告調査実施</button> -->


                    </form>
                  </div>
                  <hr>

                  <!-- /.box-body -->

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                  <strong><i class="fa fa-user margin-r-5"></i> 各調査の説明</strong>
                  <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                      <div class="col-sm-6"></div>
                      <strong>活動報告機能</strong><br>
                      <p>団体や個人が行った清掃活動の状況を知りたい際に<br>
                      調査を作成することで実施できます</p>
                      <br><strong>漂着物調査機能
</strong><br><br>


                      <div class="col-sm-6"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">

                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->

                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <?php }?>


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

      $('#example3').DataTable({
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

   // マップ生成
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
      var layer = L.markerClusterGroup();
      for (var i = 0; i < ary.length; i++) {
        L.marker([ary[i][0], ary[i][1]]).bindPopup(array[i][3]).addTo(layer);
      }
      return layer;
    }

    // function clusterGenerate(ary) {
    //   var layer = L.markerClusterGroup();
    //   markers.addLayer(L.marker());
    //   map.addLayer(markers);
    // }

    //ヒートマップ生成関数
    // function heatGenerate(ary) {
    //   // 配列の3列目を削除
    //   for (var i = 0; i < ary.length; i++) {
    //     ary[i].splice(3, 1);
    //   }
    //   var layer = L.layerGroup();
    //   L.heatLayer(ary, {
    //     radius: 30,
    //     maxOpacity: 0.5
    //   }).addTo(layer);
    //   return layer;
    // }

    //マーカーグループ生成関数
    function controlGenerate(name, markerArray) {
      overlays[name] = markerGenerate(markerArray);
    //   overlays[name + '_heatmap'] = heatGenerate(markerArray);
    }

    // PHPで取得したデータを格納
    var data = <?php echo $report; ?>;

    <?php
if (empty($researchYear)) {
    echo 'var year = "ALL";';
} else {
    echo 'var year = "ALL";';
}
?>

    // MAP用の配列生成
    for(var i in data){
      array[i] = [];
      array[i][0] = data[i].lat;
      array[i][1] = data[i].lng;
      array[i][2] = data[i].group_name;
      array[i][3] = data[i].comment;
    }

    // Map生成　必ず最初に実行
    mapGenerate(array[0][0], array[0][1]);
    // マーカー生成
    controlGenerate(year, array);
    // 初期表示設定
    overlays[year].addTo(map);
    //コントローラーの追加
    L.control.layers(baseLayers, overlays).addTo(map);
  </script>
</body>

</html>


