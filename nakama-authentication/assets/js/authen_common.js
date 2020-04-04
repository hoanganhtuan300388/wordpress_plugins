jQuery(function($) {
	var arrHidden = [
      'nakama_setting_login',
      'nakama_setting_logout',
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