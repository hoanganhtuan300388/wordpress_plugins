jQuery(document).ready(function($) {

	$("#service_meta_group_id").change(function(){
		$("#service_meta_p_id").val("public_"+$(this).val());
	});

	$('select#type_setting_service').on('change', function() {
		$('#img_loading_content').show();
		$.ajax({
			url: '../wp-admin/admin-ajax.php',
			type : "POST",
			data: {
				action: 'service_showElement',
				type_setting : $(this).val(),
			},
			success: function(response) {
				$('#img_loading_content').hide();
				if(response == 0)
					$("#content_option").html('');
				else{
					$("#content_option").html(response);
					var tg_id = $("input[name=nakama_service_param_tg_id]").val();
					if(tg_id == ''){
						$("#is_show_not_tgid").css("display","table-row");
						$("#param").css("display","none");
					} else {
						$("#is_show_not_tgid").css("display","none");
						$("#param").css("display","block");
					}
				}
			}
		});
	});
	var flg_tab_second = 0;
	var flg_tab_third = 0;
	$('#content_option').on('click', '.type_service ul#tab_service > li span', function(e) {
		var className = $(this).attr("class");
		$("#content_option ul#tab_service > li").removeClass("ui-tabs-active");
		$(this).parent().addClass("ui-tabs-active");
		$(".content-in").css("display", "none");
		$(".content-out-"+className).removeAttr("style");
		if(className != "service-first"){
			if(className == "service-second"){
				flg_tab_second++;
				if(flg_tab_second <=1){
					call_ajax_load_component(className)
				}
			}
			if(className == "service-third"){
				flg_tab_third++;
				if(flg_tab_third <=1){
					call_ajax_load_component(className)
				}
			}
		}
	});
	function call_ajax_load_component(className){
		$(".content-out-"+className).html('');
		$("#img_loading_tab").show();
		$.ajax({
			url: '../wp-admin/admin-ajax.php',
			type : "POST",
			data: {
				action: 'service_include_file',
				tab: className,
				postid: $("input[name=postID]").val(),
				tg_id: $("input[name=nakama_service_param_tg_id]").val()
			},
			success: function(response) {
				$(".content-out-"+className).html(response);
				$("#img_loading_tab").hide();
			},
			error: function() {}
		});
	}

	var tg_id = $("input[name=nakama_service_param_tg_id]").val();
	if(tg_id == ''){
		$("#is_show_not_tgid").css("display","table-row");
		$("#param").css("display","none");
	} else {
		$("#is_show_not_tgid").css("display","none");
		$("#param").css("display","block");
	}
	$(document).on('keyup change','input#nakama_service_param_tg_id' ,function() {
		console.log("input#nakama_service_param_tg_id");
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
					$("#nakama_service_param_service_info_id").html('');
					$("#nakama_service_param_service_info_id").html(response.data[0]);
					$("#nakama_service_param_lg_g_id").html('');
					$("#nakama_service_param_lg_g_id").html(response.data[1]);
					jQuery('.img_theme_loading').hide();
				},
				error: function() {}
			});
		}
	});

	$("#content_option ul#tab_service > li span").click(function(e){
		var className = $(this).attr("class");
		$("#content_option ul#tab_service > li").removeClass("ui-tabs-active");
		$(this).parent().addClass("ui-tabs-active");
		$(".content-in").css("display", "none");
		$(".content-out-"+className).removeAttr("style");
	});
});
function onchange_service_info_id(e){
	dis_id = e.target.value;
	var post_id = jQuery("input[name=post_ID]").val();
	jQuery('.img_theme_loading').show();
	jQuery.ajax({
		url: '../wp-admin/admin-ajax.php',
		type : "POST",
		data: {
			action: 'service_get_data_setting',
			dis_id: dis_id,
			post_id: post_id,
			tg_id: jQuery("input[name=nakama_service_param_tg_id]").val()
		},
		success: function(response) {
			if(response.data){
				jQuery("input[name=nakama_service_param_lg_g_id]").val(response.data['LG_ID']);
				jQuery("input[name=nakama_service_param_lg_write][value="+response.data['LG_WRITE']+"]").prop("checked",true);
				jQuery('#groupTreeServiceSelect').combotree('setValue', response.data['LG_ID']);
			}else {
				jQuery("input[name=nakama_service_param_lg_g_id]").val('');
				jQuery('#groupTreeServiceSelect').combotree('setValue', '');
			}
			jQuery('.img_theme_loading').hide();
		},
		error: function() {}
	});
}
