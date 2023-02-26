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
$errorMessageList = array();
$login = false;

// パラメータの確認
if (isset($_GET['id'])) {
  $activityID = $_GET['id'];
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

   try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        // 調査情報の取得
        $stmt = $pdo->prepare('SELECT * FROM activity WHERE activityID = ?');
        $stmt->execute(array($activityID));

        // 取得確認
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $month = (int)date("m");
            // 期間内か確認
          if ($row['researchStart'] <= $row['researchEnd']) {
               if (($row['researchStart'] <= $month) && ($month <= $row['researchEnd'])) {
                    $activ_name = $row['activ_name'];
                    $activ_details = $row['activ_details'];
                    $start = $row['researchStart'];
                    $end = $row['researchEnd'];
                    $activ_target = $row['activ_target'];
		                $activ_overview = $row['activ_overview'];
              } else {
                   $_SESSION['message'] = "この調査は期間外のため報告できません。";
                   header("Location: activ_research-top.php?id=$activityID.php");  // 調査画面へ遷移
                   exit();
              }
           } else {
              if (($row['researchStart'] <= $month) && ($month >= $row['researchEnd'])) {
                  $activ_name = $row['activ_name'];
                   $activ_details = $row['activ_details'];
                   $start = $row['researchStart'];
                   $end = $row['researchEnd'];
                   $activ_target = $row['activ_target'];
		    $activ_overview = $row['activ_overview'];
               } else {
                   $_SESSION['message'] = "この調査は期間外のため報告できません。";
                   header("Location: activ_research-top.php?id=$activityID.php");  // 調査画面へ遷移
                   exit();
               }
           }
      } else {
           $_SESSION['message'] = "この調査は存在しません。";
           header("Location: activ_research-top.php?id=$activityID.php");  // 調査画面へ遷移
           exit();
       }
  } catch (PDOException $e) {
       $errorMessage = 'データベースエラーが発生しました。';
      //  $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
      //  echo $e->getMessage();
  }
} else {
   $_SESSION['message'] = "この調査は存在しません。";
   header("Location: index.php");  // メイン画面へ遷移
   exit();
}

