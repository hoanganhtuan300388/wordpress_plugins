function inputCheck() {

	if (mainForm.disp_url.value != "") {
		if(mainForm.disp_url.value.match(/(http|https):\/\/[!#-9A-~]+[a-z0-9]/i)){
			document.mainForm.submit();
		}else{
			alert("会員証を表示するページのＵＲＬのアドレス形式を正しく入力してください。");
		}
	} else {
		alert("会員証を表示するページのＵＲＬを入力してください。");
	}
}
