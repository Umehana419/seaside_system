<?php
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031p015";  // ユーザー名
$db['pass'] = "g031p015PW";  // ユーザー名のパスワード
$db['dbname'] = "g031p015";  // データベース名
?>

<?php
// サインアップボタンが押された場合
if (isset($_POST["newActivity"])) {
    // ユーザIDの入力チェック
    if (empty($_POST["activ_name"])) {  // 値が空のとき
        $errorMessage = '調査名が未入力です。';
    } elseif (empty($_POST["activ_target"])) {
        $errorMessage = '対象名が未入力です。';
    } else {
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        
           
        // 必須項目のチェック
        if (!empty($_POST["activ_name"]) && !empty($_POST["activ_target"])) {
            // 入力したユーザIDとパスワードを格納
            $_SESSION['activ_name'] = htmlspecialchars($_POST['activ_name'], ENT_QUOTES);
            $_SESSION['start'] = htmlspecialchars($_POST['start'], ENT_QUOTES);
            $_SESSION['end'] = htmlspecialchars($_POST['end'], ENT_QUOTES);
            $_SESSION['activ_details'] = htmlspecialchars($_POST['activ_details'], ENT_QUOTES);
            $_SESSION['activ_target'] = htmlspecialchars($_POST['activ_target'], ENT_QUOTES);
            $_SESSION['activ_overview'] = htmlspecialchars($_POST['activ_overview'], ENT_QUOTES);
         
            
            // $errorMessage = $_SESSION['researchName'];
            header("Location: activ_confirm2.php");
            //var_dump($_FILES);
            exit;
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
  <h3>
          状況報告調査作成
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

        <form action="" enctype="multipart/form-data" method="post">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">調査概要</h3>
            </div>
            <div class="box-body">
              <label>調査名</label>
              <small>　調査地や調査対象などがわかるような名前にしてください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="activ_name" class="form-control pull-right require">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <label>調査期間</label>
              <small>　調査期間を設定することで、その期間だけ調査が実施されます。期間外の場合は調査報告を行うことができません。</small>
              <div class="form-group">

                <div class="row">
                  <div class="col-xs-6">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <select class="form-control" name="start">
                        <option selected value="1">1月</option>
                        <option value="2">2月</option>
                        <option value="3">3月</option>
                        <option value="4">4月</option>
                        <option value="5">5月</option>
                        <option value="6">6月</option>
                        <option value="7">7月</option>
                        <option value="8">8月</option>
                        <option value="9">9月</option>
                        <option value="10">10月</option>
                        <option value="11">11月</option>
                        <option value="12">12月</option>
                      </select>
                    </div>
                    <!-- /.input group -->
                  </div>
                  <div class="col-xs-6">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <select class="form-control" name="end">
                        <option value="1">1月</option>
                        <option value="2">2月</option>
                        <option value="3">3月</option>
                        <option value="4">4月</option>
                        <option value="5">5月</option>
                        <option value="6">6月</option>
                        <option value="7">7月</option>
                        <option value="8">8月</option>
                        <option value="9">9月</option>
                        <option value="10">10月</option>
                        <option value="11">11月</option>
                        <option selected value="12">12月</option>
                      </select>
                    </div>
                    <!-- /.input group -->
                  </div>
                </div>
              </div>
              <!-- /.form group -->

              <label>調査概要</label>
              <small>　調査の意図や調査方法、調査時の注意点、調査の対象地域がある場合は入力してください。</small>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-pencil"></i>
                  </div>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="activ_details"></textarea>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <label>対象名</label>
              <small>　調査する対象名を入力してください。</small>
              <div class="form-group has-warning">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-star"></i>
                  </div>
                  <input type="text" class="form-control pull-right require" name="activ_target">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <div class="form-group">
                <label>対象詳細</label>
                <small>　対象の報告時の注意点などがある場合は入力してください。</small>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-pencil"></i>
                  </div>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="activ_overview"></textarea>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

                         
                 
              <!-- <div class="form-group">
                <label>Twitter連携</label>
                <a class="btn btn-block btn-social btn-twitter">
                  <i class="fa fa-twitter"></i> Sign in with Twitter</a>
              </div> -->
              <!-- /.form group -->
            </div>
            <!-- /.box-body -->
          </div>
          <button type="submit" id="submit" name="newActivity" class="btn btn-block btn-success btn-lg" >調査項目設定へ進む</button>
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