// 登録ボタンが押されたら
if (isset($_POST["regist"])) {
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
           $_SESSION['lng'] = htmlspecialchars($_POST['lng'], ENT_QUOTES);
            $_SESSION['lat'] = htmlspecialchars($_POST['lat'], ENT_QUOTES);
            $_SESSION['group_name'] = htmlspecialchars($_POST['group_name'], ENT_QUOTES);
            $_SESSION['people_num'] = htmlspecialchars($_POST['people_num'], ENT_QUOTES);
            $_SESSION['comment'] = htmlspecialchars($_POST['comment'], ENT_QUOTES);
            $_SESSION['user_people'] = htmlspecialchars($_POST['user_people'], ENT_QUOTES);
            $_SESSION['date'] = htmlspecialchars($_POST['date'], ENT_QUOTES);
   
	$lng = $_SESSION['lng'];
$lat = $_SESSION['lat'];
$group_name = $_SESSION['group_name'];
$people_num = $_SESSION['people_num'];
$comment = $_SESSION['comment'];
$user_people = $_SESSION['user_people'];
$date = $_SESSION['date'];
if(empty($lng)){
$errorMessageList[] = "経緯を入力してください。";
}
if(empty($lat)){
  $errorMessageList[] = "緯度を入力してください。";
  }
  if(empty($group_name)){
    $errorMessageList[] = "団体名を入力してください。";
    }
    if(empty($people_num)){
      $errorMessageList[] = "参加人数を入力してください。";
      }
      if(empty($comment)){
        $errorMessageList[] = "コメントを入力してください。";
        }
        if(empty($user_people)){
          $errorMessageList[] = "報告者を入力してください。";
          }
          
// エラー処理
//    try {

//        $directory = 'img/research/' . $targetPicture;

//        if (!copy('tmp/research/' . $targetPicture, $directory)) {
//            echo "ファイルコピーエラー";
 //           exit();
//        }


// echo $id;

// header("Location: activ_media.php?id=$id");






    // $_SESSION['upfile'] = htmlspecialchars($_POST['upfile'], ENT_QUOTES);
    // $upfile = $_SESSION['upfile'];
    // echo "$upfile";
    
    if (is_uploaded_file($_FILES[upfile1]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile1]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile1]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile1"]["name"], 0644);
      // echo $_FILES[upfile1]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile1"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile2]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile2]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile2]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile2]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile2"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile3]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile3]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile3]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile3]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile3"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile4]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile4]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile4]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile4]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile4"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile5]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile5]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile5]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile5]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile5"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile6]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile6]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile6]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile6]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile6"]["name"];
    }else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile7]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile7]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile7]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile7]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile7"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile8]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile8]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile8]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile8]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile8"]["name"];
    } else {
      $errorMessageList[] = "ファイルをアップロードできません。";
    }
  }
    if (is_uploaded_file($_FILES[upfile9]["tmp_name"])) {
      if (move_uploaded_file ($_FILES[upfile9]["tmp_name"], "files/" .date("Ymd-His") . $_FILES[upfile9]["name"])) {
     // chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
      // echo $_FILES[upfile9]["name"] . "をアップロードしました。";
      // echo "" .date("Ymd-His") . $_FILES["upfile9"]["name"];
    } 
  else {
    $errorMessageList[] = "ファイルをアップロードできません。";
    }
    } else {
      $errorMessageList[] = "ファイルが選択されていません。";
    }


    // エラーメッセージがなければ実行
    if(empty($errorMessageList)){
  $media1 = "files/" .date("Ymd-His") . $_FILES[upfile1]["name"];
  $media2 = "files/" .date("Ymd-His") . $_FILES[upfile2]["name"];
  $media3 = "files/" .date("Ymd-His") . $_FILES[upfile3]["name"];
  $media4 = "files/" .date("Ymd-His") . $_FILES[upfile4]["name"];
  $media5 = "files/" .date("Ymd-His") . $_FILES[upfile5]["name"];
  $media6 = "files/" .date("Ymd-His") . $_FILES[upfile6]["name"];
  $media7 = "files/" .date("Ymd-His") . $_FILES[upfile7]["name"];
  $media8 = "files/" .date("Ymd-His") . $_FILES[upfile8]["name"];
  $media9 = "files/" .date("Ymd-His") . $_FILES[upfile9]["name"];

  try {
  $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
  // 基本設定テーブル（activ_report）にインサート
  $stmt = $pdo->prepare("INSERT INTO activ_report(activityID, userID, lng, lat, date, typeID, group_name, people_num, comment, user_people) VALUES (?,1,?,?,?,1,?,?,?,?)");
  $stmt->execute(array($activityID, $lng, $lat, $date, $group_name, $people_num, $comment, $user_people));
  $id = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$idに入れる
  $activ_reID = $id;

  // 基本設定テーブル（activ_media）にインサート
    $stmt = $pdo->prepare("INSERT INTO activ_media(activ_reID, media_be1, media_be2, media_be3, media_now1, media_now2, media_now3, media_af1, media_af2, media_af3) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute(array($activ_reID, $media1, $media2, $media3, $media4, $media5, $media6, $media7, $media8, $media9));
    // $id = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$idに入れる
  
    // indexに画面遷移
  header("Location: index.php");

  // DB接続エラーの場合
  }catch (PDOException $e) {
     $errorMessage = 'データベースエラーが発生しました。';
 }

          } else {
            // エラーメッセージを表示
            $error;
            foreach($errorMessageList as $key => $value){
              $error .= $value." ";
            }
            $errorMessage = $error;
            // $errorMg .= "<script type='text/javascript'>alert('". $error. "');</script>";
            // echo $errorMg;
          }
// $_SESSION['img1'] = htmlspecialchars($_POST['img1'], ENT_QUOTES);
// $_SESSION['img2'] = htmlspecialchars($_POST['img2'], ENT_QUOTES);
// $_SESSION['img3'] = htmlspecialchars($_POST['img3'], ENT_QUOTES);
// if (is_uploaded_file($_FILES["img1"]["tmp_name"])) {
// 	if (move_uploaded_file ($_FILES["img1"]["tmp_name"], "files/" .date("Ymd-His") . $_FILES["img1"]["name"])) {
// 	chmod("files/" . date("Ymd-His") . $_FILES["img1"]["name"], 0644);
// 	echo $_FILES["img1"]["name"] . "をアップロードしました。";
// } else {
// 	echo "ファイルをアップロードできません。";
// }
// } else {
// 	echo "ファイルが選択されていません。";
// }
// if (is_uploaded_file($_FILES["img2"]["tmp_name"])) {
// 	if (move_uploaded_file ($_FILES["img2"]["tmp_name"], "files/" .date("Ymd-His") . $_FILES["img2"]["name"])) {
// 	chmod("files/" . date("Ymd-His") . $_FILES["img2"]["name"], 0644);
// 	echo $_FILES["img2"]["name"] . "をアップロードしました。";
// } else {
// 	echo "ファイルをアップロードできません。";
// }
// } else {
// 	echo "ファイルが選択されていません。";
// }

// if (is_uploaded_file($_FILES["img3"]["tmp_name"])) {
// 	if (move_uploaded_file ($_FILES["img3"]["tmp_name"], "files/" .date("Ymd-His") . $_FILES["img3"]["name"])) {
// 	chmod("files/" . date("Ymd-His") . $_FILES["img3"]["name"], 0644);
// 	echo $_FILES["img3"]["name"] . "をアップロードしました。";
// } else {
// 	echo "ファイルをアップロードできません。";
// }
// } else {
// 	echo "ファイルが選択されていません。";
// }

 
                // for ($i=1; $i <= 3; $i++) {
                //   $stmt = $pdo->prepare("INSERT INTO activ_media(activ_reID, media_be1, media_now1, media_af1) VALUES (?,?,0,0");
                //     if (isset($_FILES["img"."$i"]["tmp_name"])) {
                //         $directory = 'img/activ_media/id' . $id . '-' . $i . '.jpg';
                //         if (move_uploaded_file($_FILES["img"."$i"]["tmp_name"], $directory)) {
                //             $stmt->execute(array($id, $directory));
                //         } else {
                //             throw new Exception('画像ファイルアップロードエラー！');
                //         }
                //     }
                // }

                // $stmt = $pdo->prepare("INSERT INTO activ_media(activ_reID, media_be1, media_now1, media_af1) VALUES (?,0,?,0");
 
                // for ($i=4; $i <= 6; $i++) {
                //     if (isset($_FILES["img"."$i"]["tmp_name"])) {
                //         $directory = 'img/activ_media/id' . $id . '-' . $i . '.jpg';
                //         if (move_uploaded_file($_FILES["img"."$i"]["tmp_name"], $directory)) {
                //             $stmt->execute(array($id, $directory));
                //         } else {
                //             throw new Exception('画像ファイルアップロードエラー！');
                //         }
                //     }
                // }

                // $stmt = $pdo->prepare("INSERT INTO activ_media(activ_reID, media_be1, media_now1, media_af1) VALUES (?,0,0,?");
 
                // for ($i=7; $i <= 9; $i++) {
                //     if (isset($_FILES["img"."$i"]["tmp_name"])) {
                //         $directory = 'img/activ_media/id' . $id . '-' . $i . '.jpg';
                //         if (move_uploaded_file($_FILES["img"."$i"]["tmp_name"], $directory)) {
                //             $stmt->execute(array($id, $directory));
                //         } else {
                //             throw new Exception('画像ファイルアップロードエラー！');
                //         }
                //     }
                // }
// $stmt = $pdo->prepare("INSERT INTO activ_media(activ_reID, media_be1, media_be2, media_be3, media_now1, media_now2, media_now3, media_af1, media_af2, media_af3) VALUES (?,?,?,?,?,?,?,?,?,?");
//         $stmt->execute(array($id, $img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8, $img9));
// echo $img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8, $img9;
//}
}

