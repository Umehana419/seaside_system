<?php
// session_start();

// $db['host'] = "localhost";  // DBサーバのURL
// $db['user'] = "g031p015";  // ユーザー名
// $db['pass'] = "g031p015PW";  // ユーザー名のパスワード
// $db['dbname'] = "g031p015";  // データベース名

// // ユーザ名の初期化
// $loginName = "ゲストユーザ";
// // エラーメッセージの初期化
// $errorMessage = "";
// // 表示設定の初期化
// $settingDisplay = "";
// // 管理者機能の表示設定の初期化
// $settingAdminDisplay = "none";
// $login = false;
// jjjjj
//
// // パラメータのチェック
// if (isset($_GET['id'])) {
//     // パラメータの格納
//     $pageID = $_GET['id'];
//     // 認証
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     // エラー処理
//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // コミュニティとユーザ情報を取得する呪文
//         $stmt = $pdo->prepare('SELECT community_member.communityID, community.name as communityName, community.overview, community.point, community.tag, community.policy, user.userID, user.name as userName, community_member.date as joinDate, community_member.userRole FROM community_member LEFT JOIN community ON community_member.communityID = community.communityID LEFT JOIN user ON community_member.userID = user.userID WHERE community_member.communityID = ?');
//         $stmt->execute(array($pageID));
//         // データ取得確認
//         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $communityName = $row['communityName'];
//             $communityID = $row['communityID'];
//             $communityOverview = $row['overview'];
//             $point = $row['point'];
//             $tag = $row['tag'];
//             $policy = $row['policy'];

//             $communityMember = array(); // メンバー情報入れる変数
//             $member_count = 0; // メンバー数

//             // メンバー情報格納（ifで一行目をFETCH済み）
//             do {
//                 $communityMember[] = array('id' => $row['userID'], 'name' => $row['userName'], 'role' => $row['userRole'], 'date' => $row['joinDate']);
//                 $member_count++;
//             } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));

//             // 調査情報取得
//             $stmt = $pdo->prepare('SELECT * FROM research WHERE communityID = ?');
//             $stmt->execute(array($pageID));

//             $research = array(); // 調査情報入れる変数
//             $research_count = 0; // 調査数

//             // 調査情報格納
//             while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//                 $research[] = array('researchName' => $row['name'], 'researchID' => $row['researchID'], 'researchStart' => $row['researchStart'], 'researchEnd' => $row['researchEnd'], 'researchOverview' => $row['researchOverview'], 'private' => $row['private']);
//                 $research_count++;
//             }
//         } else {
//             $_SESSION['message'] = "この団体は存在しません。";
//             header("Location: index.php");  // メイン画面へ遷移
//             exit();
//         }
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラーが発生しました。';
//         // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
//         // echo $e->getMessage();
//     }
// } else {
//     $_SESSION['message'] = "この団体は存在しません。1";
//     header("Location: index.php");  // メイン画面へ遷移
//     exit();
// }

// // ログイン状態チェック
// if (isset($_SESSION["ID"])) {
//     // ID、ユーザ名の格納
//     $loginID = $_SESSION['ID'];
//     $loginName = $_SESSION['name'];
//     $login = true;

//     // 認証
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     // エラー処理
//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // ログインユーザのコミュニティ情報取得
//         $stmt = $pdo->prepare('SELECT * FROM community_member WHERE userID = ? && communityID = ?');
//         $stmt->execute(array($loginID, $pageID));

//         // ユーザがコミュニティに所属しているか確認
//         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $settingDisplay = "none";
//             if (strcmp($row['userRole'], "admin") == 0) {
//                 $settingAdminDisplay = "";
//             }
//         }
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラーが発生しました。';
//         // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
//         // echo $e->getMessage();
//     }
// }

