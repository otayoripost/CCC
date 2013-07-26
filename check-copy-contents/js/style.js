(function($) {
	
	$(".theContentWrap-ccc").bind('copy', function() {

		//選択中のテキスト取得
		var copyText = $.selection();
		
		//ajaxで送信
		$.ajax({
			type: 'POST',
			url: CCC.endpoint,
			data: {
				'action': CCC.action,
				'copyText': copyText,
				'postID': CCC.postID,
				'url': window.location.href,
				'remote_addr': CCC.remote_addr,
				'referrer': document.referrer
			},
			success: function(data){
				if( data.debug == true ){
					var json_str = JSON.stringify(data);
					$('#json-data').append(json_str);					
				}
			},
			error: function() {}
		});
		
	});	



})(jQuery);
