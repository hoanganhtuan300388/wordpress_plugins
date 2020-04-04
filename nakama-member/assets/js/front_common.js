jQuery(function($) {
	var arrHidden = [
      'nakama_setting_member_zipcode', 
      'nakama_setting_get_category',
      'nakama_setting_get_feestatus',
      'nakama_setting_get_imgview',
      'nakama_setting_search_bank',
      'nakama_setting_select_dictionary',
      'nakama_setting_member_search_lg',
      'nakama_setting_check_magazine',
      'nakama_setting_confirm',
      'nakama_setting_complete',
      'nakama_setting_update_confirm',
      'nakama_setting_update_complete',
      'nakama_setting_view',
      'nakama_setting_login_sort',
      'nakama_setting_member_detail_2',
      'nakama_setting_member_list_group'
    ];
	$(".nav-menu").each(function(){
		var current = $(this);
		var id_menu = current.attr('id');
		$("#"+id_menu+" li").each(function(){
      var parent = $(this);
      var text = parent.text();
      arrHidden.forEach(function(e){
        if(text.indexOf(e) != -1){
          parent.remove();
        }
	    });
		});
  });
  $("li.menu-item").each(function(){
    var parent = $(this);
    var text = parent.text();
    arrHidden.forEach(function(e){
      if(text.indexOf(e) != -1){
        parent.remove();
      }
    });
  });
});