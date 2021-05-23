<?php

// メッセージを保存するファイルのパス設定
define( 'FILENAME', './message.txt');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$current_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();


if( !empty($_POST['btn_submit']) ) {
	
	// 表示名の入力チェック
	if( empty($_POST['view_name']) ) {
		$error_message[] = '表示名を入力してください。';
	} else {
		$clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES, 'UTF-8');
        $clean['view_name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['view_name']);
	}
	
	// メッセージの入力チェック
	if( empty($_POST['message']) ) {
		$error_message[] = 'ひと言メッセージを入力してください。';
	} else {
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES, 'UTF-8');
		$clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
	}

	if( empty($error_message) ) {

		if( $file_handle = fopen( FILENAME, "a") ) {
	
		    // 書き込み日時を取得
			$current_date = date("Y-m-d H:i:s");
		
			// 書き込むデータを作成
			$data = "'".$clean['view_name']."','".$clean['message']."','".$current_date."'\n";
		
			// 書き込み
			fwrite( $file_handle, $data);
		
			// ファイルを閉じる
			fclose( $file_handle);

			$success_message = 'メッセージを書き込みました。';
		}
	}
}

if( $file_handle = fopen( FILENAME,'r') ) {
    while( $data = fgets($file_handle) ){

		$split_data = preg_split( '/\'/', $data);

		$message = array(
			'view_name' => $split_data[1],
			'message' => $split_data[3],
			'post_date' => $split_data[5]
		);
		array_unshift( $message_array, $message);
	}
    
    // ファイルを閉じる
    fclose( $file_handle);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひと言掲示板</title>
<style>

/*------------------------------

 Reset Style
 
------------------------------*/
html, body, div, span, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
abbr, address, cite, code,
del, dfn, em, img, ins, kbd, q, samp,
small, strong, sub, sup, var,
b, i,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figcaption, figure,
footer, header, hgroup, menu, nav, section, summary,
time, mark, audio, video {
    margin:0;
    padding:0;
    border:0;
    outline:0;
    font-size:100%;
    vertical-align:baseline;
    background:transparent;
}

body {
    line-height:1;
}

article,aside,details,figcaption,figure,
footer,header,hgroup,menu,nav,section {
    display:block;
}

nav ul {
    list-style:none;
}

blockquote, q {
    quotes:none;
}

blockquote:before, blockquote:after,
q:before, q:after {
    content:'';
    content:none;
}

a {
    margin:0;
    padding:0;
    font-size:100%;
    vertical-align:baseline;
    background:transparent;
}

/* change colours to suit your needs */
ins {
    background-color:#ff9;
    color:#000;
    text-decoration:none;
}

/* change colours to suit your needs */
mark {
    background-color:#ff9;
    color:#000;
    font-style:italic;
    font-weight:bold;
}

del {
    text-decoration: line-through;
}

abbr[title], dfn[title] {
    border-bottom:1px dotted;
    cursor:help;
}

table {
    border-collapse:collapse;
    border-spacing:0;
}

hr {
    display:block;
    height:1px;
    border:0;
    border-top:1px solid #cccccc;
    margin:1em 0;
    padding:0;
}

input, select {
    vertical-align:middle;
}

/*------------------------------

Common Style

------------------------------*/
body {
	padding: 50px;
	font-size: 100%;
	font-family:'ヒラギノ角ゴ Pro W3','Hiragino Kaku Gothic Pro','メイリオ',Meiryo,'ＭＳ Ｐゴシック',sans-serif;
	color: #222;
	background: #f7f7f7;
}

a {
    color: #007edf;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.wrapper {
    display: flex;
    margin: 0 auto 50px;
    padding: 0 20px;
    max-width: 1200px;
    align-items: flex-start;
}

h1 {
	margin-bottom: 30px;
    font-size: 100%;
    color: #222;
    text-align: center;
}


/*-----------------------------------
入力エリア
-----------------------------------*/

label {
    display: block;
    margin-bottom: 7px;
    font-size: 86%;
}

input[type="text"],
textarea {
	margin-bottom: 20px;
	padding: 10px;
	font-size: 86%;
    border: 1px solid #ddd;
    border-radius: 3px;
    background: #fff;
}

input[type="text"] {
	width: 200px;
}
textarea {
	width: 50%;
	max-width: 50%;
	height: 70px;
}
input[type="submit"] {
	appearance: none;
    -webkit-appearance: none;
    padding: 10px 20px;
    color: #fff;
    font-size: 86%;
    line-height: 1.0em;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #37a1e5;
}
input[type=submit]:hover,
button:hover {
    background-color: #2392d8;
}

hr {
	margin: 20px 0;
	padding: 0;
}

.success_message {
    margin-bottom: 20px;
    padding: 10px;
    color: #48b400;
    border-radius: 10px;
    border: 1px solid #4dc100;
}

.error_message {
    margin-bottom: 20px;
    padding: 10px;
    color: #ef072d;
    list-style-type: none;
    border-radius: 10px;
    border: 1px solid #ff5f79;
}

.success_message,
.error_message li {
    font-size: 86%;
    line-height: 1.6em;
}


/*-----------------------------------
掲示板エリア
-----------------------------------*/

article {
	margin-top: 20px;
	padding: 20px;
	border-radius: 10px;
	background: #fff;
}
article.reply {
    position: relative;
    margin-top: 15px;
    margin-left: 30px;
}
article.reply::before {
    position: absolute;
    top: -10px;
    left: 20px;
    display: block;
    content: "";
    border-top: none;
    border-left: 7px solid #f7f7f7;
    border-right: 7px solid #f7f7f7;
    border-bottom: 10px solid #fff;
}
	.info {
		margin-bottom: 10px;
	}
	.info h2 {
		display: inline-block;
		margin-right: 10px;
		color: #222;
		line-height: 1.6em;
		font-size: 86%;
	}
	.info time {
		color: #999;
		line-height: 1.6em;
		font-size: 72%;
	}
    article p {
        color: #555;
        font-size: 86%;
        line-height: 1.6em;
    }

@media only screen and (max-width: 1000px) {

    body {
        padding: 30px 5%;
    }

    input[type="text"] {
        width: 100%;
    }
    textarea {
        width: 100%;
        max-width: 100%;
        height: 70px;
    }
}
</style>
</head>
<body>
<h1>ひと言掲示板</h1>
<?php if( !empty($success_message) ): ?>
    <p class="success_message"><?php echo $success_message; ?></p> 
<?php endif; ?>
<?php if( !empty($error_message) ): ?>
    <ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
            <li>・<?php echo $value; ?></li>
		<?php endforeach; ?>
    </ul>
<?php endif; ?>
<form method="post">
	<div>
		<label for="view_name">表示名</label>
		<input id="view_name" type="text" name="view_name" value="">
	</div>
	<div>
		<label for="message">ひと言メッセージ</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="書き込む">
</form>
<hr>
<section>
<?php if( !empty($message_array) ){ ?>
<?php foreach( $message_array as $value ){ ?>
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
    </div>
    <p><?php echo $value['message']; ?></p>
</article>
<?php } ?>
<?php } ?>
</section>
</body>
</html>