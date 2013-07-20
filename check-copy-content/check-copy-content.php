<?php
/*
Plugin Name: Check Copy Content(CCC)
Plugin URI: http://www.kigurumi.asia
Description: 本文がコピーされた時にメールを通知します。
Author: Nakashima Masahiro
Version: 0.1
Author URI: http://www.kigurumi.asia
*/
/*
参考：
http://www.slideshare.net/yuka2py/wordpress-15359306
http://show-web.jp/2012/08/12/ajax%E3%81%A7wordpress%E3%81%AE%E3%82%B3%E3%83%B3%E3%83%86%E3%83%B3%E3%83%84%E3%82%92%E9%81%B7%E7%A7%BB%E3%81%9B%E3%81%9A%E8%A1%A8%E7%A4%BA%E3%81%95%E3%81%9B%E3%82%8B/
http://takahashifumiki.com/web/programing/1978/

作業メモ:
sprintf("%s/js/", dirname(__FILE__)); //サーバー内パス
plugins_url( 'check-copy-content/js/admin.js' ) //プラグインからのパス
*/
class CheckCopyContent {
	 
	 
	/**
	 * constructor
	**/ 
    function __construct() {
	    
	    // プラグインが有効化されたときに実行されるメソッドを登録
        if (function_exists('register_activation_hook'))
        {
            register_activation_hook(__FILE__, array(&$this, 'activationHook'));
        }
        
		//contentへのフック
		add_filter( 'the_content', array($this, 'filter_wrap_content') );
		  
		//header()のフック
		add_filter( 'wp_head', array($this, 'filter_header') );
		
		//ajaxのアクション
		add_action('wp_ajax_cccAjax', array($this, 'cccAjax'));
		add_action('wp_ajax_nopriv_cccAjax', array($this, 'cccAjax'));	
		
		//管理画面についか
		add_action('admin_menu', array($this, 'ccc_admin_menu'));	 	 
	}
	
	
    /**
     * プラグインが有効化されたときに実行されるメソッド
     * @return void
     */
    public function activationHook()
    {
		//オプションを初期値
        if (! get_option('ccc_plugin_value_mail'))
        {	
        	$admin_email = get_option('admin_email');
            update_option('ccc_plugin_value_mail', $admin_email);
        }
        
        if (! get_option('ccc_plugin_value_subject'))
        {
            update_option('ccc_plugin_value_subject', '[From:CCC]ブログがコピーされました');
        }
        
        if (! get_option('ccc_plugin_value_reply'))
        {
			$url = get_bloginfo('url');
			$url = parse_url($url);
			$reply = 'no-reply@'.$url['host'];
            update_option('ccc_plugin_value_reply', $reply);
        }

    }


	
	
	    
    
    	
	/***
	 * contentをdivで囲む
	***/
	public function filter_wrap_content ( $content ) {
		
		$str = get_option('check_copy_content_option_value');
		//$str = 'POKO';
		echo '<div>'.$str.'</div>';
		
		//the_content()をIDで囲む
		echo '<div id="theContentWrap">';
	    echo( $content );
		echo '</div>';
		
	}
	
	
	/***
	 * headerにjsを読み込む
	***/
	public function filter_header(){
		
		$js_url = plugins_url( 'check-copy-content/js' );
		$js_selection_url = $js_url.'/jquery.selection.js';
		$js_style_url = $js_url.'/style.js';
		
		//jsを読み込み
		wp_enqueue_script('jquery');
		wp_enqueue_script('ccc-onload1', $js_selection_url ) ;		
		wp_enqueue_script('ccc-onload', $js_style_url, array('jquery'));
		wp_localize_script('ccc-onload', 'CCC', array(
	        'endpoint' => admin_url('admin-ajax.php'),
			'action' => 'cccAjax'
	    ));
		
	}
	
	
	/***
	 * ajaxでPOSTされた時の処理
	***/
	public function cccAjax() {
		
		//POST取得
		$copyText = $_POST['copyText'];
		$copyText = htmlspecialchars($copyText, ENT_QUOTES);
		$post_url = $_POST['url'];
		$post_url = htmlspecialchars($post_url, ENT_QUOTES);
		
		
		//補足データ作成
		$server_id = $_SERVER["REMOTE_ADDR"];
		$server_remote = $_SERVER["HTTP_USER_AGENT"];
		$time = date( "Y/m/d (D) H:i:s", time() );
		
		//TODO: データを保存


		//メール用のデータ作成
		$mail = get_option('ccc_plugin_value_mail');
		$subject = get_option('ccc_plugin_value_subject');
		$reply = get_option('ccc_plugin_value_reply');
$mail_body=<<<mail_body__END
---------------------------------------------------
以下の本文がコピーされたようです。
---------------------------------------------------

{$copyText}

---------------------------------------------------
TIME：{$time}
URL：{$post_url}
IP：{$server_id}
ブラウザ：{$server_remote}
---------------------------------------------------
mail_body__END;


		
		//メール送信
		$result = wp_mail( $mail,$subject, $mail_body, $reply );

		/*
		//json出力
		$charset = get_bloginfo( 'charset' );
		$array = array( 'massage' => $copyText, 'result' => $result  );
		$json = json_encode( $array );
		nocache_headers();
		header( "Content-Type: application/json; charset=$charset" );
		echo $json;
		*/
		die();
	}
	
	

	/***
	 * 管理画面
	***/
	public function ccc_admin_menu(){
		add_menu_page(
			'Check Copy Content', //HTMLページのタイトル
			'CCC設定',//管理画面のメニュー
			'manage_options', //ユーザーレベル
			'Check_Copy_Content_admin_menu', //URLに入る名前
			array($this,'ccc_edit_setting')//機能を提供する関数
			//'[icon_url]'//アイコンURL
		);
	}
	
	
	/***
	 * 管理画面を表示
	***/
	function ccc_edit_setting(){
		if(!current_user_can('manage_options')) {
			wp_die();
		}
		// Render the settings template
		include(sprintf("%s/views/admin.php", dirname(__FILE__)));
	}	
	
	
}
$CheckCopyContent = new CheckCopyContent();




?>