// // コミュニティ参加ボタンが押されたとき
// if (isset($_POST["join"])) {
//     if (isset($_SESSION["ID"])) {
//         // 認証
//         $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//         // エラー処理
//         try {
//             // 今日ってなんの日
//             $date = date("Y/m/d");
//             $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//             // メンバーテーブルにユーザ情報を追加
//             $stmt = $pdo->prepare("INSERT INTO community_member(communityID, userID, userRole, date) VALUES (?, ?, ?, ?)");
//             $stmt->execute(array($communityID, $loginID, "general", $date));

//             // 更新処理
//             header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $pageID);
//         } catch (PDOException $e) {
//             $errorMessage = 'データベースエラー';
//             // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
//       // echo $e->getMessage();
//         }
//     } else {
//         $_SESSION['message'] = "コミュニティに参加するにはログインが必要です。";
//         header("Location: login.php");  // ログイン画面へ遷移
//         exit();
//     }
// }

// // 検証者認定ボタンが押されたとき
// if (isset($_POST['changeRole'])) {
//     // 変更するユーザIDを格納
//     $changeUserID = $_POST['userID'];

//     // 認証
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     // エラー処理
//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // コミュニティテーブルの情報を更新
//         $stmt = $pdo->prepare("UPDATE community_member set userRole = ? WHERE userID = ?");
//         $stmt->execute(array("verifier", $changeUserID));

//         // 更新処理
//         header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $pageID);
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラー';
//         // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
//         // echo $e->getMessage();
//     }
// }

// // 除名ボタンが押されたとき
// if (isset($_POST['deleteMember'])) {
//     // 変更するユーザIDを格納
//     $deleteMemberID = $_POST['userID'];

//     // 認証
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     // エラー処理
//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // コミュニティテーブルの情報を更新
//         $stmt = $pdo->prepare("DELETE FROM community_member WHERE (userID = ? AND communityID = ?)");
//         $stmt->execute(array($deleteMemberID, $pageID));

//         // 更新処理
//         header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $pageID);
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラー';
//         // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
//         // echo $e->getMessage();
//     }
// }

// // 設定更新ボタンが押されたとき
// if (isset($_POST['update'])) {
//     $updateName = $_POST['name'];
//     $updateOverview = $_POST['overview'];
//     $updateTag = $_POST['tag'];
//     $updatePoint = $_POST['point'];

//     // 認証
//     $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

//     // エラー処理
//     try {
//         $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//         // コミュニティテーブルの情報を更新
//         $stmt = $pdo->prepare("UPDATE community set name = ?, overview = ?, tag = ?, point = ? WHERE communityID = ?");
//         $stmt->execute(array($updateName, $updateOverview, $updateTag, $updatePoint, $pageID));

//         // 更新処理
//         header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $pageID);
//     } catch (PDOException $e) {
//         $errorMessage = 'データベースエラー';
//         // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
//         // echo $e->getMessage();
//     }
// }

// // 詳細報告が押された時
// if (isset($_POST['']))