// if (isset($_POST["regist"])) {
//   if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
//     if (move_uploaded_file ($_FILES["upfile"]["tmp_name"], "files/" .date("Ymd-His") . $_FILES["upfile"]["name"])) {
//     chmod("files/" . date("Ymd-His") . $_FILES["upfile"]["name"], 0644);
//     echo $_FILES["upfile"]["name"] . "をアップロードしました。";
//   } else {
//     echo "ファイルをアップロードできません。";
//   }
//   } else {
//     echo "ファイルが選択されていません。";
//   }
// }
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-154120714-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
                    <!-- <li><a href="mypage.php?user=<?php echo $loginID; ?>"><i class="fa fa-laptop"></i><span>マイページ</span></a></li> -->
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
                        <form action="" id="form" method="post" enctype="multipart/form-data">
                            <input type="text" name="activityID" value="<?php echo $activityID; ?>" hidden>
                            <input type="text" name="loginID" value="<?php echo $loginID; ?>" hidden>
                            <label>行った場所</label>
                            <small>　行った場所をGPSを用いて入力します。GPSを有効にして「位置情報を取得」を押してください。</small>
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input id="target1" type="text" name="lng" class="form-control pull-left" value="">
                                    <input id="target2" type="text" name="lat" class="form-control pull-left" value="">
                                </div>
                                <button type="button" class="btn btn-block btn-info btn-md margin"
                                    onclick="getGeolocation()">位置情報を取得</button>
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
                                    <input type="text" name="date" class="form-control pull-right"
                                        value="<?php echo date("Y/m/d"); ?>">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->



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

                                    <label>投稿者名</label>
                                    <small>　投稿した方のお名前かニックネームを入力して下さい。</small>
                                    <div class="form-group has-warning">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right require"
                                                name="user_people">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                    <!-- /.box-body -->

                                </div>
                                <h2>添付画像の説明</h2>
                                <p>海岸を清掃する際の活動前後等の活動の様子や海岸漂着物の写真を添付して下さい</p>
                                <p>サンプルとして以下に画像を置いています。参考になるにして下さい<br>
                                    (1枚目：活動前の様子、２枚目：活動中の様子、３枚目：活動後の様子)</p>
                                <p>※アップする画像は（jpg、jpeg、PNG）でアップロードして下さい</p>
                                <img src="img_files/activity1.jpeg" width="300" height="200">
                                <img src="img_files/activity2.jpeg" width="300" height="200">
                                <img src="img_files/activity3.jpeg" width="300" height="200">

                                <h3>活動前の様子</h3>
                                <label>画像のアップロード</label>
                                <input type="file" name="upfile1"><br>
                                <!-- <label >画像のアップロード</label> -->
                                <input type="file" name="upfile2"><br>
                                <!-- <label >画像のアップロード</label> -->
                                <input type="file" name="upfile3"><br>
                                <h3>活動中の様子</h2>
                                    <label>画像のアップロード</label>
                                    <input type="file" name="upfile4"><br>
                                    <!-- <label >画像のアップロード</label> -->
                                    <input type="file" name="upfile5"><br>
                                    <!-- <label >画像のアップロード</label> -->
                                    <input type="file" name="upfile6"><br>
                                    <h3>活動後の様子</h3>
                                    <label>画像のアップロード</label>
                                    <input type="file" name="upfile7"><br>
                                    <!-- <label >画像のアップロード</label> -->
                                    <input type="file" name="upfile8"><br>
                                    <!-- <label >画像のアップロード</label> -->
                                    <input type="file" name="upfile9"><br>
                                    <button type="submit" name="regist"
                                        class="btn btn-block btn-success btn-lg">調査結果を報告する</button>
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
            $('#' + name).attr("src", fr.result);
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
            if ($("#img" + i).attr("src")) {
                //imgタグに表示した画像をimageオブジェクトとして取得
                var image = new Image();
                image.src = $("#img" + i).attr("src");

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
                var canvas = $("#canvas" + i);
                var ctx = canvas[0].getContext("2d");
                $("#canvas" + i).attr("width", w);
                $("#canvas" + i).attr("height", h);
                ctx.drawImage(image, 0, 0, w, h);

                //canvasに描画したデータを取得
                var canvasImage = $("#canvas" + i).get(0);

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

                formData.append("img" + i, uploadBlob);
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