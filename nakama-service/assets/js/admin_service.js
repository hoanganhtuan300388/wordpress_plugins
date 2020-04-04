jQuery(document).ready(function($) {
	$(document).on('keyup change','input#nakama_service_param_tg_id' ,function() {
		jQuery('.img_theme_loading').show();
		var tg_id = $("input[name=nakama_service_param_tg_id]").val();
		var post_id = $("input[name=post_ID]").val();
		if(tg_id == ''){
			$("#is_show_not_tgid").css("display","table-row");
			$("#param").css("display","none");
		} else {
			$("#is_show_not_tgid").css("display","none");
			$("#param").css("display","block");
			$.ajax({
				url: '../wp-admin/admin-ajax.php',
				type : "POST",
				data: {
					action: 'service_get_theme_data_by_tgid',
					tg_id: tg_id,
					post_id: post_id
				},
				success: function(response) {
					$("#nakama_service_param_lg_g_id").html('');
					$("#nakama_service_param_lg_g_id").html(response.data[1]);
					jQuery('.img_theme_loading').hide();
				},
				error: function() {}
			});
		}
	});
});

function reload(){
	jQuery('#setting_param_submit input#submit').click();
	jQuery('.img_theme_loading').show();
}
