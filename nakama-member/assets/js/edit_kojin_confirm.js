function OnReinput(){
	window.history.back();
}


function OnRelease(){
	document.mainForm.submit();
}


function OnReg(){
	document.mainForm.submit();
}

function ShowFeeChenge(){
	gToolWnd = open('../common/FeeChenge.asp?top_g_id=' + document.mainForm.k_top_gid.value
		+ "&g_id=" + document.mainForm.k_gid.value
		+ "&p_id=" + document.mainForm.k_pid.value
		, 'DetailWnd','width=550,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}