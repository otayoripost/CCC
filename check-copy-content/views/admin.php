<?php
/***
 * 管理画面の
参考：データ検証
http://wpdocs.sourceforge.jp/%E3%83%87%E3%83%BC%E3%82%BF%E6%A4%9C%E8%A8%BC#Email 
***/


/***
 * 初期変数
***/
$errors = '';


/***
 * POST処理
***/
if (isset($_POST['mail'], $_POST['subject'], $_POST['reply'])){

	//メール
	if (is_email($_POST['mail']) == true){
		update_option('ccc_plugin_value_mail', $_POST['mail']);
	}
	else{
		$errors .= 'メールアドレスが正しくありません。';
	}
	
	//件名
	$subject = htmlspecialchars($_POST['subject'], ENT_QUOTES);
	update_option('ccc_plugin_value_subject', $subject);
	
	//reply
	if (is_email($_POST['reply']) == true){
		update_option('ccc_plugin_value_reply', $_POST['reply']);
	}
	else{
		$errors .= '通知メールのアドレスが正しくありません。';
	}

	
}
//エラーがあれば
if ( isset($errors) ){
	
}


/***
 * データを取得
***/
$mail = get_option('ccc_plugin_value_mail');
$subject = get_option('ccc_plugin_value_subject');
$reply = get_option('ccc_plugin_value_reply');




//lessのURL
$less_url = plugins_url( 'check-copy-content/css/style.less?' );
$time = time();
$less_url = $less_url.$time;


//出力
echo <<<EOD
<link rel="stylesheet/less" href="{$less_url}" />
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.3.3/less.min.js" type="text/javascript"></script>


<div>
<h1>CCC <span style="font-size:80%;">(Check Copy Content)</span>設定画面</h1>

{$errors}

<form action="" method="post">
<table class="type01">
<tbody>
	<tr>
		<th>
			通知先のメールアドレス
		</th>
		<td>
			<input type="text" name="mail" value="{$mail}" class="p80" />
			<div class="supp">例：abcd@hoge.com</div>
		</td>
	</tr>
	<tr>
		<th>
			通知メールの件名
		</th>
		<td>
			<input type="text" name="subject" value="{$subject}" class="p80" />
		</td>
	</tr>
	<tr>
		<th>
			通知メールのアドレス
		</th>
		<td>
			<input type="text" name="reply" value="{$reply}" class="p80" />
			<div class="supp">例：no-reply@hoge.com</div>
		</td>
	</tr>
</tbody>	
</table>
<p style="text-align:center;">
<input type="submit" value="登録" />
</p>
</form>
</div>
EOD;



?>