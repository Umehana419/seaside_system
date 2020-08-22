<?php
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "g031o120";  // ユーザー名
$db['pass'] = "g031o120PW";  // ユーザー名のパスワード
$db['dbname'] = "g031o120";  // データベース名

// 日付が入力されていたら
if (isset($_POST['date'])) {
    if (empty($_POST['lat'])) {
        $errorMessage = '位置情報が未入力です。';
    } elseif (empty($_POST['lng'])) {
        $errorMessage = '位置情報が未入力です。';
    } elseif (empty($_POST['date'])) {
        $errorMessage = '発見した日時が未入力です。';
    } elseif (empty($_POST['number'])) {
        $errorMessage = '生息数が未入力です。';
    } else {
        $researchID = $_POST['researchID'];
        $loginID = $_POST['loginID'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $date = $_POST['date'];
        $number = $_POST['number'];

        // 認証
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('INSERT INTO report(researchID, userID, lng, lat, date, number, private) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute(array($researchID, $loginID, $lng, $lat, $date, $number, 1));

            $reportID = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを取得

            // 詳細調査項目が入力されていたら
            if (isset($_SESSION['reportAry'])) {
                $reportAry = $_SESSION['reportAry'];

                // 詳細調査内容をインサート
                $stmt = $pdo->prepare('INSERT INTO report_answer(reportID, answer) VALUES (?, ?)');
                foreach ($reportAry as $key => $value) {
                    $stmt->execute(array($reportID, $value));
                }
            }

            // ファイルアップロードのエラー処理
            try {
                // 画像情報をインサート
                $stmt = $pdo->prepare('INSERT INTO report_media(reportID, media) VALUES (?, ?)');

                for ($i=1; $i <= 3; $i++) {
                    if (isset($_FILES["img"."$i"]["tmp_name"])) {
                        $directory = 'img/report/reportID' . $reportID . '-' . $i . '.jpg';
                        if (move_uploaded_file($_FILES["img"."$i"]["tmp_name"], $directory)) {
                            $stmt->execute(array($reportID, $directory));
                        } else {
                            throw new Exception('画像ファイルアップロードエラー！');
                        }
                    }
                }
                echo "OK";
            } catch (Exception $ex) {
                echo $ex->getMessage();
                exit();
            }

            unset($_SESSION['reportAry']);
            $_SESSION['message'] = "調査報告が完了しました。検証されると調査トップページに公開されます。（検証は報告一覧ページから行えます）";
            // header("Location: search-top.php?id=" . $researchID);  // メイン画面へ遷移
            exit();
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラーが発生しました。';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
    }
}
