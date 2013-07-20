(function($) {
	
	$("#theContentWrap").bind('copy', function() {

		//選択中のテキスト取得
		var copyText = $.selection();
		console.log(CCC.postID);
		
		//ajaxで送信
		$.ajax({
			type: 'POST',
			url: CCC.endpoint,
			data: {
				'action': CCC.action,
				'copyText': copyText,
				'postID': CCC.postID,
				'url': window.location.href,
				'server_id': CCC.server_id
			},
			success: function(data){
				if( data.debug == true ){
					var json_str = JSON.stringify(data);
					$('#json-data').append(json_str);					
				}
			},
			error: function(){
				console.log('error');
			}
		});
		
	});	



})(jQuery);