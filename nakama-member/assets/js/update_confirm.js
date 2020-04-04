function OnReinput(){
	window.history.back();
	if (typeof(Storage) !== 'undefined') {
        sessionStorage.setItem('confirm_back', 'back');
    }
	$('input[type="button"]').attr("disabled", "disabled");
	$('.preLoading').css('display', 'block');
}


function OnRelease(){
	document.mainForm.submit();
	$('input[type="button"]').attr("disabled", "disabled");
	$('.preLoading').css('display', 'block');
}


function OnReg(){
	$("#mainForm").submit();
	if (typeof(Storage) !== 'undefined') {
        sessionStorage.removeItem('allitem');
    }
	$('input[type="button"]').attr("disabled", "disabled");
	$('.preLoading').css('display', 'block');
}

function ShowFeeChenge(){
	gToolWnd = open('../common/FeeChenge.asp?top_g_id=' + document.mainForm.k_top_gid.value
		+ "&g_id=" + document.mainForm.k_gid.value
		+ "&p_id=" + document.mainForm.k_pid.value
		, 'DetailWnd','width=550,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}