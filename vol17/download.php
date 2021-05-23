<?php

// データベースの接続情報
define( 'DB_HOST', 'localhost');
define( 'DB_USER', 'root');
define( 'DB_PASS', 'password');
define( 'DB_NAME', 'board');

// 変数の初期化
$csv_data = null;
$sql = null;
$pdo = null;
$option = null;
$message_array = array();

session_start();

if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true ) {

	// データベースに接続
	try {

		$option = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
		);
		$pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);

		// メッセージのデータを取得する
		$sql = "SELECT * FROM message ORDER BY post_date ASC";
		$message_array = $pdo->query($sql);

		// データベースの接続を閉じる
		$pdo = null;

	} catch(PDOException $e) {

		// 管理者ページへリダイレクト
		header("Location: ./admin.php");
		exit;
	}

	// 出力の設定
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=メッセージデータ.csv");
	header("Content-Transfer-Encoding: binary");

	// CSVデータを作成
	if( !empty($message_array) ) {
		
		// 1行目のラベル作成
		$csv_data .= '"ID","表示名","メッセージ","投稿日時"'."\n";
		
		foreach( $message_array as $value ) {
		
			// データを1行ずつCSVファイルに書き込む
			$csv_data .= '"' . $value['id'] . '","' . $value['view_name'] . '","' . $value['message'] . '","' . $value['post_date'] . "\"\n";
		}
	}

	// ファイルを出力	
	echo $csv_data;

} else {

	// ログインページへリダイレクト
	header("Location: ./admin.php");
	exit;
}

return;