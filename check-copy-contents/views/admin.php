<?php
/***
 * 管理画面の
参考：データ検証
http://wpdocs.sourceforge.jp/%E3%83%87%E3%83%BC%E3%82%BF%E6%A4%9C%E8%A8%BC#Email 
***/


/***
 * 初期変数
***/
$errors = array();

//lessのURL
$less_url = plugins_url( 'check-copy-contents/css/style.less?' );
$time = time();
$less_url = $less_url.$time;



/***
 * POST処理
***/
if (isset($_POST['mail'], $_POST['subject'], $_POST['reply'], $_POST['letters'])){

	//メール
	if (is_email($_POST['mail']) == true){
		update_option('ccc_plugin_value_mail', $_POST['mail']);
	}
	else{
		$errors[] = 'メールアドレスが正しくありません。';
	}
	
	//件名
	$subject = htmlspecialchars($_POST['subject'], ENT_QUOTES);
	update_option('ccc_plugin_value_subject', $subject);
	
	//reply
	if (is_email($_POST['reply']) == true){
		update_option('ccc_plugin_value_reply', $_POST['reply']);
	}
	else{
		$errors[] = '通知メールのアドレスが正しくありません。';
	}
	
	//文字数
	$letters = $_POST['letters'];
	if (strval($letters) == strval(intval($letters)))
	{
		update_option('ccc_plugin_value_letters', $letters);	
	}
	else
	{
		$errors[] = '整数を入力してください。';
	}
	
	
	
	//ログイン時のチェック
	$login_flg = htmlspecialchars($_POST['login_flg'], ENT_QUOTES);
	update_option('ccc_plugin_value_login_flg', $login_flg);
	
}
//エラーがあればリスト作成
if ( isset($errors) ){
$errorList = '<ul class="errors">';
	foreach ($errors as $value) {
	    $errorList .= '<li>'.$value.'</li>';
	}
$errorList .= '</ul>';	
}


/***
 * データを取得
***/
//登録データ
$mail = get_option('ccc_plugin_value_mail');
$subject = get_option('ccc_plugin_value_subject');
$reply = get_option('ccc_plugin_value_reply');
$letters = get_option('ccc_plugin_value_letters');
$login_flg = get_option('ccc_plugin_value_login_flg');



//出力
$echo = '';
$echo .= <<<EOD
<link rel="stylesheet/less" href="{$less_url}" />
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.3.3/less.min.js" type="text/javascript"></script>


<div>
<h1>CCC <span style="font-size:80%;">(Check Copy Contents)</span>設定画面</h1>

{$errorList}

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
	<tr>
		<th>
			感知する文字数
		</th>
		<td>
			<input type="text" name="letters" value="{$letters}" class="p20" /> 文字以上
			<div class="supp">この文字数以上がコピーされた時に通知メールをします。</div>
		</td>
	</tr>
	<tr>
		<th>
			ログインユーザーの時の通知
		</th>
		<td>
EOD;

if ($login_flg == 1)
{
	$echo .= '<input type="checkbox" name="login_flg" value="1" checked> 通知する';
}
else if($login_flg != 1)
{
	$echo .= '<input type="checkbox" name="login_flg" value="1" > 通知する';		
}
			
$echo .= <<<EOD
		<div class="supp">ログインしているユーザーがコピーした時に通知します。</div>
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

echo $echo;




/* DBの保存データ
echo '<ul>';
$posts = query_posts('meta_key=ccc_plugin_value_copytext');
foreach ( $posts as $post ) {
	echo '<li><a href="'.$post->ID.'">'.$post->post_title.'</a></li>';
}
echo '<ul>';
*/
?>