<?php
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名


// エラーメッセージの初期化
$errorMessage = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
  $message = "";
}

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // ユーザIDの入力チェック　バリデーションで実装予定
    if (empty($_POST["mail"])) {
        $errorMessage = 'ユーザーIDが未入力です。';
    } elseif (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["mail"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $mail = $_POST["mail"];

        // ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM user WHERE mail = :mail');
            $stmt->execute(array(':mail' => $mail));

            $password = $_POST["password"];

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // パスワードのハッシュを比較
                if (password_verify($password, $row['pw'])) {
                    // 認証成功なら、セッションIDを新規に発行する
                    session_regenerate_id(true);

                    // 入力したメールアドレスのユーザー名を取得
                    $mail = $row['mail'];
                    $sql = "SELECT * FROM user WHERE mail = '$mail'";  //入力したIDからユーザー名を取得
                    $stmt = $pdo->query($sql);
                    foreach ($stmt as $row) {
                        $row['userID'];  // ユーザー名
                    }

                    $userID = $row['userID'];

                    $_SESSION["ID"] = $userID;
                    $_SESSION["name"] = $row['name'];

                    // こっからバッジ取得情報更新処理
                    $badgeAry = array(1);

                    // コミュニティ所属状況の確認
                    $stmt = $pdo->prepare('SELECT * FROM community_member WHERE userID = ?');
                    $stmt->execute(array($userID));

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      array_push($badgeAry, 1);
                    } else {
                      array_push($badgeAry, NULL);
                    }

                    // 報告数の取得
                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM report WHERE userID = ?');
                    $stmt->execute(array($userID));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $reportCount = $row['COUNT(*)'];

                    // バッジ条件に合わせてバッジ情報をアップデート
                    if ($reportCount < 1) {
                      array_push($badgeAry, NULL, NULL, NULL, NULL);
                    } elseif ($reportCount < 10) {
                      array_push($badgeAry, 1, NULL, NULL, NULL);
                    } elseif ($reportCount < 30) {
                      array_push($badgeAry, 1, 1, NULL, NULL);
                    } elseif ($reportCount < 100) {
                      array_push($badgeAry, 1, 1, 1, NULL);
                    } elseif ($reportCount >= 100) {
                      array_push($badgeAry, 1, 1, 1, 1);
                    }

                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM validation WHERE userID = ?');
                    $stmt->execute(array($userID));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $valiCount = $row['COUNT(*)'];

                    if ($valiCount < 1) {
                      array_push($badgeAry, NULL, NULL, NULL, NULL);
                    } elseif ($valiCount < 10) {
                      array_push($badgeAry, 1, NULL, NULL, NULL);
                    } elseif ($valiCount < 30) {
                      array_push($badgeAry, 1, 1, NULL, NULL);
                    } elseif ($valiCount < 100) {
                      array_push($badgeAry, 1, 1, 1, NULL);
                    } elseif ($valiCount >= 100) {
                      array_push($badgeAry, 1, 1, 1, 1);
                    }

                    array_push($badgeAry, $userID);

                    // バッジ情報アップデート
                    $stmt = $pdo->prepare('UPDATE badge SET badge1 = ?, badge2 = ?, badge3 = ?, badge4 = ?, badge5 = ?, badge6 = ?, badge7 = ?, badge8 = ?, badge9 = ?, badge10 = ? WHERE userID = ?');
                    $stmt->execute($badgeAry);

                    header("Location: index.php");  // メイン画面へ遷移
                    //$errorMessage = $_SESSION["NAME"] . $row['pw'];  //デバック用
                    exit();  // 処理終了
                } else {
                    // 認証失敗
                    $errorMessage = 'メールアドレスあるいはパスワードに誤りがあります。';
                }
            } else {
                // 該当データなし
                $errorMessage = 'メールアドレスあるいはパスワードに誤りがあります。';
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラーが発生しました。';
            // $errorMessage = $sql;
            $e->getMessage(); //でエラー内容を参照可能（デバッグ時のみ表示）
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
  <title>市民参加型調査支援システム | ログインページ</title>
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
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">

  <div class="login-box">
    <div class="login-logo">
      <a href="index.php">調査支援システム</a>
    </div>
    <?php
    if (!empty($errorMessage) || !empty($message)) {
        $errorMessage = htmlspecialchars($errorMessage, ENT_QUOTES);
        $message = htmlspecialchars($message, ENT_QUOTES);
        echo <<<EOM
      <div class="callout callout-warning">
      <h4>MESSAGE</h4>
      <p>$errorMessage</p>
      <p>$message</p>
      </div>
EOM;
    }
    ?>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">アカウントを持っていない方は新規ユーザ登録からアカウントを作成してください。</p>

      <form action="" id="loginForm" name="loginForm" method="post">
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">E-mail</label>
          </div>
          <!-- /.col -->
          <div class="col-md-8">
            <div class="form-group has-warning">
              <input type="text" class="form-control require" id="mail" name="mail" placeholder="E-mail">
              <!-- <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-md-4">
            <label for="inputmail3" class="control-label text-center">パスワード</label>
          </div>
          <!-- /.col -->
          <div class="col-md-8">
            <div class="form-group has-warning">
              <input type="password" class="form-control require" id="password" name="password" placeholder="Password">
              <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat" id="login" name="login" value="ログイン">ログイン</button>
          </div>
          <div class="text-center">または</div>
          <!-- /.col -->
          <div class="col-xs-12">
            <div class="text-center">
              <a href="register.php">新規ユーザ登録</a>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </form>

    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function() {
      // フォーム入力チェック
      $('input.require').on('blur', function() {
        let error;
        let value = $(this).val();
        if (value == "") {
          error = true;
        } else if (!value.match(/[^\s\t]/)) {
          error = true;
        }

        if (error) {
          // エラー時の処理
          if (!($(this).parent().hasClass('has-warning'))) {
            $(this).parent().addClass('has-warning');
            $(this).parent().removeClass('has-success');
          }

          // エラーで、エラーメッセージがなかったら
          if (!$(this).nextAll('span.help-block').length) {
            //メッセージを後ろに追加
            $(this).after('<span class="help-block">この項目の入力は必須です。</span>');
          }
        } else {
          // 正常時の処理
          if ($(this).parent().hasClass('has-warning')) {
            $(this).parent().removeClass('has-warning');
            $(this).parent().addClass('has-success');
          }

          // エラーじゃないのにメッセージがあったら
          if ($(this).nextAll('span.help-block').length) {
            // エラーメッセージを消す
            $(this).nextAll('span.help-block').remove();
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

      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
    });
  </script>
</body>

</html>