// try{
//   $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
//   $sql = "INSERT INTO syousai (
//      community_id, name, url, start, end, type
//   ) VALUES (
//     community_id, '詳細調査', 'url', '1', '12'
// , '通年'  )";
// $res = $dbh->query($sql);
// header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $pageID);
//     } catch (PDOException $e) 
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
        <h3>
          コミュニティプロフィール
          <small>User Profile</small>
        </h3>

        <div class="box box-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-aqua-active">
            <h3 class="widget-user-username text-center"><?php echo htmlspecialchars($communityName, ENT_QUOTES)?></h3>
            <h5 class="widget-user-desc text-center"><?php echo htmlspecialchars($tag, ENT_QUOTES)?></h5>
          </div>
          <!--
          <div class="widget-user-image">
            <img class="img-circle" src="dist/img/user1-128x128.jpg" alt="User Avatar">
          </div>
          -->
          <div class="box-footer">
            <div class="row">
              <div class="col-sm-6 border-right">
                <div class="description-block">
                  <h5 class="description-header"><?php echo $member_count; ?></h5>
                  <span class="description-text">MEMBER</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-6 border-right">
                <div class="description-block">
                  <h5 class="description-header"><?php echo $research_count; ?></h5>
                  <span class="description-text">RESEARCH</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <!-- About Me Box -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">団体紹介</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <strong><i class="fa fa-book margin-r-5"></i> 団体概要</strong>

                <p class="text-muted">
                  <?php echo htmlspecialchars($communityOverview, ENT_QUOTES);?>
                </p>

                <hr>

                <strong><i class="fa fa-map-marker margin-r-5"></i> 主な活動地点</strong>

                <p class="text-muted"><?php echo htmlspecialchars($point, ENT_QUOTES);?></p>

                <hr>

                <strong><i class="fa fa-file-text-o margin-r-5"></i> その他</strong>

                <p class="text-muted"><?php echo htmlspecialchars($policy, ENT_QUOTES); ?></p>
              </div>
              <!-- /.box-body -->
            </div>

            <form action="" method="post" style="display: <?php echo $settingDisplay; ?>">
              <button type="button" class="btn btn-block btn-success btn-lg" data-toggle="modal" data-target="#modal-default">このコミュニティに参加する</button>

              <div class="modal fade" id="modal-default" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                      <h4 class="modal-title">このコミュニティに参加しますか？</h4>
                    </div>
                    <div class="modal-body">
                      <h5>参加するにはコミュニティが設定したメンバー除名ポリシーに従う必要があります。</h5>
                      <p><?php echo htmlspecialchars($policy, ENT_QUOTES)?></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">キャンセル</button>
                      <button type="submit" class="btn btn-primary" name="join">参加する</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
            </form>
          </div>


          <!-- /.col -->
          <div class="col-md-9">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true" style="">アクティビティ</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" style="" aria-expanded="false">メンバー</a></li>
                <li class="" style="display: <?php echo $settingAdminDisplay; ?>"><a href="#settings" data-toggle="tab" style="" aria-expanded="false">団体設定</a></li>
                <li class="" style="display: "><a href="#discribe" data-toggle="tab" style="" aria-expanded="false">調査機能説明</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="activity">
                  <div style="display: <?php echo $settingAdminDisplay; ?>">
                    <!-- <form class="" action="newresearch-general.php" method="post"> --> -->
                      <input type="text" class="form-control" id="inputName" placeholder="Name" name="communityID" value="<?php echo htmlspecialchars($pageID, ENT_QUOTES)?>" style="display: none">
                      <button type="submit" onclick="location.href='./newresearch-general.php'" class="btn btn-success btn-block btn-lg">新規調査実施</button>
                      <button type="submit" onclick="location.href='./newresearch-general.php'" class="btn btn-success btn-block btn-lg">漂着物調査</button>
                      <button type="submit" onclick="location.href='./newresearch-general.php'" class="btn btn-success btn-block btn-lg">活動報告</button>
                      <button type="submit" onclick="location.href='./newresearch-general.php'" class="btn btn-success btn-block btn-lg">現状報告</button>
                      <button type="submit" onclick="location.href='./newresearch-general.php'" class="btn btn-success btn-block btn-lg">地図統合作成</button>
                      <button type="submit"  class="btn btn-success btn-block btn-lg">詳細報告</button>
                    </form>
                  </div>
                  <hr>
                  <strong><i class="fa fa-search margin-r-5"></i> 実施している調査一覧</strong>
                  <div class="box-body table-responsive">
                    <table class="table table-hover">
                      <tbody>
                        <tr>
                          <th>ID</th>
                          <th>調査期間</th>
                          <th>ステータス</th>
                          <th>調査名</th>
                          <th>調査リンク</th>
                        </tr>
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

                            if ($val['private'] != 1) {
                              $status = "非公開";
                              $statusCollor = "warning";
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
                  <strong><i class="fa fa-user margin-r-5"></i> メンバー一覧</strong>
                  <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                      <div class="col-sm-6"></div>
                      <div class="col-sm-6"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                          <thead>
                            <tr role="row">
                              <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column descending" aria-sort="ascending">ID</th>
                              <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">メンバー名</th>
                              <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">役職</th>
                              <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">参加日</th>
                              <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">報告数</th>
                              <th class="" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="display: <?php echo $settingAdminDisplay; ?>">検証者認定</th>
                              <th style="display: <?php echo $settingAdminDisplay; ?>">除名</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            foreach ($communityMember as $key => $val) {
                                $role = "";
                                $cahngeRoleDisabled = "";
                                $deleteMemberDisabled = "";
                                if (strcmp($val['role'], "admin") == 0) {
                                    $role = "管理者";
                                    $cahngeRoleDisabled = "disabled";
                                    $deleteMemberDisabled = "disabled";
                                } elseif (strcmp($val['role'], "verifier") == 0) {
                                    $role = "検証者";
                                    $cahngeRoleDisabled = "disabled";
                                } else {
                                    $role = "一般";
                                }
                                echo <<<EOM
                                <tr role="row">
                                <form action="" method="post">
                                  <input type="hidden" name="userID" value="{$val['id']}">
                                  <td class="sorting_1">{$val['id']}</td>
                                  <td class="">{$val['name']}</td>
                                  <td class="">{$role}</td>
                                  <td class="">{$val['date']}</td>
                                  <td>10</td>
                                  <td style="display: {$settingAdminDisplay}"><button type="submit" class="btn btn-block btn-primary btn-sm" name="changeRole" {$cahngeRoleDisabled}>検証者認定</button></td>
                                  <td style="display: {$settingAdminDisplay}"><button type="submit" class="btn btn-block btn-gray btn-xs" name="deleteMember" {$deleteMemberDisabled}>除名</button></td>
                                </form>
                                </tr>
EOM;
                            }
                            ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th rowspan="1" colspan="1">ID</th>
                              <th rowspan="1" colspan="1">メンバー名</th>
                              <th rowspan="1" colspan="1">役職</th>
                              <th rowspan="1" colspan="1">参加日</th>
                              <th rowspan="1" colspan="1" style="display: <?php echo $settingAdminDisplay; ?>">報告数</th>
                              <th rowspan="1" colspan="1" style="display: <?php echo $settingAdminDisplay; ?>">検証者認定</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="settings" style="display: <?php echo $settingAdminDisplay; ?>">
                  <strong><i class="fa fa-lock margin-r-5"></i> 団体設定を変更する</strong>
                  <form class="form-horizontal" action="" method="POST">
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 control-label">コミュニティ名</label>

                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" value="<?php echo htmlspecialchars($communityName, ENT_QUOTES)?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputSkills" class="col-sm-2 control-label">コミュニティ概要</label>

                      <div class="col-sm-10">
                        <textarea class="form-control" rows="3" placeholder="Enter ..." name="overview"><?php echo htmlspecialchars($communityOverview, ENT_QUOTES)?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputSkills" class="col-sm-2 control-label">登録タグ</label>

                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputSkills" placeholder="TAG" name="tag" value="<?php echo htmlspecialchars($tag, ENT_QUOTES)?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputSkills" class="col-sm-2 control-label">主な活動地点</label>

                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputSkills" placeholder="point" name="point" value="<?php echo htmlspecialchars($point, ENT_QUOTES)?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputSkills" class="col-sm-2 control-label">Twitter連携</label>

                      <div class="col-sm-10">
                        <a class="btn btn-block btn-social btn-twitter">
                          <i class="fa fa-twitter"></i> Sign in with Twitter</a>
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
  <script>
    $(function() {
      $('#example1').DataTable()
      $('#example2').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false
      })
    })
  </script>
</body>

</html>
