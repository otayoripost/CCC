(function($) {
	
	$("#theContentWrap").bind('copy', function() {

		//選択中のテキスト取得
		var copyText = $.selection();
		
		//ajaxで送信
		$.ajax({
			type: 'POST',
			url: CCC.endpoint,
			data: {
				'action': CCC.action,
				'copyText': copyText,
				'url': window.location.href
			},
			success: function(data){
				//var json_str = JSON.stringify(data);
				//$('#json-data').append(json_str);
			},
			error: function(){
				console.log('error');
			}
		});
		
	});	



})(jQuery);