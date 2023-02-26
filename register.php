<?php
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";

// サインアップボタンが押された場合
if (isset($_POST["signUp"])) {
    // ユーザIDの入力チェック
    if (empty($_POST["userName"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } elseif (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } elseif (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    } elseif (empty($_POST["mail"])) {
        $errorMessage = 'mailが未入力です。';
    }

    // 必須項目のチェック
    if (!empty($_POST["userName"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && !empty($_POST["mail"]) && $_POST["password"] === $_POST["password2"]) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["userName"];
        $password = $_POST["password"];
        $mail = $_POST["mail"];
        $age = $_POST["age"];
        $sex = $_POST["sex"];
        $address = $_POST["address"];

        // ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM user WHERE mail = :mail');
            $stmt->execute(array(':mail' => $mail));

            // メールアドレスの重複確認
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              // 認証失敗
              $errorMessage = 'すでに登録されているメールアドレスです。';

            } else {
              $stmt = $pdo->prepare("INSERT INTO user(name, pw, mail, age, gender, address, user_label) VALUES (:name, :pw, :mail, :age, :gender, :address, 0)");
              $stmt->execute(array(':name' => $username, ':pw' => password_hash($password, PASSWORD_DEFAULT), ':mail' => $mail, ':age' => $age, ':gender' => $sex, ':address' => $address));  // パスワードのハッシュ化を行いインサート
              $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

              // バッジ情報をインサート
              $stmt = $pdo->prepare("INSERT INTO badge(userID, badge1) VALUES (?, ?)");
              $stmt->execute(array($userid, 1));

              $_SESSION['message'] = '登録が完了しました。ログインを行ってください。';
              header("Location: login.php");  // ログイン画面へ遷移
              exit();
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage(); //エラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
    } elseif ($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
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
  <title>市民参加型調査支援システム | 新規登録</title>
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
  <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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
      <a href="index.html">調査支援システム</a>
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
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">アカウントを作成して調査を開始しましょう<br>*がついている項目の入力は必須です</p>
      <p class="text-yellow text-center">誕生年、性別、居住地は公開されません</p>
      <form id="loginForm" name="loginForm" action="" method="post">
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">名前（ユーザ名）*</label>
          </div>
          <div class="col-md-8">
            <div class="form-group has-warning">
              <input type="text" class="form-control require" id="inputPassword3" name="userName" placeholder="User Name">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">E-mail*</label>
          </div>
          <div class="col-md-8">
            <div class="form-group has-warning">
              <input type="Email" class="form-control require" id="inputPassword3" name="mail" placeholder="E-mail">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">パスワード*</label>
          </div>
          <div class="col-md-8">
            <div class="form-group has-warning">
              <input type="password" class="form-control require" id="inputPassword3" name="password" placeholder="Password">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">パスワード再入力*</label>
          </div>
          <div class="col-md-8">
            <div class="form-group has-warning">
              <input type="password" class="form-control require" id="inputPassword3" name="password2" placeholder="Password">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">誕生年</label>
          </div>
          <div class="col-md-8">
            <div class="form-group has-feedback">
              <select class="form-control" name="age">
              
              <option selected  value="2021">2021年</option>
                <option selected  value="2020">2020年</option>
                <option selected  value="2019">2019年</option>
                <option value="2018">2018年</option>
                <option value="2017">2017年</option>
                <option value="2016">2016年</option>
                <option value="2015">2015年</option>
                <option value="2014">2014年</option>
                <option value="2013">2013年</option>
                <option value="2012">2012年</option>
                <option value="2011">2011年</option>
                <option value="2010">2010年</option>
                <option value="2009">2009年</option>
                <option value="2008">2008年</option>
                <option value="2007">2007年</option>
                <option value="2006">2006年</option>
                <option value="2005">2005年</option>
                <option value="2004">2004年</option>
                <option value="2003">2003年</option>
                <option value="2002">2002年</option>
                <option value="2001">2001年</option>
                <option value="2000">2000年</option>
                <option value="1999">1999年</option>
                <option value="1998">1998年</option>
                <option value="1997">1997年</option>
                <option value="1996">1996年</option>
                <option value="1995">1995年</option>
                <option value="1994">1994年</option>
                <option value="1993">1993年</option>
                <option value="1992">1992年</option>
                <option value="1991">1991年</option>
                <option value="1990">1990年</option>
                <option value="1989">1989年</option>
                <option value="1988">1988年</option>
                <option value="1987">1987年</option>
                <option value="1986">1986年</option>
                <option value="1985">1985年</option>
                <option value="1984">1984年</option>
                <option value="1983">1983年</option>
                <option value="1982">1982年</option>
                <option value="1981">1981年</option>
                <option value="1980">1980年</option>
                <option value="1979">1979年</option>
                <option value="1978">1978年</option>
                <option value="1977">1977年</option>
                <option value="1976">1976年</option>
                <option value="1975">1975年</option>
                <option value="1974">1974年</option>
                <option value="1973">1973年</option>
                <option value="1972">1972年</option>
                <option value="1971">1971年</option>
                <option value="1970">1970年</option>
                <option value="1969">1969年</option>
                <option value="1968">1968年</option>
                <option value="1967">1967年</option>
                <option value="1966">1966年</option>
                <option value="1965">1965年</option>
                <option value="1964">1964年</option>
                <option value="1963">1963年</option>
                <option value="1962">1962年</option>
                <option value="1961">1961年</option>
                <option value="1960">1960年</option>
                <option value="1959">1959年</option>
                <option value="1958">1958年</option>
                <option value="1957">1957年</option>
                <option value="1956">1956年</option>
                <option value="1955">1955年</option>
                <option value="1954">1954年</option>
                <option value="1953">1953年</option>
                <option value="1952">1952年</option>
                <option value="1951">1951年</option>
                <option value="1950">1950年</option>
                <option value="1949">1949年</option>
                <option value="1948">1948年</option>
                <option value="1947">1947年</option>
                <option value="1946">1946年</option>
                <option value="1945">1945年</option>
                <option value="1944">1944年</option>
                <option value="1943">1943年</option>
                <option value="1942">1942年</option>
                <option value="1941">1941年</option>
                <option value="1940">1940年</option>
                <option value="1939">1939年</option>
                <option value="1938">1938年</option>
                <option value="1937">1937年</option>
                <option value="1936">1936年</option>
                <option value="1935">1935年</option>
                <option value="1934">1934年</option>
                <option value="1933">1933年</option>
                <option value="1932">1932年</option>
                <option value="1931">1931年</option>
                <option value="1930">1930年</option>
                <option value="1929">1929年</option>
                <option value="1928">1928年</option>
                <option value="1927">1927年</option>
                <option value="1926">1926年</option>
                <option value="1925">1925年</option>
                <option value="1924">1924年</option>
                <option value="1923">1923年</option>
                <option value="1922">1922年</option>
                <option value="1921">1921年</option>
                <option value="1920">1920年</option>
              </select>
              <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">性別</label>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <div class="row">
                <div class="col-md-5">
                  <label class="">
                    <div class="iradio_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                      <input type="radio" name="sex" value="male" class="minimal" style="position: absolute; opacity: 0;" checked>
                      <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                  </label>
                  男性
                </div>
                <div class="col-md-5 offset-2">
                  <label class="">
                    <div class="iradio_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                      <input type="radio" name="sex" value="female" class="minimal" style="position: absolute; opacity: 0;">
                      <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                  </label>
                  女性
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail3" class="control-label text-center">居住地</label>
          </div>
          <div class="col-md-8">
            <div class="form-group has-feedback">
              <select class="form-control select2 select2-hidden-accessible" name="address" style="width: 100%;" tabindex="-1" aria-hidden="true">
                <option selected value="">設定しない</option>
                <option>北海道</option>
                <option>青森県</option>
                <option>岩手県</option>
                <option>宮城県</option>
                <option>秋田県</option>
                <option>山形県</option>
                <option>福島県</option>
                <option>茨城県</option>
                <option>栃木県</option>
                <option>群馬県</option>
                <option>埼玉県</option>
                <option>千葉県</option>
                <option>東京都</option>
                <option>神奈川県</option>
                <option>新潟県</option>
                <option>富山県</option>
                <option>石川県</option>
                <option>福井県</option>
                <option>山梨県</option>
                <option>長野県</option>
                <option>岐阜県</option>
                <option>静岡県</option>
                <option>愛知県</option>
                <option>三重県</option>
                <option>滋賀県</option>
                <option>京都府</option>
                <option>大阪府</option>
                <option>兵庫県</option>
                <option>奈良県</option>
                <option>和歌山県</option>
                <option>鳥取県</option>
                <option>島根県</option>
                <option>岡山県</option>
                <option>広島県</option>
                <option>山口県</option>
                <option>徳島県</option>
                <option>香川県</option>
                <option>愛媛県</option>
                <option>高知県</option>
                <option>福岡県</option>
                <option>佐賀県</option>
                <option>長崎県</option>
                <option>熊本県</option>
                <option>大分県</option>
                <option>宮崎県</option>
                <option>鹿児島県</option>
                <option>沖縄県</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <button type="submit" name="signUp" class="btn btn-danger btn-block btn-flat">新規ユーザ登録</button>
            <div class="text-center">
              <a href="login.php">ログインページに戻る</a>
            </div>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.form -->
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
  <!-- bootstrap color picker -->
  <script src="bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
  <!-- bootstrap time picker -->
  <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <!-- SlimScroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!-- FastClick -->
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- Page script -->
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


      //Initialize Select2 Elements
      $('.select2').select2()

      //Datemask dd/mm/yyyy
      $('#datemask').inputmask('dd/mm/yyyy', {
        'placeholder': 'dd/mm/yyyy'
      })
      //Datemask2 mm/dd/yyyy
      $('#datemask2').inputmask('mm/dd/yyyy', {
        'placeholder': 'mm/dd/yyyy'
      })
      //Money Euro
      $('[data-mask]').inputmask()

      //Date range picker
      $('#reservation').daterangepicker()
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
