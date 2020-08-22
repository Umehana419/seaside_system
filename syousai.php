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
?>

<?php

?>