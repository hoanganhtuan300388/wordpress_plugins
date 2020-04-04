var g_focusItem = null;
var g_focusElem = null;


function SetDicItem(itemPos){
	var i;
	var itemName = "";
	for(i=0;i<document.all.length;i++){
		if(document.all[i].name == document.activeElement.name){
			itemName = document.all[i - itemPos].innerHTML;
			break;
		}
	}
	g_focusItem = itemName;
	g_focusElem = document.activeElement.name;
}

function ShowDicWnd(url){
	var dicName;
	switch(g_focusItem){
		case "役職":
		dicName = "役職名";
		break;
	}
	return open(url + "?form=document.mainForm&item=" + dicName + "&text=" + g_focusElem,
		'SearchWnd',
		'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function ShowDicWnd2(url,dicName,eleName){
	return open(url + "?form=document.mainForm&item=" + dicName + "&text=" + eleName,
		'SearchWnd',
		'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function OnZipCode(cb, zip1, zip2){
	var buf = document.URL.split("/");
	var path;
	if(zip1.length != 3){
		alert("郵便番号は３桁以上を入力して下さい。");
		return false;
	}
	gToolWnd = open("./window/SelAddr.asp?zip=" + zip1 + zip2 + "&cb=" + cb,
		'SearchWnd',
		'width=500,height=300,left=500,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function pdCheckInputData(bChange){
	var form = document.mainForm;
	var messageflg = 1;
	var m_card_open;
	var m_open = form.P_O_NAME.value;
	if(form.P_CARD_OPEN == false || form.P_CARD_OPEN.type == "hidden"){
		messageflg = 0;
	}else{
		m_card_open = form.P_CARD_OPEN.value;
	}
	var change_flg="0";
	if(bChange == true){
		change_flg="1";
	}

	if(bChange == false){
		if(IsNull(form.P_P_ID.value, "個人ID")){
			return errProc(form.P_P_ID);
		}
		if(IsLength(form.P_P_ID.value, 4, 20, "個人ID")){
			return errProc(form.P_P_ID);
		}
		if(IsNarrowPlus(form.P_P_ID.value, "個人ID")){
			return errProc(form.P_P_ID);
		}
	}

	if(form.P_PASSWORD.type != "hidden"){
		if(IsNull(form.P_PASSWORD.value, "パスワード")){
			return errProc(form.P_PASSWORD);
		}
		if(IsLength(form.P_PASSWORD.value, 4, 20, "パスワード")){
			return errProc(form.P_PASSWORD);
		}
		if(IsNarrowPassword(form.P_PASSWORD.value, "パスワード")){
			return errProc(form.P_PASSWORD);
		}
	}


	if(form.P_PASSWORD.value != form.P_PASSWORD2.value){
		alert("パスワードの内容と確認入力の内容が一致しません。\nパスワードをもう一度確認して下さい");
		form.P_PASSWORD2.value = "";
		form.P_PASSWORD2.focus();
		return false;
	}

	if(form.P_C_NAME.type != "hidden"){
		if(IsNull(form.P_C_NAME.value, "氏名")){
			return errProc(form.P_C_NAME);
		}
		if(IsLengthB(form.P_C_NAME.value, 0, 200, "氏名")){
			return errProc(form.P_C_NAME);
		}
	}

	if(form.P_C_KANA.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_KANA.value){
			messageflg = 0;
		}
		if(IsNull(form.P_C_KANA.value, "個人フリガナ")){
			return errProc(form.P_C_KANA);
		}
		if(IsLengthB(form.P_C_KANA.value, 0, 200, "個人フリガナ")){
			return errProc(form.P_C_KANA);
		}
	}

	if(form.P_C_SEX.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_SEX.value){
			messageflg = 0;
		}
	}

	if(form.P_C_URL.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_URL.value){
			messageflg = 0;
		}
		if(IsLength(form.P_C_URL.value, 0, 100, "個人URL")){
			return errProc(form.P_C_URL);
		}
		if(IsNarrowPlus(form.P_C_URL.value, "個人URL")){
			return errProc(form.P_C_URL);
		}
	}

	if(form.P_C_EMAIL.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_EMAIL.value){
			messageflg = 0;
		}
		if(IsLength(form.P_C_EMAIL.value, 0, 100, "個人E-MAIL")){
			return errProc(form.P_C_EMAIL);
		}
	}
	if(IsNarrowPlus3(form.P_C_EMAIL.value, "個人E-MAIL")){
		return errProc(form.P_C_EMAIL);
	}
	if(isMail(form.P_C_EMAIL.value, "個人E-MAIL")){
		return errProc(form.P_C_EMAIL);
	}

	if(form.P_C_EMAIL2 != undefined){
		if(form.P_C_EMAIL2.type != "hidden"){
			if(IsLength(form.P_C_EMAIL2.value, 0, 100, "個人E-MAIL再入力")){
				return errProc(form.P_C_EMAIL2);
			}
		}
		if(IsNarrowPlus3(form.P_C_EMAIL2.value, "個人E-MAIL再入力")){
			return errProc(form.P_C_EMAIL2);
		}
		if(isMail(form.P_C_EMAIL2.value, "個人E-MAIL再入力")){
			return errProc(form.P_C_EMAIL2);
		}
		if(form.P_C_EMAIL.value != form.P_C_EMAIL2.value){
			alert("個人E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
			form.P_C_EMAIL2.value = "";
			form.P_C_EMAIL2.focus();
			return false;
		}
	}

	if(form.P_C_CC_EMAIL != undefined){
		if(form.P_C_CC_EMAIL.type != "hidden"){
			if(messageflg == 1 && m_open != form.P_O_C_CC_EMAIL.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_CC_EMAIL.value, 0, 100, "個人追加送信先E-MAIL")){
				return errProc(form.P_C_CC_EMAIL);
			}
		}
		if(IsNarrowPlus3(form.P_C_CC_EMAIL.value, "個人追加送信先E-MAIL")){
			return errProc(form.P_C_CC_EMAIL);
		}
		if(isMail(form.P_C_CC_EMAIL.value, "個人追加送信先E-MAIL")){
			return errProc(form.P_C_CC_EMAIL);
		}
	}

	if(form.P_C_TEL.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_TEL.value){
			messageflg = 0;
		}
	}
	if(form.P_C_TEL_1.value != "" || form.P_C_TEL_2.value != "" || form.P_C_TEL_3.value != ""){
		if(form.P_C_TEL_1.value == ""){
			alert("個人TEL\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_TEL_1);
		}
		if(form.P_C_TEL_2.value == ""){
			alert("個人TEL\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_TEL_2);
		}
		if(form.P_C_TEL_3.value == ""){
			alert("個人TEL\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_TEL_3);
		}
		if(IsNarrowTelNum(form.P_C_TEL_1.value, "個人TEL")){
			return errProc(form.P_C_TEL_1);
		}
		if(IsNarrowTelNum(form.P_C_TEL_2.value, "個人TEL")){
			return errProc(form.P_C_TEL_2);
		}
		if(IsNarrowTelNum(form.P_C_TEL_3.value, "個人TEL")){
			return errProc(form.P_C_TEL_3);
		}
		form.P_C_TEL.value = form.P_C_TEL_1.value + "-" + form.P_C_TEL_2.value + "-" + form.P_C_TEL_3.value;
		if(form.P_C_TEL.type != "hidden"){
			if(IsLength(form.P_C_TEL.value, 0, 20, "個人TEL")){
				return errProc(form.P_C_TEL_1);
			}
		}
	} else {
		form.P_C_TEL.value = "";
	}

	if(form.P_C_FAX.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_FAX.value){
			messageflg = 0;
		}
	}
	if(form.P_C_FAX_1.value != "" || form.P_C_FAX_2.value != "" || form.P_C_FAX_3.value != ""){
		if(form.P_C_FAX_1.value == ""){
			alert("個人FAX\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_FAX_1);
		}
		if(form.P_C_FAX_2.value == ""){
			alert("個人FAX\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_FAX_2);
		}
		if(form.P_C_FAX_3.value == ""){
			alert("個人FAX\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_FAX_3);
		}
		if(IsNarrowTelNum(form.P_C_FAX_1.value, "個人FAX")){
			return errProc(form.P_C_FAX_1);
		}
		if(IsNarrowTelNum(form.P_C_FAX_2.value, "個人FAX")){
			return errProc(form.P_C_FAX_2);
		}
		if(IsNarrowTelNum(form.P_C_FAX_3.value, "個人FAX")){
			return errProc(form.P_C_FAX_3);
		}
		form.P_C_FAX.value = form.P_C_FAX_1.value + "-" + form.P_C_FAX_2.value + "-" + form.P_C_FAX_3.value;
		if(form.P_C_FAX.type != "hidden"){
			if(IsLength(form.P_C_FAX.value, 0, 20, "個人FAX")){
				form.P_C_FAX_1.value = form.P_C_FAX_2.value = form.P_C_FAX_3.value = ""
				form.P_C_FAX_1.focus();
				return false;
			}
		}
	} else {
		form.P_C_FAX.value = "";
	}

	if(form.P_C_PTEL.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_PTEL.value){
			messageflg = 0;
		}
	}
	if(form.P_C_PTEL_1.value != "" || form.P_C_PTEL_2.value != "" || form.P_C_PTEL_3.value != ""){
		if(form.P_C_PTEL_1.value == ""){
			alert("携帯\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_PTEL_1);
		}
		if(form.P_C_PTEL_2.value == ""){
			alert("携帯\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_PTEL_2);
		}
		if(form.P_C_PTEL_3.value == ""){
			alert("携帯\nを登録する場合は\n全ての欄を入力して下さい")
			return errProc(form.P_C_PTEL_3);
		}
		if(IsNarrowTelNum(form.P_C_PTEL_1.value, "携帯")){
			return errProc(form.P_C_PTEL_1);
		}
		if(IsNarrowTelNum(form.P_C_PTEL_2.value, "携帯")){
			return errProc(form.P_C_PTEL_2);
		}
		if(IsNarrowTelNum(form.P_C_PTEL_3.value, "携帯")){
			return errProc(form.P_C_PTEL_3);
		}
		form.P_C_PTEL.value = form.P_C_PTEL_1.value + "-" + form.P_C_PTEL_2.value + "-" + form.P_C_PTEL_3.value;
		if(form.P_C_PTEL.type != "hidden"){
			if(IsLength(form.P_C_PTEL.value, 0, 20, "携帯")){
				form.P_C_PTEL_1.value = form.P_C_PTEL_2.value = form.P_C_PTEL_3.value = ""
				form.P_C_PTEL_1.focus();
				return false;
			}
		}
	} else {
		form.P_C_PTEL.value = "";
	}

	if(form.P_C_PMAIL.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_PMAIL.value){
			messageflg = 0;
		}
		if(IsLength(form.P_C_PMAIL.value, 0, 100, "携帯メール")){
			return errProc(form.P_C_PMAIL);
		}
	}
	if(IsNarrowPlus3(form.P_C_PMAIL.value, "携帯メール")){
		return errProc(form.P_C_PMAIL);
	}
	if(isMail(form.P_C_PMAIL.value, "携帯メール")){
		return errProc(form.P_C_PMAIL);
	}

	if(form.P_C_PMAIL2 != undefined){
		if(form.P_C_PMAIL2.type != "hidden"){
			if(IsLength(form.P_C_PMAIL2.value, 0, 100, "携帯メール再入力")){
				return errProc(form.P_C_PMAIL2);
			}
		}
		if(IsNarrowPlus3(form.P_C_PMAIL2.value, "携帯メール再入力")){
			return errProc(form.P_C_PMAIL2);
		}
		if(isMail(form.P_C_PMAIL2.value, "携帯メール再入力")){
			return errProc(form.P_C_PMAIL2);
		}
		if(form.P_C_PMAIL.value != form.P_C_PMAIL2.value){
			alert("携帯メールの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
			form.P_C_PMAIL2.value = "";
			form.P_C_PMAIL2.focus();
			return false;
		}
	}

	if(form.P_C_POST.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_POST.value){
			messageflg = 0;
		}
	}
	if(form.P_C_POST_u.value != "" || form.P_C_POST_l.value != ""){
		if(form.P_C_POST_u.value == ""){
			alert("個人〒\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_C_POST_u);
		}
		if(IsLength(form.P_C_POST_u.value, 3, 3, "個人〒(上３桁)")){
			return errProc(form.P_C_POST_u);
		}
		if(IsNarrowNum(form.P_C_POST_u.value, "個人〒")){
			return errProc(form.P_C_POST_u);
		}
		if(form.P_C_POST_l.value == ""){
			alert("個人〒\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_C_POST_l);
		}
		if(IsLength(form.P_C_POST_l.value, 4, 4, "個人〒(下４桁)")){
			return errProc(form.P_C_POST_l);
		}
		if(IsNarrowNum(form.P_C_POST_l.value, "個人〒")){
			return errProc(form.P_C_POST_l);
		}
		form.P_C_POST.value = form.P_C_POST_u.value + "-" + form.P_C_POST_l.value;
	} else {
		form.P_C_POST.value = "";
	}

	if(form.P_C_STA.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_STA.value){
			messageflg = 0;
		}
	}

	if(form.P_C_ADDRESS.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_ADDRESS.value){
			messageflg = 0;
		}
	}
	if(IsLengthB(form.P_C_ADDRESS.value, 0, 500, "個人住所１")){
		return errProc(form.P_C_ADDRESS);
	}

	if(form.P_C_ADDRESS.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_ADDRESS.value){
			messageflg = 0;
		}
		if(IsLengthB(form.P_C_ADDRESS2.value, 0, 100, "建物ビル名")){
			return errProc(form.P_C_ADDRESS2);
		}
	}

	if(form.P_C_IMG.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_IMG.value){
			messageflg = 0;
		}
	}

	if(form.P_C_IMG2 == true){
		if(form.P_C_IMG2.type != "hidden"){
			if(messageflg == 1 && m_open != form.P_O_IMG2.value){
				messageflg = 0;
			}
		}
	}

	if(form.P_C_IMG3 == true){
		if(form.P_C_IMG3.type != "hidden"){
			if(messageflg == 1 && m_open != form.P_O_IMG3.value){
				messageflg = 0;
			}
		}
	}

	if(form.P_C_APPEAL.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_APPEAL.value){
			messageflg = 0;
		}
		if(IsLength(form.P_C_APPEAL.value, 0, 500, "個人アピール")){
			return errProc(form.P_C_APPEAL);
		}
	}

	if(form.P_C_KEYWORD.type != "hidden"){
		if(IsLength(form.P_C_KEYWORD.value, 0, 500, "事務局検索用コメント")){
			return errProc(form.P_C_KEYWORD);
		}
	}

	if('dmshibuya' != 'jeca2'){
		if('dmshibuya' == ''){
		}else{
			if(form.P_C_BIKOU1 == true || form.P_C_BIKOU1 == "[object HTMLTextAreaElement]"){
				if(form.P_C_BIKOU1.type == "text" || form.P_C_BIKOU1.type == "textarea"){
					if(messageflg == 1 && m_open != form.P_O_BIKOU1.value){
						messageflg = 0;
					}
					if(IsLength(form.P_C_BIKOU1.value, 0, 2000, "当会を何で知りましたか？")){
						return errProc(form.P_C_BIKOU1);
					}
				}
			}
		}
	}
	if(form.P_C_BIKOU2 == true || form.P_C_BIKOU2 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU2.type == "text" || form.P_C_BIKOU2.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU2.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU2.value, 0, 2000, "個人自由項目２")){
				return errProc(form.P_C_BIKOU2);
			}
		}
	}
	if(form.P_C_BIKOU3 == true || form.P_C_BIKOU3 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU3.type == "text" || form.P_C_BIKOU3.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU3.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU3.value, 0, 2000, "個人自由項目３")){
				return errProc(form.P_C_BIKOU3);
			}
		}
	}
	if(form.P_C_BIKOU4 == true || form.P_C_BIKOU4 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU4.type == "text" || form.P_C_BIKOU4.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU4.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU4.value, 0, 2000, "個人自由項目４")){
				return errProc(form.P_C_BIKOU4);
			}
		}
	}
	if(form.P_C_BIKOU5 == true || form.P_C_BIKOU5 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU5.type == "text" || form.P_C_BIKOU5.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU5.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU5.value, 0, 2000, "個人自由項目５")){
				return errProc(form.P_C_BIKOU5);
			}
		}
	}
	if(form.P_C_BIKOU6 == true || form.P_C_BIKOU6 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU6.type == "text" || form.P_C_BIKOU6.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU6.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU6.value, 0, 2000, "個人自由項目６")){
				return errProc(form.P_C_BIKOU6);
			}
		}
	}
	if(form.P_C_BIKOU7 == true || form.P_C_BIKOU7 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU7.type == "text" || form.P_C_BIKOU7.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU7.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU7.value, 0, 2000, "個人自由項目７")){
				return errProc(form.P_C_BIKOU7);
			}
		}
	}
	if(form.P_C_BIKOU8 == true || form.P_C_BIKOU8 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU8.type == "text" || form.P_C_BIKOU8.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU8.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU8.value, 0, 2000, "個人自由項目８")){
				return errProc(form.P_C_BIKOU8);
			}
		}
	}
	if(form.P_C_BIKOU9 == true || form.P_C_BIKOU9 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU9.type == "text" || form.P_C_BIKOU9.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU9.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU9.value, 0, 2000, "個人自由項目９")){
				return errProc(form.P_C_BIKOU9);
			}
		}
	}
	if(form.P_C_BIKOU10 == true || form.P_C_BIKOU10 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU10.type == "text" || form.P_C_BIKOU10.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU10.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU10.value, 0, 2000, "個人自由項目１０")){
				return errProc(form.P_C_BIKOU10);
			}
		}
	}
	if(form.P_C_BIKOU11 == true || form.P_C_BIKOU11 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU11.type == "text" || form.P_C_BIKOU11.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU11.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU11.value, 0, 2000, "個人自由項目１１")){
				return errProc(form.P_C_BIKOU11);
			}
		}
	}
	if(form.P_C_BIKOU12 == true || form.P_C_BIKOU12 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU12.type == "text" || form.P_C_BIKOU12.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU12.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU12.value, 0, 2000, "個人自由項目１２")){
				return errProc(form.P_C_BIKOU12);
			}
		}
	}
	if(form.P_C_BIKOU13 == true || form.P_C_BIKOU13 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU13.type == "text" || form.P_C_BIKOU13.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU13.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU13.value, 0, 2000, "個人自由項目１３")){
				return errProc(form.P_C_BIKOU13);
			}
		}
	}
	if(form.P_C_BIKOU14 == true || form.P_C_BIKOU14 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU14.type == "text" || form.P_C_BIKOU14.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU14.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU14.value, 0, 2000, "個人自由項目１４")){
				return errProc(form.P_C_BIKOU14);
			}
		}
	}
	if(form.P_C_BIKOU15 == true || form.P_C_BIKOU15 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU15.type == "text" || form.P_C_BIKOU15.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU15.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU15.value, 0, 2000, "個人自由項目１５")){
				return errProc(form.P_C_BIKOU15);
			}
		}
	}
	if(form.P_C_BIKOU16 == true || form.P_C_BIKOU16 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU16.type == "text" || form.P_C_BIKOU16.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU16.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU16.value, 0, 2000, "個人自由項目１６")){
				return errProc(form.P_C_BIKOU16);
			}
		}
	}
	if(form.P_C_BIKOU17 == true || form.P_C_BIKOU17 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU17.type == "text" || form.P_C_BIKOU17.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU17.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU17.value, 0, 2000, "個人自由項目１７")){
				return errProc(form.P_C_BIKOU17);
			}
		}
	}
	if(form.P_C_BIKOU18 == true || form.P_C_BIKOU18 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU18.type == "text" || form.P_C_BIKOU18.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU18.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU18.value, 0, 2000, "個人自由項目１８")){
				return errProc(form.P_C_BIKOU18);
			}
		}
	}
	if(form.P_C_BIKOU19 == true || form.P_C_BIKOU19 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU19.type == "text" || form.P_C_BIKOU19.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU19.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU19.value, 0, 2000, "個人自由項目１９")){
				return errProc(form.P_C_BIKOU19);
			}
		}
	}
	if(form.P_C_BIKOU20 == true || form.P_C_BIKOU20 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU20.type == "text" || form.P_C_BIKOU20.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU20.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU20.value, 0, 2000, "個人自由項目２０")){
				return errProc(form.P_C_BIKOU20);
			}
		}
	}
	if(form.P_C_BIKOU21 == true || form.P_C_BIKOU21 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU21.type == "text" || form.P_C_BIKOU21.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU21.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU21.value, 0, 2000, "個人自由項目２１")){
				return errProc(form.P_C_BIKOU21);
			}
		}
	}
	if(form.P_C_BIKOU22 == true || form.P_C_BIKOU22 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU22.type == "text" || form.P_C_BIKOU22.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU22.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU22.value, 0, 2000, "個人自由項目２２")){
				return errProc(form.P_C_BIKOU22);
			}
		}
	}
	if(form.P_C_BIKOU23 == true || form.P_C_BIKOU23 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU23.type == "text" || form.P_C_BIKOU23.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU23.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU23.value, 0, 2000, "個人自由項目２３")){
				return errProc(form.P_C_BIKOU23);
			}
		}
	}
	if(form.P_C_BIKOU24 == true || form.P_C_BIKOU24 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU24.type == "text" || form.P_C_BIKOU24.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU24.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU24.value, 0, 2000, "個人自由項目２４")){
				return errProc(form.P_C_BIKOU24);
			}
		}
	}
	if(form.P_C_BIKOU25 == true || form.P_C_BIKOU25 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU25.type == "text" || form.P_C_BIKOU25.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU25.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU25.value, 0, 2000, "個人自由項目２５")){
				return errProc(form.P_C_BIKOU25);
			}
		}
	}
	if(form.P_C_BIKOU26 == true || form.P_C_BIKOU26 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU26.type == "text" || form.P_C_BIKOU26.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU26.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU26.value, 0, 2000, "個人自由項目２６")){
				return errProc(form.P_C_BIKOU26);
			}
		}
	}
	if(form.P_C_BIKOU27 == true || form.P_C_BIKOU27 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU27.type == "text" || form.P_C_BIKOU27.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU27.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU27.value, 0, 2000, "個人自由項目２７")){
				return errProc(form.P_C_BIKOU27);
			}
		}
	}
	if(form.P_C_BIKOU28 == true || form.P_C_BIKOU28 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU28.type == "text" || form.P_C_BIKOU28.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU28.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU28.value, 0, 2000, "個人自由項目２８")){
				return errProc(form.P_C_BIKOU28);
			}
		}
	}
	if(form.P_C_BIKOU29 == true || form.P_C_BIKOU29 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU29.type == "text" || form.P_C_BIKOU29.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU29.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU29.value, 0, 2000, "個人自由項目２９")){
				return errProc(form.P_C_BIKOU29);
			}
		}
	}
	if(form.P_C_BIKOU30 == true || form.P_C_BIKOU30 == "[object HTMLTextAreaElement]"){
		if(form.P_C_BIKOU30.type == "text" || form.P_C_BIKOU30.type == "textarea"){
			if(messageflg == 1 && m_open != form.P_O_BIKOU30.value){
				messageflg = 0;
			}
			if(IsLength(form.P_C_BIKOU30.value, 0, 2000, "個人自由項目３０")){
				return errProc(form.P_C_BIKOU30);
			}
		}
	}

	if(form.m_selGid != undefined){
		if(form.m_selGid.type != "hidden"){
			if(messageflg == 1 && m_open != form.P_O_G_ID.value){
				messageflg = 0;
			}
		}
	}

	if(form.S_AFFILIATION_NAME.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_AFFILIATION.value){
			messageflg = 0;
		}
	}

	if(form.S_OFFICIAL_POSITION.type != "hidden"){
		if(messageflg == 1 && m_open != form.P_O_OFFICIAL.value){
			messageflg = 0;
		}
	}

	if(form.P_HANDLE_NAME != undefined){
		if(form.P_HANDLE_NAME.type != "hidden"){
			if(IsLengthB(form.P_HANDLE_NAME.value, 0, 200, "会議室ニックネーム")){
				return errProc(form.P_HANDLE_NAME);
			}
		}
	}

	if(form.P_MEETING_NAME_MARK != undefined){
		if(form.P_MEETING_NAME_MARK.type != "hidden"){
			if(IsLengthB(form.P_MEETING_NAME_MARK.value, 0, 200, "会議室公開ネーム表示マーク")){
				return errProc(form.P_MEETING_NAME_MARK);
			}
		}
	}


	if(form.P_P_URL.type != "hidden"){
		if(IsLength(form.P_P_URL.value, 0, 100, "URL")){
			return errProc(form.P_P_URL);
		}
		if(IsNarrowPlus(form.P_P_URL.value, "URL")){
			return errProc(form.P_P_URL);
		}
	}

	if(form.P_P_EMAIL.type != "hidden"){
		if(IsLength(form.P_P_EMAIL.value, 0, 100, "E-MAIL")){
			return errProc(form.P_P_EMAIL);
		}
		if(IsNarrowPlus3(form.P_P_EMAIL.value, "E-MAIL")){
			return errProc(form.P_P_EMAIL);
		}
		if(isMail(form.P_P_EMAIL.value, "E-MAIL")){
			return errProc(form.P_P_EMAIL);
		}
	}

	if(form.P_P_EMAIL2 != undefined){
		if(form.P_P_EMAIL2.type != "hidden"){
			if(IsLength(form.P_P_EMAIL2.value, 0, 100, "プライベートE-MAIL再入力")){
				return errProc(form.P_P_EMAIL2);
			}
		}
		if(IsNarrowPlus3(form.P_P_EMAIL2.value, "プライベートE-MAIL再入力")){
			return errProc(form.P_P_EMAIL2);
		}
		if(isMail(form.P_P_EMAIL2.value, "プライベートE-MAIL再入力")){
			return errProc(form.P_P_EMAIL2);
		}
		if(form.P_P_EMAIL.value != form.P_P_EMAIL2.value){
			alert("プライベートE-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
			form.P_P_EMAIL2.value = "";
			form.P_P_EMAIL2.focus();
			return false;
		}
	}

	if(form.P_P_CC_EMAIL != undefined){
		if(form.P_P_CC_EMAIL.type != "hidden"){
			if(IsLength(form.P_P_CC_EMAIL.value, 0, 100, "追加送信先E-MAIL")){
				return errProc(form.P_P_CC_EMAIL);
			}
			if(IsNarrowPlus3(form.P_P_CC_EMAIL.value, "追加送信先E-MAIL")){
				return errProc(form.P_P_CC_EMAIL);
			}
			if(isMail(form.P_P_CC_EMAIL.value, "追加送信先E-MAIL")){
				return errProc(form.P_P_CC_EMAIL);
			}
		}
	}

	if(form.P_P_TEL_1.value != "" || form.P_P_TEL_2.value != "" || form.P_P_TEL_3.value != ""){
		if(form.P_P_TEL_1.value == ""){
			alert("電話番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_TEL_1);
		}
		if(form.P_P_TEL_2.value == ""){
			alert("電話番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_TEL_2);
		}
		if(form.P_P_TEL_3.value == ""){
			alert("電話番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_TEL_3);
		}
		if(IsNarrowTelNum(form.P_P_TEL_1.value, "電話番号")){
			return errProc(form.P_P_TEL_1);
		}
		if(IsNarrowTelNum(form.P_P_TEL_2.value, "電話番号")){
			return errProc(form.P_P_TEL_2);
		}
		if(IsNarrowTelNum(form.P_P_TEL_3.value, "電話番号")){
			return errProc(form.P_P_TEL_3);
		}
		form.P_P_TEL.value = form.P_P_TEL_1.value + "-" + form.P_P_TEL_2.value + "-" + form.P_P_TEL_3.value;

		if(form.P_P_TEL.type != "hidden"){
			if(IsLength(form.P_P_TEL.value, 0, 20, "電話番号")){
				return errProc(form.P_P_TEL_1);
			}
		}
	} else {
		form.P_P_TEL.value = "";
	}


	if(form.P_P_FAX_1.value != "" || form.P_P_FAX_2.value != "" || form.P_P_FAX_3.value != ""){
		if(form.P_P_FAX_1.value == ""){
			alert("FAX番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_FAX_1);
		}
		if(form.P_P_FAX_2.value == ""){
			alert("FAX番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_FAX_2);
		}
		if(form.P_P_FAX_3.value == ""){
			alert("FAX番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_FAX_3);
		}
		if(IsNarrowTelNum(form.P_P_FAX_1.value, "FAX番号")){
			return errProc(form.P_P_FAX_1);
		}
		if(IsNarrowTelNum(form.P_P_FAX_2.value, "FAX番号")){
			return errProc(form.P_P_FAX_2);
		}
		if(IsNarrowTelNum(form.P_P_FAX_3.value, "FAX番号")){
			return errProc(form.P_P_FAX_3);
		}
		form.P_P_FAX.value = form.P_P_FAX_1.value + "-" + form.P_P_FAX_2.value + "-" + form.P_P_FAX_3.value;

		if(form.P_P_FAX.type != "hidden"){
			if(IsLength(form.P_P_FAX.value, 0, 20, "FAX番号")){
				form.P_P_FAX_1.value = form.P_P_FAX_2.value = form.P_P_FAX_3.value = ""
				form.P_P_FAX_1.focus();
				return false;
			}
		}
	} else {
		form.P_P_FAX.value = "";
	}


	if(form.P_P_PTEL_1.value != "" || form.P_P_PTEL_2.value != "" || form.P_P_PTEL_3.value != ""){
		if(form.P_P_PTEL_1.value == ""){
			alert("携帯番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_PTEL_1);
		}
		if(form.P_P_PTEL_2.value == ""){
			alert("携帯番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_PTEL_2);
		}
		if(form.P_P_PTEL_3.value == ""){
			alert("携帯番号\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.P_P_PTEL_3);
		}
		if(IsNarrowTelNum(form.P_P_PTEL_1.value, "携帯番号")){
			return errProc(form.P_P_PTEL_1);
		}
		if(IsNarrowTelNum(form.P_P_PTEL_2.value, "携帯番号")){
			return errProc(form.P_P_PTEL_2);
		}
		if(IsNarrowTelNum(form.P_P_PTEL_3.value, "携帯番号")){
			return errProc(form.P_P_PTEL_3);
		}
		form.P_P_PTEL.value = form.P_P_PTEL_1.value + "-" + form.P_P_PTEL_2.value + "-" + form.P_P_PTEL_3.value;

		if(form.P_P_PTEL.type != "hidden"){
			if(IsLength(form.P_P_PTEL.value, 0, 20, "携帯番号")){
				form.P_P_PTEL_1.value = form.P_P_PTEL_2.value = form.P_P_PTEL_3.value = ""
				form.P_P_PTEL_1.focus();
				return false;
			}
		}
	} else {
		form.P_P_PTEL.value = "";
	}


	if(form.P_P_PMAIL.type != "hidden"){
		if(IsLength(form.P_P_PMAIL.value, 0, 100, "携帯メールアドレス")){
			return errProc(form.P_P_PMAIL);
		}
		if(IsNarrowPlus3(form.P_P_PMAIL.value, "携帯メールアドレス")){
			return errProc(form.P_P_PMAIL);
		}
		if(isMail(form.P_P_PMAIL.value, "携帯メールアドレス")){
			return errProc(form.P_P_PMAIL);
		}
	}

	if(form.P_P_PMAIL2 != undefined){
		if(form.P_P_PMAIL2.type != "hidden"){
			if(IsLength(form.P_P_PMAIL2.value, 0, 100, "携帯メールアドレス再入力")){
				return errProc(form.P_P_PMAIL2);
			}
		}
		if(IsNarrowPlus3(form.P_P_PMAIL2.value, "携帯メールアドレス再入力")){
			return errProc(form.P_P_PMAIL2);
		}
		if(isMail(form.P_P_PMAIL2.value, "携帯メールアドレス再入力")){
			return errProc(form.P_P_PMAIL2);
		}
		if(form.P_P_PMAIL.value != form.P_P_PMAIL2.value){
			alert("携帯メールアドレスの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
			form.P_P_PMAIL2.value = "";
			form.P_P_PMAIL2.focus();
			return false;
		}
	}

	if(form.P_P_POST_u.value != "" || form.P_P_POST_l.value != ""){
		if(form.P_P_POST_u.type != "hidden"){
			if(form.P_P_POST_u.value == ""){
				alert("郵便番号\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.P_P_POST_u);
			}
			if(IsLength(form.P_P_POST_u.value, 3, 3, "郵便番号(上３桁)")){
				return errProc(form.P_P_POST_u);
			}
			if(IsNarrowNum(form.P_P_POST_u.value, "郵便番号")){
				return errProc(form.P_P_POST_u);
			}
		}
		if(form.P_P_POST_l.type != "hidden"){
			if(form.P_P_POST_l.value == ""){
				alert("郵便番号\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.P_P_POST_l);
			}
			if(IsLength(form.P_P_POST_l.value, 4, 4, "郵便番号(下４桁)")){
				return errProc(form.P_P_POST_l);
			}
			if(IsNarrowNum(form.P_P_POST_l.value, "郵便番号")){
				return errProc(form.P_P_POST_l);
			}
			form.P_P_POST.value = form.P_P_POST_u.value + "-" + form.P_P_POST_l.value;
		}
	} else {
		form.P_P_POST.value = "";
	}


	if(form.P_P_ADDRESS.type != "hidden"){
		if(IsLengthB(form.P_P_ADDRESS.value, 0, 500, "住所１")){
			return errProc(form.P_P_ADDRESS);
		}
	}

	if(form.P_P_ADDRESS2.type != "hidden"){
		if(IsLengthB(form.P_P_ADDRESS2.value, 0, 100, "住所２")){
			return errProc(form.P_P_ADDRESS2);
		}
	}

	if((form.P_P_BIRTH_YEAR.value != "") || (form.P_P_BIRTH_MONTH.value != "") && (form.P_P_BIRTH_DAY.value != "")){
		if(IsDateImp(form.m_birthImperialP.value, form.P_P_BIRTH_YEAR, form.P_P_BIRTH_MONTH, form.P_P_BIRTH_DAY, "生年月日") != 0){
			return false;
		}
		form.P_P_BIRTH.value = MakeYMD(form.m_birthImperialP.value, form.P_P_BIRTH_YEAR.value, form.P_P_BIRTH_MONTH.value, form.P_P_BIRTH_DAY.value);
	} else {
		form.P_P_BIRTH.value = "";
	}

	if(form.P_P_HOBBY.type != "hidden"){
		if(IsLengthB(form.P_P_HOBBY.value, 0, 100, "趣味")){
			return errProc(form.P_P_HOBBY);
		}
	}

	if(form.P_P_FAMILY.type != "hidden"){
		if(IsLengthB(form.P_P_FAMILY.value, 0, 30, "家族構成")){
			return errProc(form.P_P_FAMILY);
		}
	}

	if((form.P_P_MOURNING_DATE_START_YEAR.value != "") || (form.P_P_MOURNING_DATE_START_MONTH.value != "") || (form.P_P_MOURNING_DATE_START_DAY.value != "")){
		if(IsDateImp(form.m_mournStartImperialP.value, form.P_P_MOURNING_DATE_START_YEAR, form.P_P_MOURNING_DATE_START_MONTH, form.P_P_MOURNING_DATE_START_DAY, "喪中開始年月日") != 0){
			return false;
		}
		form.P_P_MOURNING_DATE_START.value = MakeYMD(form.m_mournStartImperialP.value, form.P_P_MOURNING_DATE_START_YEAR.value, form.P_P_MOURNING_DATE_START_MONTH.value, form.P_P_MOURNING_DATE_START_DAY.value);
	} else {
		form.P_P_MOURNING_DATE_START.value = "";
	}

	if((form.P_P_MOURNING_DATE_END_YEAR.value != "") || (form.P_P_MOURNING_DATE_END_MONTH.value != "") || (form.P_P_MOURNING_DATE_END_DAY.value != "")){
		if(IsDateImp(form.m_mournEndImperialP.value, form.P_P_MOURNING_DATE_END_YEAR, form.P_P_MOURNING_DATE_END_MONTH, form.P_P_MOURNING_DATE_END_DAY, "喪中終了年月日") != 0){
			return false;
		}
		form.P_P_MOURNING_DATE_END.value = MakeYMD(form.m_mournEndImperialP.value, form.P_P_MOURNING_DATE_END_YEAR.value, form.P_P_MOURNING_DATE_END_MONTH.value, form.P_P_MOURNING_DATE_END_DAY.value);
	} else {
		form.P_P_MOURNING_DATE_END.value = "";
	}

	if(form.P_P_GRADUATION_YEAR != undefined){
		if(form.P_P_GRADUATION_YEAR.type != "hidden"){
			if((form.P_P_GRADUATION_YEAR.value != "")){
				if(IsLengthB(form.P_P_GRADUATION_YEAR.value, 0, 12, "卒業年度（退職年度）")){
					form.P_P_GRADUATION_YEAR.select();
					form.P_P_GRADUATION_YEAR.focus();
					return false;
				}
			}
		}
	}

	if(form.P_P_DEPARTMENT != undefined){
		if(form.P_P_DEPARTMENT.type != "hidden"){
			if(IsLengthB(form.P_P_DEPARTMENT.value, 0, 100, "学部（所属）")){
				return errProc(form.P_P_DEPARTMENT);
			}
		}
	}

	if(form.P_P_GRADUATION_POSITION != undefined){
		if(form.P_P_GRADUATION_POSITION.type != "hidden"){
			if(IsLengthB(form.P_P_GRADUATION_POSITION.value, 0, 100, "卒業時の学校/学部又は退職時の会社名/役職")){
				return errProc(form.P_P_GRADUATION_POSITION);
			}
		}
	}

	if(form.P_P_COUNTRY != undefined){
		if(form.P_P_COUNTRY.type != "hidden"){
			if(IsLengthB(form.P_P_COUNTRY.value, 0, 100, "国名")){
				return errProc(form.P_P_COUNTRY);
			}
		}
	}
	if(messageflg != 0){
		if(m_card_open != "0" && m_open == "0"){
			var openName1;
			var openName2;
			var message = "";
			switch(m_card_open){
				case "0":
				openName1 = "公開しない";
				break;
				case "1":
				openName1 = "一般公開";
				break;
				default:
				openName1 = "会員にのみ公開";
				break;
			}
			switch(m_open){
				case "0":
				openName2 = "公開しない";
				break;
				case "1":
				openName2 = "一般公開";
				break;
				default:
				openName2 = "会員にのみ公開";
				break;
			}
			message = "名刺情報公開設定：【" + openName1 + "】\n各名刺データ公開設定：【" + openName2 + "】\nとなっております。";
			if(m_card_open == "0" || m_open == "0"){
				message = message + "\n\nこのままですと名刺データは公開されませんがよろしいですか？"
			}else{
				message = message + "\n\nこのままですと名刺データは会員にのみ公開されますがよろしいですか？"
			}
			if(!confirm(message)){
				return false;
			}
		}
	}
	return true;
}


function gdCheckInputData(bChange){
	var form = document.mainForm;
	var ifmGetData = getData;
	var ifmSetUrl;


	if(bChange == false){
		if(form.G_G_ID.type != "hidden"){
			if(IsNull(form.G_G_ID.value, "組織ID")){
				return errProc(form.G_G_ID);
			}
			if(IsLength(form.G_G_ID.value, 4, 20, "組織ID")){
				return errProc(form.G_G_ID);
			}
			if(IsNarrow(form.G_G_ID.value, "組織ID")){
				return errProc(form.G_G_ID);
			}
		}
	}

	if(form.G_PASSWORD.type != "hidden"){
		if(IsNull(form.G_PASSWORD.value, "パスワード")){
			return errProc(form.G_PASSWORD);
		}
		if(IsLength(form.G_PASSWORD.value, 4, 20, "パスワード")){
			return errProc(form.G_PASSWORD);
		}
		if(IsNarrowPassword(form.G_PASSWORD.value, "パスワード")){
			return errProc(form.G_PASSWORD);
		}



		if(form.G_PASSWORD2.type != "hidden"){
			if(form.G_PASSWORD.value != form.G_PASSWORD2.value){
				alert("パスワードの内容と確認入力の内容が一致しません。\nパスワードをもう一度確認して下さい");
				form.G_PASSWORD2.value = "";
				form.G_PASSWORD2.focus();
				return false;
			}
		}
	}


	if(form.G_NAME.type != "hidden"){
		if(IsLengthB(form.G_NAME.value, 0, 1000, "組織名")){
			return errProc(form.G_NAME);
		}
	}

	if(form.G_KANA.type != "hidden"){
		if(IsLengthB(form.G_KANA.value, 0, 1000, "組織フリガナ")){
			return errProc(form.G_KANA);
		}
	}

	if(form.G_SNAME.type != "hidden"){
		if(IsLengthB(form.G_SNAME.value, 0, 100, "組織略称")){
			return errProc(form.G_SNAME);
		}
	}

	if(form.G_CATEGORY_CODE.type != "hidden"){
		if(IsLength(form.G_CATEGORY_CODE.value, 0, 6, "業種コード")){
			return errProc(form.G_CATEGORY_CODE);
		}
		if(IsNarrow(form.G_CATEGORY_CODE.value, "業種コード")){
			return errProc(form.G_CATEGORY_CODE);
		}
	}

	if(form.G_CATEGORY.type != "hidden"){
		if(IsLengthB(form.G_CATEGORY.value, 0, 1000, "業種")){
			return errProc(form.G_CATEGORY);
		}
	}

	if(form.G_URL.type != "hidden"){
		if(IsLength(form.G_URL.value, 0, 100, "組織URL")){
			return errProc(form.G_URL);
		}
		if(IsNarrowPlus(form.G_URL.value, "組織URL")){
			return errProc(form.G_URL);
		}
	}


	if(form.G_P_URL != undefined){
		if(form.G_P_URL.type != "hidden"){
			if(IsLength(form.G_P_URL.value, 0, 100, "組織携帯URL")){
				return errProc(form.G_P_URL);
			}
			if(IsNarrowPlus(form.G_P_URL.value, "組織携帯URL")){
				return errProc(form.G_P_URL);
			}
		}
	}


	if(form.G_EMAIL.type != "hidden"){
		if(IsLength(form.G_EMAIL.value, 0, 100, "組織E-MAIL")){
			return errProc(form.G_EMAIL);
		}
		if(IsNarrowPlus3(form.G_EMAIL.value, "組織E-MAIL")){
			return errProc(form.G_EMAIL);
		}
		if(isMail(form.G_EMAIL.value, "組織E-MAIL")){
			return errProc(form.G_EMAIL);
		}
	}

	if(form.G_EMAIL2 != undefined){
		if(form.G_EMAIL2.type != "hidden"){
			if(IsLength(form.G_EMAIL2.value, 0, 100, "組織E-MAIL再入力")){
				return errProc(form.G_EMAIL2);
			}
		}
		if(IsNarrowPlus3(form.G_EMAIL2.value, "組織E-MAIL再入力")){
			return errProc(form.G_EMAIL2);
		}
		if(isMail(form.G_EMAIL2.value, "組織E-MAIL再入力")){
			return errProc(form.G_EMAIL2);
		}
		if(form.G_EMAIL.value != form.G_EMAIL2.value){
			alert("組織E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
			form.G_EMAIL2.value = "";
			form.G_EMAIL2.focus();
			return false;
		}
	}

	if(form.G_CC_EMAIL != undefined){
		if(form.G_CC_EMAIL.type != "hidden"){
			if(IsLength(form.G_CC_EMAIL.value, 0, 100, "組織追加送信先E-MAIL")){
				return errProc(form.G_CC_EMAIL);
			}
			if(IsNarrowPlus3(form.G_CC_EMAIL.value, "組織追加送信先E-MAIL")){
				return errProc(form.G_CC_EMAIL);
			}
			if(isMail(form.G_CC_EMAIL.value, "組織追加送信先E-MAIL")){
				return errProc(form.G_CC_EMAIL);
			}
		}
	}


	if(form.G_TEL_1.value != "" || form.G_TEL_2.value != "" || form.G_TEL_3.value != ""){
		if(form.G_TEL_1.value == ""){
			alert("組織TEL\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.G_TEL_1);
		}
		if(form.G_TEL_2.value == ""){
			alert("組織TEL\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.G_TEL_2);
		}
		if(form.G_TEL_3.value == ""){
			alert("組織TEL\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.G_TEL_3);
		}
		if(IsNarrowTelNum(form.G_TEL_1.value, "組織TEL")){
			return errProc(form.G_TEL_1);
		}
		if(IsNarrowTelNum(form.G_TEL_2.value, "組織TEL")){
			return errProc(form.G_TEL_2);
		}
		if(IsNarrowTelNum(form.G_TEL_3.value, "組織TEL")){
			return errProc(form.G_TEL_3);
		}
		form.G_TEL.value = form.G_TEL_1.value + "-" + form.G_TEL_2.value + "-" + form.G_TEL_3.value;
		if(form.G_TEL.type != "hidden"){
			if(IsLength(form.G_TEL.value, 0, 20, "組織TEL")){
				form.G_TEL_1.focus();
				return false;
			}
		}
	} else {
		form.G_TEL.value = "";
	}

	if(form.G_FAX_1.value != "" || form.G_FAX_2.value != "" || form.G_FAX_3.value != ""){
		if(form.G_FAX_1.value == ""){
			alert("組織FAX\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.G_FAX_1);
		}
		if(form.G_FAX_2.value == ""){
			alert("組織FAX\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.G_FAX_2);
		}
		if(form.G_FAX_3.value == ""){
			alert("組織FAX\nを登録する場合は\n全ての欄を入力して下さい");
			return errProc(form.G_FAX_3);
		}
		if(IsNarrowTelNum(form.G_FAX_1.value, "組織FAX")){
			return errProc(form.G_FAX_1);
		}
		if(IsNarrowTelNum(form.G_FAX_2.value, "組織FAX")){
			return errProc(form.G_FAX_2);
		}
		if(IsNarrowTelNum(form.G_FAX_3.value, "組織FAX")){
			return errProc(form.G_FAX_3);
		}
		form.G_FAX.value = form.G_FAX_1.value + "-" + form.G_FAX_2.value + "-" + form.G_FAX_3.value;
		if(form.G_FAX.type != "hidden"){
			if(IsLength(form.G_FAX.value, 0, 20, "組織FAX")){
				form.G_FAX_1.value = form.G_FAX_2.value = form.G_FAX_3.value = ""
				form.G_FAX_1.focus();
				return false;
			}
		}
	} else {
		form.G_FAX.value = "";
	}

	if((form.G_FOUND_YEAR.value != "") || (form.G_FOUND_MONTH.value != "") || (form.G_FOUND_DAY.value != "")){
		if(IsDateImp(form.m_foundImperialG.value, form.G_FOUND_YEAR, form.G_FOUND_MONTH, form.G_FOUND_DAY, "設立年月日")){
			return false;
		}
		form.G_FOUND_DATE.value = MakeYMD(form.m_foundImperialG.value, form.G_FOUND_YEAR.value, form.G_FOUND_MONTH.value, form.G_FOUND_DAY.value);
	} else {
		form.G_FOUND_DATE.value = "";
	}

	if(form.G_CAPITAL.type != "hidden"){
		if(IsLength(form.G_CAPITAL.value, 0, 13, "資本金")){
			return errProc(form.G_CAPITAL);
		}
		if(IsNarrowNum(form.G_CAPITAL.value, "資本金")){
			return errProc(form.G_CAPITAL);
		}
	}

	if(form.G_REPRESENTATIVE.type != "hidden"){
		if(IsLengthB(form.G_REPRESENTATIVE.value, 0, 100, "代表者")){
			return errProc(form.G_REPRESENTATIVE);
		}
	}

	if(form.G_REPRESENTATIVE_KANA.type != "hidden"){
		if(IsLengthB(form.G_REPRESENTATIVE_KANA.value, 0, 100, "代表者フリガナ")){
			return errProc(form.G_REPRESENTATIVE_KANA);
		}
	}

	if(form.G_POST_u.value != "" || form.G_POST_l.value != ""){
		if(form.G_POST_u.type != "hidden"){
			if(form.G_POST_u.value == ""){
				alert("組織〒\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.G_POST_u);
			}
			if(IsLength(form.G_POST_u.value, 3, 3, "組織〒(上３桁)")){
				return errProc(form.G_POST_u);
			}
			if(IsNarrowNum(form.G_POST_u.value, "組織〒")){
				return errProc(form.G_POST_u);
			}
		}
		if(form.G_POST_l.type != "hidden"){
			if(form.G_POST_l.value == ""){
				alert("組織〒\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.G_POST_l);
			}
			if(IsLength(form.G_POST_l.value, 4, 4, "組織〒(下４桁)")){
				return errProc(form.G_POST_l);
			}
			if(IsNarrowNum(form.G_POST_l.value, "組織〒")){
				return errProc(form.G_POST_l);
			}
			form.G_POST.value = form.G_POST_u.value + "-" + form.G_POST_l.value;
		}
	} else {
		form.G_POST.value = "";
	}

	if('dmshibuya' == 'jeca2'){
		if(form.G_STA.value == ""){
			alert("組織都道府県を指定してください。");
			return errProc(form.G_STA);
		}
	}

	if(form.G_ADDRESS.type != "hidden"){
		if(IsLengthB(form.G_ADDRESS.value, 0, 500, "組織住所１")){
			return errProc(form.G_ADDRESS);
		}
	}

	if(form.G_ADDRESS2.type != "hidden"){
		if(IsLengthB(form.G_ADDRESS2.value, 0, 100, "組織住所２")){
			return errProc(form.G_ADDRESS2);
		}
	}

	if(form.G_APPEAL.type != "hidden"){

		if(IsLength(form.G_APPEAL.value, 0, 500, "組織アピール")){
			return errProc(form.G_APPEAL);
		}

	}

	if(form.G_APPEAL.value != ""){
		form.G_O_APPEAL.value = "1";
	}else{
		form.G_O_APPEAL.value = "0";
	}

	if(form.G_KEYWORD.type != "hidden"){
		if(IsLength(form.G_KEYWORD.value, 0, 500, "事務局検索用企業コメント")){
			return errProc(form.G_KEYWORD);
		}
	}

	if(form.G_IMG.value != ""){
		form.G_O_IMG.value = "1";
	}else{
		form.G_O_IMG.value = "0";
	}

	if(form.G_LOGO.value != ""){
		form.G_O_LOGO.value = "1";
	}else{
		form.G_O_LOGO.value = "0";
	}

	if(form.G_HOGEN_CODE.type != "hidden"){
		if(IsLength(form.G_HOGEN_CODE.value, 0, 6, "法源コード")){
			return errProc(form.G_HOGEN_CODE);
		}
		if(IsNarrow(form.G_HOGEN_CODE.value, "法源コード")){
			return errProc(form.G_HOGEN_CODE);
		}
	}

	if(form.G_BIKOU1 == true){
		if(form.G_BIKOU1.type == "text"){
			if(IsLength(form.G_BIKOU1.value, 0, 2000, "組織自由項目１")){
				return errProc(form.G_BIKOU1);
			}
		}
	}
	if(form.G_BIKOU2 == true){
		if(form.G_BIKOU2.type == "text"){
			if(IsLength(form.G_BIKOU2.value, 0, 2000, "組織自由項目２")){
				return errProc(form.G_BIKOU2);
			}
		}
	}
	if(form.G_BIKOU3 == true){
		if(form.G_BIKOU3.type == "text"){
			if(IsLength(form.G_BIKOU3.value, 0, 2000, "組織自由項目３")){
				return errProc(form.G_BIKOU3);
			}
		}
	}
	if(form.G_BIKOU4 == true){
		if(form.G_BIKOU4.type == "text"){
			if(IsLength(form.G_BIKOU4.value, 0, 2000, "組織自由項目４")){
				return errProc(form.G_BIKOU4);
			}
		}
	}
	if(form.G_BIKOU5 == true){
		if(form.G_BIKOU5.type == "text"){
			if(IsLength(form.G_BIKOU5.value, 0, 2000, "組織自由項目５")){
				return errProc(form.G_BIKOU5);
			}
		}
	}
	if(form.G_BIKOU6 == true){
		if(form.G_BIKOU6.type == "text"){
			if(IsLength(form.G_BIKOU6.value, 0, 2000, "組織自由項目６")){
				return errProc(form.G_BIKOU6);
			}
		}
	}
	if(form.G_BIKOU7 == true){
		if(form.G_BIKOU7.type == "text"){
			if(IsLength(form.G_BIKOU7.value, 0, 2000, "組織自由項目７")){
				return errProc(form.G_BIKOU7);
			}
		}
	}
	if(form.G_BIKOU8 == true){
		if(form.G_BIKOU8.type == "text"){
			if(IsLength(form.G_BIKOU8.value, 0, 2000, "組織自由項目８")){
				return errProc(form.G_BIKOU8);
			}
		}
	}
	if(form.G_BIKOU9 == true){
		if(form.G_BIKOU9.type == "text"){
			if(IsLength(form.G_BIKOU9.value, 0, 2000, "組織自由項目９")){
				return errProc(form.G_BIKOU9);
			}
		}
	}
	if(form.G_BIKOU10 == true){
		if(form.G_BIKOU10.type == "text"){
			if(IsLength(form.G_BIKOU10.value, 0, 2000, "組織自由項目１０")){
				return errProc(form.G_BIKOU10);
			}
		}
	}
	if(form.G_BIKOU11 == true){
		if(form.G_BIKOU11.type == "text"){
			if(IsLength(form.G_BIKOU11.value, 0, 2000, "組織自由項目１１")){
				return errProc(form.G_BIKOU11);
			}
		}
	}
	if(form.G_BIKOU12 == true){
		if(form.G_BIKOU12.type == "text"){
			if(IsLength(form.G_BIKOU12.value, 0, 2000, "組織自由項目１２")){
				return errProc(form.G_BIKOU12);
			}
		}
	}
	if(form.G_BIKOU13 == true){
		if(form.G_BIKOU13.type == "text"){
			if(IsLength(form.G_BIKOU13.value, 0, 2000, "組織自由項目１３")){
				return errProc(form.G_BIKOU13);
			}
		}
	}
	if(form.G_BIKOU14 == true){
		if(form.G_BIKOU14.type == "text"){
			if(IsLength(form.G_BIKOU14.value, 0, 2000, "組織自由項目１４")){
				return errProc(form.G_BIKOU14);
			}
		}
	}
	if(form.G_BIKOU15 == true){
		if(form.G_BIKOU15.type == "text"){
			if(IsLength(form.G_BIKOU15.value, 0, 2000, "組織自由項目１５")){
				return errProc(form.G_BIKOU15);
			}
		}
	}

	if(form.G_BIKOU16 == true){
		if(form.G_BIKOU16.type == "text"){
			if(IsLength(form.G_BIKOU16.value, 0, 2000, "組織自由項目１６")){
				return errProc(form.G_BIKOU16);
			}
		}
	}
	if(form.G_BIKOU17 == true){
		if(form.G_BIKOU17.type == "text"){
			if(IsLength(form.G_BIKOU17.value, 0, 2000, "組織自由項目１７")){
				return errProc(form.G_BIKOU17);
			}
		}
	}
	if(form.G_BIKOU18 == true){
		if(form.G_BIKOU18.type == "text"){
			if(IsLength(form.G_BIKOU18.value, 0, 2000, "組織自由項目１８")){
				return errProc(form.G_BIKOU18);
			}
		}
	}
	if(form.G_BIKOU19 == true){
		if(form.G_BIKOU19.type == "text"){
			if(IsLength(form.G_BIKOU19.value, 0, 2000, "組織自由項目１９")){
				return errProc(form.G_BIKOU19);
			}
		}
	}
	if(form.G_BIKOU20 == true){
		if(form.G_BIKOU20.type == "text"){
			if(IsLength(form.G_BIKOU20.value, 0, 2000, "組織自由項目２０")){
				return errProc(form.G_BIKOU20);
			}
		}
	}
	if(form.G_BIKOU21 == true){
		if(form.G_BIKOU21.type == "text"){
			if(IsLength(form.G_BIKOU21.value, 0, 2000, "組織自由項目２１")){
				return errProc(form.G_BIKOU21);
			}
		}
	}
	if(form.G_BIKOU22 == true){
		if(form.G_BIKOU22.type == "text"){
			if(IsLength(form.G_BIKOU22.value, 0, 2000, "組織自由項目２２")){
				return errProc(form.G_BIKOU22);
			}
		}
	}
	if(form.G_BIKOU23 == true){
		if(form.G_BIKOU23.type == "text"){
			if(IsLength(form.G_BIKOU23.value, 0, 2000, "組織自由項目２３")){
				return errProc(form.G_BIKOU23);
			}
		}
	}
	if(form.G_BIKOU24 == true){
		if(form.G_BIKOU24.type == "text"){
			if(IsLength(form.G_BIKOU24.value, 0, 2000, "組織自由項目２４")){
				return errProc(form.G_BIKOU24);
			}
		}
	}
	if(form.G_BIKOU25 == true){
		if(form.G_BIKOU25.type == "text"){
			if(IsLength(form.G_BIKOU25.value, 0, 2000, "組織自由項目２５")){
				return errProc(form.G_BIKOU25);
			}
		}
	}
	if(form.G_BIKOU26 == true){
		if(form.G_BIKOU26.type == "text"){
			if(IsLength(form.G_BIKOU26.value, 0, 2000, "組織自由項目２６")){
				return errProc(form.G_BIKOU26);
			}
		}
	}
	if(form.G_BIKOU27 == true){
		if(form.G_BIKOU27.type == "text"){
			if(IsLength(form.G_BIKOU27.value, 0, 2000, "組織自由項目２７")){
				return errProc(form.G_BIKOU27);
			}
		}
	}
	if(form.G_BIKOU28 == true){
		if(form.G_BIKOU28.type == "text"){
			if(IsLength(form.G_BIKOU28.value, 0, 2000, "組織自由項目２８")){
				return errProc(form.G_BIKOU28);
			}
		}
	}
	if(form.G_BIKOU29 == true){
		if(form.G_BIKOU29.type == "text"){
			if(IsLength(form.G_BIKOU29.value, 0, 2000, "組織自由項目２９")){
				return errProc(form.G_BIKOU29);
			}
		}
	}
	if(form.G_BIKOU30 == true){
		if(form.G_BIKOU30.type == "text"){
			if(IsLength(form.G_BIKOU30.value, 0, 2000, "組織自由項目３０")){
				return errProc(form.G_BIKOU30);
			}
		}
	}


	if(form.G_WORKFORCE != undefined){
		if(form.G_WORKFORCE.type != "hidden"){
			if(IsLength(form.G_WORKFORCE.value, 0, 5, "従業員数")){
				return errProc(form.G_WORKFORCE);
			}
			if(IsNarrowNum(form.G_WORKFORCE.value, "従業員数")){
				return errProc(form.G_WORKFORCE);
			}
		}
	}

	if(form.G_PARTTIME != undefined){
		if(form.G_PARTTIME.type != "hidden"){
			if(IsLength(form.G_PARTTIME.value, 0, 5, "パート・アルバイト数")){
				return errProc(form.G_PARTTIME);
			}
			if(IsNarrowNum(form.G_PARTTIME.value, "パート・アルバイト数")){
				return errProc(form.G_PARTTIME);
			}
		}
	}

	if(form.G_ANNVAL_BUSINESS != undefined){
		if(form.G_ANNVAL_BUSINESS.type != "hidden"){
			if(IsLength(form.G_ANNVAL_BUSINESS.value, 0, 14, "年商")){
				return errProc(form.G_ANNVAL_BUSINESS);
			}
			if(IsNarrowNum(form.G_ANNVAL_BUSINESS.value, "年商")){
				return errProc(form.G_ANNVAL_BUSINESS);
			}
		}
	}

	if(form.G_LICENSE != undefined){
		if(form.G_LICENSE.type != "hidden"){
			if(IsLength(form.G_LICENSE.value, 0, 100, "資格・免許類")){
				return errProc(form.G_LICENSE);
			}
		}
	}

	if(form.G_PATENT != undefined){
		if(form.G_PATENT.type != "hidden"){
			if(IsLength(form.G_PATENT.value, 0, 100, "特許関係")){
				return errProc(form.G_PATENT);
			}
		}
	}

	if(form.G_HOLD_CAR != undefined){
		if(form.G_HOLD_CAR.type != "hidden"){
			if(IsLength(form.G_HOLD_CAR.value, 0, 4, "車台数")){
				return errProc(form.G_HOLD_CAR);
			}
			if(IsNarrowNum(form.G_HOLD_CAR.value, "車台数")){
				return errProc(form.G_HOLD_CAR);
			}
		}
	}

	if(form.G_E_TAX != undefined){
		if(form.G_E_TAX.type != "hidden"){
			if(IsLength(form.G_E_TAX.value, 0, 100, "e_Tax")){
				return errProc(form.G_E_TAX);
			}
		}
	}

	if(form.G_ADD_MARKETING16 != undefined){
		if(form.G_ADD_MARKETING16.type != "hidden"){
			if(IsLength(form.G_ADD_MARKETING16.value, 0, 50, "マーケティング自由項目１６")){
				return errProc(form.G_ADD_MARKETING16);
			}
		}
	}
	if(form.G_ADD_MARKETING17 != undefined){
		if(form.G_ADD_MARKETING17.type != "hidden"){
			if(IsLength(form.G_ADD_MARKETING17.value, 0, 50, "マーケティング自由項目１７")){
				return errProc(form.G_ADD_MARKETING17);
			}
		}
	}
	if(form.G_ADD_MARKETING18 != undefined){
		if(form.G_ADD_MARKETING18.type != "hidden"){
			if(IsLength(form.G_ADD_MARKETING18.value, 0, 50, "マーケティング自由項目１８")){
				return errProc(form.G_ADD_MARKETING18);
			}
		}
	}
	if(form.G_ADD_MARKETING19 != undefined){
		if(form.G_ADD_MARKETING19.type != "hidden"){
			if(IsLength(form.G_ADD_MARKETING19.value, 0, 50, "マーケティング自由項目１９")){
				return errProc(form.G_ADD_MARKETING19);
			}
		}
	}
	if(form.G_ADD_MARKETING20 != undefined){
		if(form.G_ADD_MARKETING20.type != "hidden"){
			if(IsLength(form.G_ADD_MARKETING20.value, 0, 50, "マーケティング自由項目２０")){
				return errProc(form.G_ADD_MARKETING20);
			}
		}
	}
	var groupRegCheck = true;

	if(form.NoneRMf != undefined){
		if(form.NoneRMf.value == "1"){
			groupRegCheck = false;
		}
	}
	if(groupRegCheck == true){
		if(form.G_G_ID.type != "hidden"){

			ifmSetUrl = "../rs/commonRsSearch.asp?fncName=CheckGid&gid=" + form.G_G_ID.value;
			ifmGetData.location.href = ifmSetUrl;
		}
	}
	return true;
}

function retSearchVal_CheckGid(retSearchVal){
	var form = document.mainForm;
	if(retSearchVal != 0){
		alert("入力された組織IDは既に使用されています。\n別の組織IDを使用してください。");
		return errProc(form.G_G_ID);
	}
}


function sdCheckInputData(bMdReg){
	var form = document.mainForm;

	if(form.S_AFFILIATION_NAME.type != "hidden"){
		if(IsLengthB(form.S_AFFILIATION_NAME.value, 0, 100, "所属")){
			return errProc(form.S_AFFILIATION_NAME);
		}
	}

	if(form.S_OFFICIAL_POSITION.type != "hidden"){
		if(IsLengthB(form.S_OFFICIAL_POSITION.value, 0, 100, "役職")){
			return errProc(form.S_OFFICIAL_POSITION);
		}
	}

	if(bMdReg){
		if(form.m_chg == "0"){
			if(form.AFFILIATION_NAME2.type != "hidden"){
				if(IsLengthB(form.AFFILIATION_NAME2.value, 0, 100, "所属")){
					return errProc(form.AFFILIATION_NAME2);
				}
			}
			if(form.OFFICIAL_POSITION2.type != "hidden"){
				if(IsLengthB(form.OFFICIAL_POSITION2.value, 0, 100, "役職")){
					return errProc(form.OFFICIAL_POSITION2);
				}
			}
		}
	}

	if(form.S_X_COMMENT != undefined){
		if(form.S_X_COMMENT.type != "hidden"){
			if(IsLength(form.S_X_COMMENT.value, 0, 500, "コメント")){
				return errProc(form.S_X_COMMENT);
			}
		}
	}
	return true;
}


function mdCheckInputData(bMDReg){
	var form = document.mainForm;

	if(form.M_LG_G_ID != undefined){
		if(form.M_LG_G_ID.type != "hidden"){
			if(form.M_LG_G_ID.value == ""){
				alert("下部組織を指定してください。");
				return errProc(form.M_LG_G_ID);
			}
			if(IsNarrow(form.M_LG_G_ID.value, "下部組織ID")){
				return errProc(form.M_LG_G_ID);
			}
		}
	}

	if(form.M_ADMISSION_DATE_Y != undefined){
		if(form.M_ADMISSION_DATE_Y.type != "hidden"){
			if((form.M_ADMISSION_DATE_Y.value != "") || (form.M_ADMISSION_DATE_M.value != "") || (form.M_ADMISSION_DATE_D.value != "")){
				if(IsDateImp(form.m_admImperialM.value, form.M_ADMISSION_DATE_Y, form.M_ADMISSION_DATE_M, form.M_ADMISSION_DATE_D, "入会年月日")){
					return false;
				}
				form.M_ADMISSION_DATE.value = MakeYMD(form.m_admImperialM.value, form.M_ADMISSION_DATE_Y.value, form.M_ADMISSION_DATE_M.value, form.M_ADMISSION_DATE_D.value);
			} else {
				form.M_ADMISSION_DATE.value = "";
			}
		}
	}

	if(form.M_WITHDRAWAL_DATE_Y != undefined){
		if(form.M_WITHDRAWAL_DATE_Y.type != "hidden"){
			if((form.M_WITHDRAWAL_DATE_Y.value != "") || (form.M_WITHDRAWAL_DATE_M.value != "") || (form.M_WITHDRAWAL_DATE_D.value != "")){
				if(IsDateImp(form.m_witImperialM.value, form.M_WITHDRAWAL_DATE_Y, form.M_WITHDRAWAL_DATE_M, form.M_WITHDRAWAL_DATE_D, "退会年月日")){
					return false;
				}
				form.M_WITHDRAWAL_DATE.value = MakeYMD(form.m_witImperialM.value, form.M_WITHDRAWAL_DATE_Y.value, form.M_WITHDRAWAL_DATE_M.value, form.M_WITHDRAWAL_DATE_D.value);
			} else {
				form.M_WITHDRAWAL_DATE.value = "";
			}
		}
	}

	if(form.M_CHANGE_DATE_Y != undefined){
		if(form.M_CHANGE_DATE_Y.type != "hidden"){
			if((form.M_CHANGE_DATE_Y.value != "") || (form.M_CHANGE_DATE_M.value != "") || (form.M_CHANGE_DATE_D.value != "")){
				if(IsDateImp(form.m_chaImperialM.value, form.M_CHANGE_DATE_Y, form.M_CHANGE_DATE_M, form.M_CHANGE_DATE_D, "異動年月日")){
					return false;
				}
				form.M_CHANGE_DATE.value = MakeYMD(form.m_chaImperialM.value, form.M_CHANGE_DATE_Y.value, form.M_CHANGE_DATE_M.value, form.M_CHANGE_DATE_D.value);
			} else {
				form.M_CHANGE_DATE.value = "";
			}
		}
	}

	if(form.M_CHANGE_DATE_Y != undefined){
		if(form.M_CHANGE_DATE_Y.type != "hidden"){
			if(IsLength(form.M_CHANGE_REASON.value, 0, 50, "移動理由") != 0){
				return errProc(form.M_CHANGE_REASON);
			}
		}
	}

	if(form.M_CLAIM_CLASS != undefined && form.M_FEE_RANK != undefined){
		if(form.M_CLAIM_CLASS.type != "hidden" && form.M_FEE_RANK.type != "hidden"){
			if(form.M_CLAIM_CLASS.value != ""){
				if(form.M_FEE_RANK.value == ""){
					alert("会費ランクを入力して下さい。");
					return errProc(form.M_FEE_RANK);
				}
			}
		}
	}

	if(form.M_CLAIM_CLASS != undefined && form.M_CLAIM_CYCLE != undefined){
		if(form.M_CLAIM_CLASS.type != "hidden" && form.M_CLAIM_CYCLE.type != "hidden"){
			if(form.M_CLAIM_CLASS.value != ""){
				if(form.M_CLAIM_CYCLE.value == ""){
					alert("請求サイクルを入力して下さい。");
					return errProc(form.M_CLAIM_CYCLE);
				}
			}
		}
	}

	if(form.M_FEE_MEMO != undefined){
		if(form.M_FEE_MEMO.type != "hidden"){
			if(IsLengthB(form.M_FEE_MEMO.value, 0, 100, "会費メモ") != 0){
				return errProc(form.M_FEE_MEMO);
			}
		}
	}

	if(form.M_BANK_CODE != undefined){
		if(form.M_BANK_CODE.type != "hidden"){
			if(form.M_CLAIM_CLASS != undefined){
				if(form.M_CLAIM_CLASS.type != "hidden"){
					if(form.M_CLAIM_CLASS.value == "0"){
						if(form.M_BANK_CODE.value == ""){
							if(form.M_BANK_CODE.disabled == true){
								alert("銀行コードが未登録です。\n請求先指定を変更、または\n指定先の銀行コードを入力してください。");
								return errProc(form.M_CLAIMDEST);
							}else{
								alert("銀行コードを入力して下さい。");
								return errProc(form.M_BANK_CODE);
							}
						}
					}
				}
			}
		}
	}

	if(form.M_BRANCH_CODE != undefined){
		if(form.M_BRANCH_CODE.type != "hidden"){
			if(form.M_CLAIM_CLASS != undefined){
				if(form.M_CLAIM_CLASS.type != "hidden"){
					if(form.M_CLAIM_CLASS.value == "0"){
						if(form.M_BRANCH_CODE.value == ""){
							if(form.M_BRANCH_CODE.disabled == true){
								if(form.M_BANK_CODE != undefined){
//ゆうちょ以外
if (form.M_BANK_CODE.value != "9900"){
	alert("支店コードが未登録です。\n請求先指定を変更、または\n指定先の支店コードを入力してください。");
	return errProc(form.M_CLAIMDEST);
}
}
}else{
	if(form.M_BANK_CODE != undefined){
//ゆうちょ以外
if (form.M_BANK_CODE.value != "9900"){
	alert("支店コードを入力して下さい。");
	return errProc(form.M_BRANCH_CODE);
}
}
}
}
}
}
}
}
}

if(form.M_ACCAUNT_NUMBER != undefined){
	if(form.M_ACCAUNT_NUMBER.type != "hidden"){
		if(form.M_CLAIM_CLASS != undefined){
			if(form.M_CLAIM_CLASS.type != "hidden"){
				if(form.M_CLAIM_CLASS.value == "0"){
					if(form.M_BANK_CODE.value != "9900"){
						if(IsNull(form.M_ACCAUNT_NUMBER.value, "口座番号")){
							return errProc(form.M_ACCAUNT_NUMBER);
						}
					}
				}
			}
		}
		if(IsLength(form.M_ACCAUNT_NUMBER.value, 0, 7, "口座番号")){
			return errProc(form.M_ACCAUNT_NUMBER);
		}
		if(IsNarrowNum(form.M_ACCAUNT_NUMBER.value, "口座番号")){
			return errProc(form.M_ACCAUNT_NUMBER);
		}
	}
}

if(form.M_ACCAUNT_NAME != undefined){
	if(form.M_ACCAUNT_NAME.type != "hidden"){
		if(form.M_CLAIM_CLASS != undefined){
			if(form.M_CLAIM_CLASS.type != "hidden"){
				if(form.M_CLAIM_CLASS.value == "0"){
					if(IsNull(form.M_ACCAUNT_NAME.value, "口座名義")){
						return errProc(form.M_ACCAUNT_NAME);
					}
				}
			}
		}
		if(IsLength(form.M_ACCAUNT_NAME.value, 0, 30, "口座名義")){
			return errProc(form.M_ACCAUNT_NAME);
		}
		if(IsNarrowAccaunt(form.M_ACCAUNT_NAME.value, "口座名義")){
			return errProc(form.M_ACCAUNT_NAME);
		}
	}
}

if(form.M_CUST_NO != undefined){
	if(form.M_CUST_NO.type != "hidden"){
		if(IsLength(form.M_CUST_NO.value, 0, 12, "顧客番号")){
			return errProc(form.M_CUST_NO);
		}
		if(IsNarrowNum(form.M_CUST_NO.value, "顧客番号")){
			return errProc(form.M_CUST_NO);
		}
	}
}

if(form.M_SAVINGS_CODE != undefined){
	if(form.M_SAVINGS_CODE.type != "hidden"){
		if(form.M_CLAIM_CLASS != undefined){
			if(form.M_CLAIM_CLASS.type != "hidden"){
				if(form.M_CLAIM_CLASS.value == "0"){
					if(form.M_BANK_CODE.value == "9900"){
						if(IsNull(form.M_SAVINGS_CODE.value, "貯金記号")){
							return errProc(form.M_SAVINGS_CODE);
						}
					}
				}
			}
		}
		if(IsLengthF(form.M_SAVINGS_CODE.value, 5, "貯金記号")){
			return errProc(form.M_SAVINGS_CODE);
		}
		if(IsLength(form.M_SAVINGS_CODE.value, 0, 5, "貯金記号")){
			return errProc(form.M_SAVINGS_CODE);
		}
		if(IsNarrowNum(form.M_SAVINGS_CODE.value, "貯金記号")){
			return errProc(form.M_SAVINGS_CODE);
		}
	}
}

if(form.M_SAVINGS_NUMBER != undefined){
	if(form.M_SAVINGS_NUMBER.type != "hidden"){
		if(form.M_CLAIM_CLASS != undefined){
			if(form.M_CLAIM_CLASS.type != "hidden"){
				if(form.M_CLAIM_CLASS.value == "0"){
					if(form.M_BANK_CODE.value == "9900"){
						if(IsNull(form.M_SAVINGS_NUMBER.value, "貯金番号")){
							return errProc(form.M_SAVINGS_NUMBER);
						}
					}
				}
			}
		}
		if(IsLengthF(form.M_SAVINGS_NUMBER.value, 8, "貯金番号")){
			return errProc(form.M_SAVINGS_NUMBER);
		}
		if(IsLength(form.M_SAVINGS_NUMBER.value, 0, 8, "貯金番号")){
			return errProc(form.M_SAVINGS_NUMBER);
		}
		if(IsNarrowNum(form.M_SAVINGS_NUMBER.value, "貯金番号")){
			return errProc(form.M_SAVINGS_NUMBER);
		}
	}
}

if(form.M_CO_G_NAME != undefined){
	if(form.M_CO_G_NAME.type != "hidden"){
		if(IsLengthB(form.M_CO_G_NAME.value, 0, 200, "連絡先組織名")){
			return errProc(form.M_CO_G_NAME);
		}
	}
}

if(form.M_CO_G_KANA != undefined){
	if(form.M_CO_G_KANA.type != "hidden"){
		if(IsLengthB(form.M_CO_G_KANA.value, 0, 200, "連絡先組織名フリガナ")){
			return errProc(form.M_CO_G_KANA);
		}
	}
}

if(form.M_CO_C_NAME != undefined){
	if(form.M_CO_C_NAME.type != "hidden"){
		if(IsLengthB(form.M_CO_C_NAME.value, 0, 200, "連絡先名称")){
			return errProc(form.M_CO_C_NAME);
		}
	}
}

if(form.M_CO_C_KANA != undefined){
	if(form.M_CO_C_KANA.type != "hidden"){
		if(IsLengthB(form.M_CO_C_KANA.value, 0, 200, "連絡先フリガナ")){
			return errProc(form.M_CO_C_KANA);
		}
	}
}

if(form.M_CO_C_EMAIL != undefined){
	if(form.M_CO_C_EMAIL.type != "hidden"){
		if(IsLength(form.M_CO_C_EMAIL.value, 0, 100, "連絡先E-MAIL")){
			return errProc(form.M_CO_C_EMAIL);
		}
		if(IsNarrowPlus3(form.M_CO_C_EMAIL.value, "連絡先E-MAIL")){
			return errProc(form.M_CO_C_EMAIL);
		}
		if(isMail(form.M_CO_C_EMAIL.value, "連絡先E-MAIL")){
			return errProc(form.M_CO_C_EMAIL);
		}
	}
}

if(form.M_CO_C_EMAIL2 != undefined){
	if(form.M_CO_C_EMAIL2.type != "hidden"){
		if(IsLength(form.M_CO_C_EMAIL2.value, 0, 100, "連絡先E-MAIL再入力")){
			return errProc(form.M_CO_C_EMAIL2);
		}
	}
	if(IsNarrowPlus3(form.M_CO_C_EMAIL2.value, "連絡先E-MAIL再入力")){
		return errProc(form.M_CO_C_EMAIL2);
	}
	if(isMail(form.M_CO_C_EMAIL2.value, "連絡先E-MAIL再入力")){
		return errProc(form.M_CO_C_EMAIL2);
	}
	if(form.M_CO_C_EMAIL.value != form.M_CO_C_EMAIL2.value){
		alert("連絡先E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
		form.M_CO_C_EMAIL2.value = "";
		form.M_CO_C_EMAIL2.focus();
		return false;
	}
}

if(form.M_CO_C_CC_EMAIL != undefined){
	if(form.M_CO_C_CC_EMAIL.type != "hidden"){
		if(IsLength(form.M_CO_C_CC_EMAIL.value, 0, 100, "連絡先追加送信先E-MAIL")){
			return errProc(form.M_CO_C_CC_EMAIL);
		}
		if(IsNarrowPlus3(form.M_CO_C_CC_EMAIL.value, "連絡先追加送信先E-MAIL")){
			return errProc(form.M_CO_C_CC_EMAIL);
		}
		if(isMail(form.M_CO_C_CC_EMAIL.value, "連絡先追加送信先E-MAIL")){
			return errProc(form.M_CO_C_CC_EMAIL);
		}
	}
}

if(form.M_CONTACTDEST != undefined && form.M_CO_C_TEL_1 != undefined){
	if(form.M_CO_C_TEL_1.type != "hidden"){
		if(form.M_CO_C_TEL_1.value != "" || form.M_CO_C_TEL_2.value != "" || form.M_CO_C_TEL_3.value != ""){
			if(form.M_CO_C_TEL_1.value == ""){
				alert("連絡先TEL\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_TEL_1);
			}
			if(form.M_CO_C_TEL_2.value == ""){
				alert("連絡先TEL\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_TEL_2);
			}
			if(form.M_CO_C_TEL_3.value == ""){
				alert("連絡先TEL\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_TEL_3);
			}
			if(IsNarrowTelNum(form.M_CO_C_TEL_1.value, "連絡先TEL")){
				return errProc(form.M_CO_C_TEL_1);
			}
			if(IsNarrowTelNum(form.M_CO_C_TEL_2.value, "連絡先TEL")){
				return errProc(form.M_CO_C_TEL_2);
			}
			if(IsNarrowTelNum(form.M_CO_C_TEL_3.value, "連絡先TEL")){
				return errProc(form.M_CO_C_TEL_3);
			}
			form.M_CO_C_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
			if(IsLength(form.M_CO_C_TEL.value, 0, 20, "連絡先TEL")){
				form.M_CO_C_TEL_1.focus();
				return false;
			}
		} else {
			form.M_CO_C_TEL.value = "";
		}
	}
}

if(form.M_CO_C_FAX_1 != undefined){
	if(form.M_CO_C_FAX_1.type != "hidden"){
		if(form.M_CO_C_FAX_1.value != "" || form.M_CO_C_FAX_2.value != "" || form.M_CO_C_FAX_3.value != ""){
			if(form.M_CO_C_FAX_1.value == ""){
				alert("連絡先FAX\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_FAX_1);
			}
			if(form.M_CO_C_FAX_2.value == ""){
				alert("連絡先FAX\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_FAX_2);
			}
			if(form.M_CO_C_FAX_3.value == ""){
				alert("連絡先FAX\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_FAX_3);
			}
			if(IsNarrowTelNum(form.M_CO_C_FAX_1.value, "連絡先FAX")){
				return errProc(form.M_CO_C_FAX_1);
			}
			if(IsNarrowTelNum(form.M_CO_C_FAX_2.value, "連絡先FAX")){
				return errProc(form.M_CO_C_FAX_2);
			}
			if(IsNarrowTelNum(form.M_CO_C_FAX_3.value, "連絡先FAX")){
				return errProc(form.M_CO_C_FAX_3);
			}
			form.M_CO_C_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
			if(IsLength(form.M_CO_C_FAX.value, 0, 20, "連絡先FAX")){
				form.M_CO_C_FAX_1.value = form.M_CO_C_FAX_2.value = form.M_CO_C_FAX_3.value = ""
				form.M_CO_C_FAX_1.focus();
				return false;
			}
		} else {
			form.M_CO_C_FAX.value = "";
		}
	}
}

if(form.M_CONTACTDEST != undefined && form.M_CO_C_POST_u != undefined){
	if(form.M_CO_C_POST_u.type != "hidden"){
		if(form.M_CO_C_POST_u.value != "" || form.M_CO_C_POST_l.value != ""){
			if(form.M_CO_C_POST_u.value == ""){
				alert("連絡先〒\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_POST_u);
			}
			if(IsLength(form.M_CO_C_POST_u.value, 3, 3, "連絡先〒(上３桁)")){
				return errProc(form.M_CO_C_POST_u);
			}
			if(IsNarrowNum(form.M_CO_C_POST_u.value, "連絡先〒")){
				return errProc(form.M_CO_C_POST_u);
			}
			if(form.M_CO_C_POST_l.value == ""){
				alert("連絡先〒\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CO_C_POST_l);
			}
			if(IsLength(form.M_CO_C_POST_l.value, 4, 4, "連絡先〒(下４桁)")){
				return errProc(form.M_CO_C_POST_l);
			}
			if(IsNarrowNum(form.M_CO_C_POST_l.value, "連絡先〒")){
				return errProc(form.M_CO_C_POST_l);
			}
			form.M_CO_C_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
		} else {
			form.M_CO_C_POST.value = "";
		}
	}
}

if(form.M_CO_C_ADDRESS != undefined){
	if(form.M_CO_C_ADDRESS.type != "hidden"){
		if(IsLengthB(form.M_CO_C_ADDRESS.value, 0, 500, "連絡先住所１")){
			return errProc(form.M_CO_C_ADDRESS);
		}
	}
}

if(form.M_CO_C_ADDRESS2 != undefined){
	if(form.M_CO_C_ADDRESS2.type != "hidden"){
		if(IsLengthB(form.M_CO_C_ADDRESS2.value, 0, 100, "連絡先住所２")){
			return errProc(form.M_CO_C_ADDRESS2);
		}
	}
}

if(form.M_CL_G_NAME != undefined){
	if(form.M_CL_G_NAME.type != "hidden"){
		if(IsLengthB(form.M_CL_G_NAME.value, 0, 200, "請求先組織名")){
			return errProc(form.M_CL_G_NAME);
		}
	}
}

if(form.M_CL_G_KANA != undefined){
	if(form.M_CL_G_KANA.type != "hidden"){
		if(IsLengthB(form.M_CL_G_KANA.value, 0, 200, "請求先組織名フリガナ")){
			return errProc(form.M_CL_G_KANA);
		}
	}
}

if(form.M_CL_C_NAME != undefined){
	if(form.M_CL_C_NAME.type != "hidden"){
		if(IsLengthB(form.M_CL_C_NAME.value, 0, 200, "請求先名称")){
			return errProc(form.M_CL_C_NAME);
		}
	}
}

if(form.M_CL_C_KANA != undefined){
	if(form.M_CL_C_KANA.type != "hidden"){
		if(IsLengthB(form.M_CL_C_KANA.value, 0, 200, "請求先フリガナ")){
			return errProc(form.M_CL_C_KANA);
		}
	}
}

if(form.M_CL_C_EMAIL != undefined){
	if(form.M_CL_C_EMAIL.type != "hidden"){
		if(IsLength(form.M_CL_C_EMAIL.value, 0, 100, "請求先E-MAIL")){
			return errProc(form.M_CL_C_EMAIL);
		}
		if(IsNarrowPlus3(form.M_CL_C_EMAIL.value, "請求先E-MAIL")){
			return errProc(form.M_CL_C_EMAIL);
		}
		if(isMail(form.M_CL_C_EMAIL.value, "請求先E-MAIL")){
			return errProc(form.M_CL_C_EMAIL);
		}
	}
}

if(form.M_CL_C_EMAIL2 != undefined){
	if(form.M_CL_C_EMAIL2.type != "hidden"){
		if(IsLength(form.M_CL_C_EMAIL2.value, 0, 100, "請求先E-MAIL再入力")){
			return errProc(form.M_CL_C_EMAIL2);
		}
	}
	if(IsNarrowPlus3(form.M_CL_C_EMAIL2.value, "請求先E-MAIL再入力")){
		return errProc(form.M_CL_C_EMAIL2);
	}
	if(isMail(form.M_CL_C_EMAIL2.value, "請求先E-MAIL再入力")){
		return errProc(form.M_CL_C_EMAIL2);
	}
	if(form.M_CL_C_EMAIL.value != form.M_CL_C_EMAIL2.value){
		alert("請求先E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
		form.M_CL_C_EMAIL2.value = "";
		form.M_CL_C_EMAIL2.focus();
		return false;
	}
}

if(form.M_CL_C_CC_EMAIL != undefined){
	if(form.M_CL_C_CC_EMAIL.type != "hidden"){
		if(IsLength(form.M_CL_C_CC_EMAIL.value, 0, 100, "請求先追加送信先E-MAIL")){
			return errProc(form.M_CL_C_CC_EMAIL);
		}
		if(IsNarrowPlus3(form.M_CL_C_CC_EMAIL.value, "請求先追加送信先E-MAIL")){
			return errProc(form.M_CL_C_CC_EMAIL);
		}
		if(isMail(form.M_CL_C_CC_EMAIL.value, "請求先追加送信先E-MAIL")){
			return errProc(form.M_CL_C_CC_EMAIL);
		}
	}
}

if(form.M_CLAIMDEST != undefined && form.M_CL_C_TEL_1 != undefined){
	if(form.M_CL_C_TEL_1.type != "hidden"){
		if(form.M_CLAIMDEST.value == "2"){
			if(form.M_CL_C_TEL_1.value != "" || form.M_CL_C_TEL_2.value != "" || form.M_CL_C_TEL_3.value != ""){
				if(form.M_CL_C_TEL_1.value == ""){
					alert("請求先TEL\nを登録する場合は\n全ての欄を入力して下さい");
					return errProc(form.M_CL_C_TEL_1);
				}
				if(form.M_CL_C_TEL_2.value == ""){
					alert("請求先TEL\nを登録する場合は\n全ての欄を入力して下さい");
					return errProc(form.M_CL_C_TEL_2);
				}
				if(form.M_CL_C_TEL_3.value == ""){
					alert("請求先TEL\nを登録する場合は\n全ての欄を入力して下さい");
					return errProc(form.M_CL_C_TEL_3);
				}
				if(IsNarrowTelNum(form.M_CL_C_TEL_1.value, "請求先TEL")){
					return errProc(form.M_CL_C_TEL_1);
				}
				if(IsNarrowTelNum(form.M_CL_C_TEL_2.value, "請求先TEL")){
					return errProc(form.M_CL_C_TEL_2);
				}
				if(IsNarrowTelNum(form.M_CL_C_TEL_3.value, "請求先TEL")){
					return errProc(form.M_CL_C_TEL_3);
				}
				form.M_CL_C_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
				if(IsLength(form.M_CL_C_TEL.value, 0, 20, "請求先TEL")){
					form.M_CL_C_TEL_1.focus();
					return false;
				}
			} else {
				form.M_CL_C_TEL.value = "";
			}
		}
	}
}

if(form.M_CL_C_FAX_1 != undefined){
	if(form.M_CL_C_FAX_1.type != "hidden"){
		if(form.M_CL_C_FAX_1.value != "" || form.M_CL_C_FAX_2.value != "" || form.M_CL_C_FAX_3.value != ""){
			if(form.M_CL_C_FAX_1.value == ""){
				alert("請求先FAX\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CL_C_FAX_1);
			}
			if(form.M_CL_C_FAX_2.value == ""){
				alert("請求先FAX\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CL_C_FAX_2);
			}
			if(form.M_CL_C_FAX_3.value == ""){
				alert("請求先FAX\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CL_C_FAX_3);
			}
			if(IsNarrowTelNum(form.M_CL_C_FAX_1.value, "請求先FAX")){
				return errProc(form.M_CL_C_FAX_1);
			}
			if(IsNarrowTelNum(form.M_CL_C_FAX_2.value, "請求先FAX")){
				return errProc(form.M_CL_C_FAX_2);
			}
			if(IsNarrowTelNum(form.M_CL_C_FAX_3.value, "請求先FAX")){
				return errProc(form.M_CL_C_FAX_3);
			}
			form.M_CL_C_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
			if(IsLength(form.M_CL_C_FAX.value, 0, 20, "請求先FAX")){
				form.M_CL_C_FAX_1.value = form.M_CL_C_FAX_2.value = form.M_CL_C_FAX_3.value = "";
				form.M_CL_C_FAX_1.focus();
				return false;
			}
		} else {
			form.M_CL_C_FAX.value = "";
		}
	}
}

if(form.M_CLAIMDEST != undefined && form.M_CL_C_POST_u != undefined){
	if(form.M_CL_C_POST_u.type != "hidden"){
		if(form.M_CL_C_POST_u.value != "" || form.M_CL_C_POST_l.value != ""){
			if(form.M_CL_C_POST_u.value == ""){
				alert("請求先〒\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CL_C_POST_u);
			}
			if(IsLength(form.M_CL_C_POST_u.value, 3, 3, "請求先〒(上３桁)")){
				return errProc(form.M_CL_C_POST_u);
			}
			if(IsNarrowNum(form.M_CL_C_POST_u.value, "請求先〒")){
				return errProc(form.M_CL_C_POST_u);
			}
			if(form.M_CL_C_POST_l.value == ""){
				alert("請求先〒\nを登録する場合は\n全ての欄を入力して下さい");
				return errProc(form.M_CL_C_POST_l);
			}
			if(IsLength(form.M_CL_C_POST_l.value, 4, 4, "請求先〒(下４桁)")){
				return errProc(form.M_CL_C_POST_l);
			}
			if(IsNarrowNum(form.M_CL_C_POST_l.value, "請求先〒")){
				return errProc(form.M_CL_C_POST_l);
			}
			form.M_CL_C_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
		} else {
			form.M_CL_C_POST.value = "";
		}
	}
}

if(form.M_CL_C_ADDRESS != undefined){
	if(form.M_CL_C_ADDRESS.type != "hidden"){
		if(IsLengthB(form.M_CL_C_ADDRESS.value, 0, 500, "請求先住所１")){
			return errProc(form.M_CL_C_ADDRESS);
		}
	}
}

if(form.M_CL_C_ADDRESS2 != undefined){
	if(form.M_CL_C_ADDRESS2.type != "hidden"){
		if(IsLengthB(form.M_CL_C_ADDRESS2.value, 0, 100, "請求先住所２")){
			return errProc(form.M_CL_C_ADDRESS2);
		}
	}
}

if(form.M_R_ID != undefined){
	if(form.M_R_ID.type != "hidden"){
		if(IsLength(form.M_R_ID.value, 4, 20, "推薦者個人ID")){
			return errProc(form.M_R_ID);
		}
		if(IsNarrowPlus(form.M_R_ID.value, "推薦者個人ID")){
			return errProc(form.M_R_ID);
		}
	}
}

if(form.M_X_COMMENT != undefined){
	if(form.M_X_COMMENT.type != "hidden"){
		if(IsLength(form.M_X_COMMENT.value, 0, 500, "コメント")){
			return errProc(form.M_X_COMMENT);
		}
	}
}

if(form.FAX_TIMEZONE != undefined){
	var fax_timezone;
	if(form.FAX_TIMEZONE.type != "hidden"){
		for(var i = 0; i < form.FAX_TIMEZONE.length; i++){
			if(form.FAX_TIMEZONE[i].checked == true){
				fax_timezone = form.FAX_TIMEZONE[i].value
			}
		}
		if(fax_timezone=="4"){
			if(form.FAX_TIME_FROM_H.value==""||form.FAX_TIME_FROM_N.value==""||form.FAX_TIME_TO_H.value==""||form.FAX_TIME_TO_N.value==""){
				alert("FAX送信時間は必ず入力して下さい。");
				form.FAX_TIME_FROM_H.focus();
				return false;
			}
			form.FAX_TIME_FROM.value=form.FAX_TIME_FROM_H.value + ":" + form.FAX_TIME_FROM_N.value;
			form.FAX_TIME_TO.value=form.FAX_TIME_TO_H.value + ":" + form.FAX_TIME_TO_N.value;
		}else{
			form.FAX_TIME_FROM_H[0].selected;
			form.FAX_TIME_FROM_N[0].selected;
			form.FAX_TIME_TO_H[0].selected;
			form.FAX_TIME_TO_N[0].selected;
			form.FAX_TIME_FROM.value="";
			form.FAX_TIME_TO.value="";
		}
	}
}

if(form.GM_BIKOU1 != undefined){
	if(form.GM_BIKOU1.type == "text"){
		if(IsLength(form.GM_BIKOU1.value, 0, 2000, "予備項目０１")){
			return errProc(form.GM_BIKOU1);
		}
	}
}
if(form.GM_BIKOU2 != undefined){
	if(form.GM_BIKOU2.type == "text"){
		if(IsLength(form.GM_BIKOU2.value, 0, 2000, "会員自由項目２")){
			return errProc(form.GM_BIKOU2);
		}
	}
}
if(form.GM_BIKOU3 != undefined){
	if(form.GM_BIKOU3.type == "text"){
		if(IsLength(form.GM_BIKOU3.value, 0, 2000, "予備項目０２")){
			return errProc(form.GM_BIKOU3);
		}
	}
}
if(form.GM_BIKOU4 != undefined){
	if(form.GM_BIKOU4.type == "text"){
		if(IsLength(form.GM_BIKOU4.value, 0, 2000, "会員自由項目４")){
			return errProc(form.GM_BIKOU4);
		}
	}
}
if(form.GM_BIKOU5 != undefined){
	if(form.GM_BIKOU5.type == "text"){
		if(IsLength(form.GM_BIKOU5.value, 0, 2000, "会員自由項目５")){
			return errProc(form.GM_BIKOU5);
		}
	}
}
if(form.GM_BIKOU6 != undefined){
	if(form.GM_BIKOU6.type == "text"){
		if(IsLength(form.GM_BIKOU6.value, 0, 2000, "会員自由項目６")){
			return errProc(form.GM_BIKOU6);
		}
	}
}
if(form.GM_BIKOU7 != undefined){
	if(form.GM_BIKOU7.type == "text"){
		if(IsLength(form.GM_BIKOU7.value, 0, 2000, "会員自由項目７")){
			return errProc(form.GM_BIKOU7);
		}
	}
}
if(form.GM_BIKOU8 != undefined){
	if(form.GM_BIKOU8.type == "text"){
		if(IsLength(form.GM_BIKOU8.value, 0, 2000, "会員自由項目８")){
			return errProc(form.GM_BIKOU8);
		}
	}
}
if(form.GM_BIKOU9 != undefined){
	if(form.GM_BIKOU9.type == "text"){
		if(IsLength(form.GM_BIKOU9.value, 0, 2000, "会員自由項目９")){
			return errProc(form.GM_BIKOU9);
		}
	}
}
if(form.GM_BIKOU10 != undefined){
	if(form.GM_BIKOU10.type == "text"){
		if(IsLength(form.GM_BIKOU10.value, 0, 2000, "会員自由項目１０")){
			return errProc(form.GM_BIKOU10);
		}
	}
}
if(form.GM_BIKOU11 != undefined){
	if(form.GM_BIKOU11.type == "text"){
		if(IsLength(form.GM_BIKOU11.value, 0, 2000, "会員自由項目１１")){
			return errProc(form.GM_BIKOU11);
		}
	}
}
if(form.GM_BIKOU12 != undefined){
	if(form.GM_BIKOU12.type == "text"){
		if(IsLength(form.GM_BIKOU12.value, 0, 2000, "会員自由項目１２")){
			return errProc(form.GM_BIKOU12);
		}
	}
}
if(form.GM_BIKOU13 != undefined){
	if(form.GM_BIKOU13.type == "text"){
		if(IsLength(form.GM_BIKOU13.value, 0, 2000, "会員自由項目１３")){
			return errProc(form.GM_BIKOU13);
		}
	}
}
if(form.GM_BIKOU14 != undefined){
	if(form.GM_BIKOU14.type == "text"){
		if(IsLength(form.GM_BIKOU14.value, 0, 2000, "会員自由項目１４")){
			return errProc(form.GM_BIKOU14);
		}
	}
}
if(form.GM_BIKOU15 != undefined){
	if(form.GM_BIKOU15.type == "text"){
		if(IsLength(form.GM_BIKOU15.value, 0, 2000, "会員自由項目１５")){
			return errProc(form.GM_BIKOU15);
		}
	}
}
if(form.GM_BIKOU16 != undefined){
	if(form.GM_BIKOU16.type == "text"){
		if(IsLength(form.GM_BIKOU16.value, 0, 2000, "会員自由項目１６")){
			return errProc(form.GM_BIKOU16);
		}
	}
}
if(form.GM_BIKOU17 != undefined){
	if(form.GM_BIKOU17.type == "text"){
		if(IsLength(form.GM_BIKOU17.value, 0, 2000, "会員自由項目１７")){
			return errProc(form.GM_BIKOU17);
		}
	}
}
if(form.GM_BIKOU18 != undefined){
	if(form.GM_BIKOU18.type == "text"){
		if(IsLength(form.GM_BIKOU18.value, 0, 2000, "会員自由項目１８")){
			return errProc(form.GM_BIKOU18);
		}
	}
}
if(form.GM_BIKOU19 != undefined){
	if(form.GM_BIKOU19.type == "text"){
		if(IsLength(form.GM_BIKOU19.value, 0, 2000, "会員自由項目１９")){
			return errProc(form.GM_BIKOU19);
		}
	}
}
if(form.GM_BIKOU20 != undefined){
	if(form.GM_BIKOU20.type == "text"){
		if(IsLength(form.GM_BIKOU20.value, 0, 2000, "会員自由項目２０")){
			return errProc(form.GM_BIKOU20);
		}
	}
}
if(form.GM_BIKOU21 != undefined){
	if(form.GM_BIKOU21.type == "text"){
		if(IsLength(form.GM_BIKOU21.value, 0, 2000, "会員自由項目２１")){
			return errProc(form.GM_BIKOU21);
		}
	}
}
if(form.GM_BIKOU22 != undefined){
	if(form.GM_BIKOU22.type == "text"){
		if(IsLength(form.GM_BIKOU22.value, 0, 2000, "会員自由項目２２")){
			return errProc(form.GM_BIKOU22);
		}
	}
}
if(form.GM_BIKOU23 != undefined){
	if(form.GM_BIKOU23.type == "text"){
		if(IsLength(form.GM_BIKOU23.value, 0, 2000, "会員自由項目２３")){
			return errProc(form.GM_BIKOU23);
		}
	}
}
if(form.GM_BIKOU24 != undefined){
	if(form.GM_BIKOU24.type == "text"){
		if(IsLength(form.GM_BIKOU24.value, 0, 2000, "会員自由項目２４")){
			return errProc(form.GM_BIKOU24);
		}
	}
}
if(form.GM_BIKOU25 != undefined){
	if(form.GM_BIKOU25.type == "text"){
		if(IsLength(form.GM_BIKOU25.value, 0, 2000, "会員自由項目２５")){
			return errProc(form.GM_BIKOU25);
		}
	}
}
if(form.GM_BIKOU26 != undefined){
	if(form.GM_BIKOU26.type == "text"){
		if(IsLength(form.GM_BIKOU26.value, 0, 2000, "会員自由項目２６")){
			return errProc(form.GM_BIKOU26);
		}
	}
}
if(form.GM_BIKOU27 != undefined){
	if(form.GM_BIKOU27.type == "text"){
		if(IsLength(form.GM_BIKOU27.value, 0, 2000, "会員自由項目２７")){
			return errProc(form.GM_BIKOU27);
		}
	}
}
if(form.GM_BIKOU28 != undefined){
	if(form.GM_BIKOU28.type == "text"){
		if(IsLength(form.GM_BIKOU28.value, 0, 2000, "会員自由項目２８")){
			return errProc(form.GM_BIKOU28);
		}
	}
}
if(form.GM_BIKOU29 != undefined){
	if(form.GM_BIKOU29.type == "text"){
		if(IsLength(form.GM_BIKOU29.value, 0, 2000, "会員自由項目２９")){
			return errProc(form.GM_BIKOU29);
		}
	}
}
if(form.GM_BIKOU30 != undefined){
	if(form.GM_BIKOU30.type == "text"){
		if(IsLength(form.GM_BIKOU30.value, 0, 2000, "会員自由項目３０")){
			return errProc(form.GM_BIKOU30);
		}
	}
}
if(form.GM_BIKOU31 != undefined){
	if(form.GM_BIKOU31.type == "text"){
		if(IsLength(form.GM_BIKOU31.value, 0, 2000, "会員自由項目３１")){
			return errProc(form.GM_BIKOU31);
		}
	}
}
if(form.GM_BIKOU32 != undefined){
	if(form.GM_BIKOU32.type == "text"){
		if(IsLength(form.GM_BIKOU32.value, 0, 2000, "会員自由項目３２")){
			return errProc(form.GM_BIKOU32);
		}
	}
}
if(form.GM_BIKOU33 != undefined){
	if(form.GM_BIKOU33.type == "text"){
		if(IsLength(form.GM_BIKOU33.value, 0, 2000, "会員自由項目３３")){
			return errProc(form.GM_BIKOU33);
		}
	}
}
if(form.GM_BIKOU34 != undefined){
	if(form.GM_BIKOU34.type == "text"){
		if(IsLength(form.GM_BIKOU34.value, 0, 2000, "会員自由項目３４")){
			return errProc(form.GM_BIKOU34);
		}
	}
}
if(form.GM_BIKOU35 != undefined){
	if(form.GM_BIKOU35.type == "text"){
		if(IsLength(form.GM_BIKOU35.value, 0, 2000, "会員自由項目３５")){
			return errProc(form.GM_BIKOU35);
		}
	}
}
if(form.GM_BIKOU36 != undefined){
	if(form.GM_BIKOU36.type == "text"){
		if(IsLength(form.GM_BIKOU36.value, 0, 2000, "会員自由項目３６")){
			return errProc(form.GM_BIKOU36);
		}
	}
}
if(form.GM_BIKOU37 != undefined){
	if(form.GM_BIKOU37.type == "text"){
		if(IsLength(form.GM_BIKOU37.value, 0, 2000, "会員自由項目３７")){
			return errProc(form.GM_BIKOU37);
		}
	}
}
if(form.GM_BIKOU38 != undefined){
	if(form.GM_BIKOU38.type == "text"){
		if(IsLength(form.GM_BIKOU38.value, 0, 2000, "会員自由項目３８")){
			return errProc(form.GM_BIKOU38);
		}
	}
}
if(form.GM_BIKOU39 != undefined){
	if(form.GM_BIKOU39.type == "text"){
		if(IsLength(form.GM_BIKOU39.value, 0, 2000, "会員自由項目３９")){
			return errProc(form.GM_BIKOU39);
		}
	}
}
if(form.GM_BIKOU40 != undefined){
	if(form.GM_BIKOU40.type == "text"){
		if(IsLength(form.GM_BIKOU40.value, 0, 2000, "会員自由項目４０")){
			return errProc(form.GM_BIKOU40);
		}
	}
}
if(form.GM_BIKOU41 != undefined){
	if(form.GM_BIKOU41.type == "text"){
		if(IsLength(form.GM_BIKOU41.value, 0, 2000, "会員自由項目４１")){
			return errProc(form.GM_BIKOU41);
		}
	}
}
if(form.GM_BIKOU42 != undefined){
	if(form.GM_BIKOU42.type == "text"){
		if(IsLength(form.GM_BIKOU42.value, 0, 2000, "会員自由項目４２")){
			return errProc(form.GM_BIKOU42);
		}
	}
}
if(form.GM_BIKOU43 != undefined){
	if(form.GM_BIKOU43.type == "text"){
		if(IsLength(form.GM_BIKOU43.value, 0, 2000, "会員自由項目４３")){
			return errProc(form.GM_BIKOU43);
		}
	}
}
if(form.GM_BIKOU44 != undefined){
	if(form.GM_BIKOU44.type == "text"){
		if(IsLength(form.GM_BIKOU44.value, 0, 2000, "会員自由項目４４")){
			return errProc(form.GM_BIKOU44);
		}
	}
}
if(form.GM_BIKOU45 != undefined){
	if(form.GM_BIKOU45.type == "text"){
		if(IsLength(form.GM_BIKOU45.value, 0, 2000, "会員自由項目４５")){
			return errProc(form.GM_BIKOU45);
		}
	}
}
if(form.GM_BIKOU46 != undefined){
	if(form.GM_BIKOU46.type == "text"){
		if(IsLength(form.GM_BIKOU46.value, 0, 2000, "会員自由項目４６")){
			return errProc(form.GM_BIKOU46);
		}
	}
}
if(form.GM_BIKOU47 != undefined){
	if(form.GM_BIKOU47.type == "text"){
		if(IsLength(form.GM_BIKOU47.value, 0, 2000, "会員自由項目４７")){
			return errProc(form.GM_BIKOU47);
		}
	}
}
if(form.GM_BIKOU48 != undefined){
	if(form.GM_BIKOU48.type == "text"){
		if(IsLength(form.GM_BIKOU48.value, 0, 2000, "会員自由項目４８")){
			return errProc(form.GM_BIKOU48);
		}
	}
}
if(form.GM_BIKOU49 != undefined){
	if(form.GM_BIKOU49.type == "text"){
		if(IsLength(form.GM_BIKOU49.value, 0, 2000, "会員自由項目４９")){
			return errProc(form.GM_BIKOU49);
		}
	}
}
if(form.GM_BIKOU50 != undefined){
	if(form.GM_BIKOU50.type == "text"){
		if(IsLength(form.GM_BIKOU50.value, 0, 2000, "会員自由項目５０")){
			return errProc(form.GM_BIKOU50);
		}
	}
}
return true;
}

function errProc(elem){
	if(elem.type != "hidden"){
		if(elem.type != "select-one") elem.select();
		elem.focus();
	}
	return false;
}

function CheckInputData_searver(pdFlg, gdFlg, sdFlg, mdFlg, bChange, bMdReg){
	var form = document.mainForm;
	var ifmGetData = getData;
	var ifmSetUrl;
	var change_flg="0";
	if(bChange == true){
		change_flg="1";
	}
	ifmSetUrl = "./rs/commonRsSearch.asp?fncName=CheckInputData";

	if(pdFlg == true){

		if(bChange == false){
			ifmSetUrl = ifmSetUrl + "&PPID=" + form.P_P_ID.value;
		}
		ifmSetUrl = ifmSetUrl + "&PCEmail=" + form.P_C_EMAIL.value;
		ifmSetUrl = ifmSetUrl + "&PCPmail=" + form.P_C_PMAIL.value;
		ifmSetUrl = ifmSetUrl + "&PPEmail=" + form.P_P_EMAIL.value;
		ifmSetUrl = ifmSetUrl + "&PPPmail=" + form.P_P_PMAIL.value;
		ifmSetUrl = ifmSetUrl + "&pid=" + form.P_P_ID.value;
	}

	if(gdFlg == true){
		if(bChange == false){
			if(form.G_G_ID.type != "hidden"){

				ifmSetUrl = ifmSetUrl + "&GGID=" + form.G_G_ID.value;
			}
		}
	}

	if(sdFlg == true){
	}

	if(mdFlg == true){
	}
	ifmSetUrl = ifmSetUrl + "&bChange=" + change_flg;
	ifmGetData.location.href = ifmSetUrl;
}

function retSearchVal_CheckInputData(PCEmail, PCPmail, PPEmail, PPPmail, PPID, GGID){
	var form = document.mainForm;
	var RegCheck = true;

	if(form.NoneRMf != undefined){
		if(form.NoneRMf.value == "1"){
			RegCheck = false;
		}
	}
	if(form.entry_active == undefined){
		if(PPID != 0 && RegCheck == true){
			alert("入力された個人IDは既に使用されています。\n別の個人IDを使用してください。");
			return errProc(form.P_P_ID);
		}

		if(form.P_C_EMAIL.type != "hidden"){
			if(PCEmail != 0){
				alert("入力された個人E-MAILは既に登録されています。\n別の個人E-MAILを入力してください。");
				return errProc(form.P_C_EMAIL);
			}
		}
		if(form.P_C_PMAIL.type != "hidden"){
			if(PCPmail != 0){
				alert("入力された携帯メールは既に登録されています。\n別の携帯メールを入力してください。");
				return errProc(form.P_C_PMAIL);
			}
		}
		if(form.P_P_EMAIL.type != "hidden"){
			if(PPEmail != 0){
				alert("入力されたプライベートE-MAILは既に登録されています。\n別のプライベートE-MAILを入力してください。");
				return errProc(form.P_P_EMAIL);
			}
		}
		if(form.P_P_PMAIL.type != "hidden"){
			if(PPPmail != 0){
				alert("入力されたプライベート携帯メールアドレスは既に登録されています。\n別のプライベート携帯メールアドレスを入力してください。");
				return errProc(form.P_P_PMAIL);
			}
		}

		if(GGID != 0 && RegCheck == true){
			alert("入力された組織IDは既に使用されています。\n別の組織IDを使用してください。");
			return errProc(form.G_G_ID);
		}
	}


	if(form.M_CONTACTDEST != undefined){

		changeContactForm();
	}
	if(form.M_CLAIMDEST != undefined){

		changeClaimForm();
	}


	var act_win;
	if(form.NewRegCheckFlg != undefined){
		act_win = "confirm_active_ex.asp";
	}else{

		act_win = "confirm.asp";

	}


	if(form.flgFee.value == '1' && form.M_STATUS.value != '1'){
		form.method = "post";
		form.action = act_win + "?feewin=1";
		form.submit();
	}else{
		form.method = "post";
		form.action = act_win + "";
		form.submit();
	}

}

function dispSwitch(mode){
	var form = document.mainForm;
	if(mode=='on'){

		form.FAX_TIME_FROM_H.disabled = false;
		form.FAX_TIME_FROM_N.disabled = false;
		form.FAX_TIME_TO_H.disabled   = false;
		form.FAX_TIME_TO_N.disabled   = false;
	}else{

		form.FAX_TIME_FROM_H.disabled = true;
		form.FAX_TIME_FROM_N.disabled = true;
		form.FAX_TIME_TO_H.disabled   = true;
		form.FAX_TIME_TO_N.disabled   = true;
	}
}

function funcHanToZen(obj){
	var ifmGetData = getData;
	var ifmSetUrl;
	ifmSetUrl = "./rs/commonRsSearch.asp?fncName=rsHanToZen&value=" + obj.value;
	ifmSetUrl = ifmSetUrl + "&ArgVal=" + obj.name;
	ifmGetData.location.href = ifmSetUrl;
}
function retSearchVal_rsHanToZen(retVal, objname){
	var form = document.mainForm;

	form[objname].value = retVal;

	switch (objname){
		case "P_C_ADDRESS":
		case "P_C_ADDRESS2":
		changeContactForm();
		changeClaimForm();
		break;

		case "P_P_ADDRESS":
		case "P_P_ADDRESS2":
		changeContactForm();
		changeClaimForm();
		break;

		case "G_ADDRESS":
		case "G_ADDRESS2":
		changeContactForm();
		changeClaimForm();
		break;

		case "M_CO_C_ADDRESS":
		case "M_CO_C_ADDRESS2":
		changeContactFormValue();
		changeClaimForm();
		break;

		case "M_CL_C_ADDRESS":
		case "M_CL_C_ADDRESS2":
		changeClaimFormValue();
		changeClaimForm();
		break;

		default:
		break;
	}

}
function funcZenToHan(obj){
	var ifmGetData = getData;
	var ifmSetUrl;
	ifmSetUrl = "./rs/commonRsSearch.asp?fncName=rsZenToHan&value=" + obj.value;
	ifmSetUrl = ifmSetUrl + "&ArgVal=" + obj.name;
	ifmGetData.location.href = ifmSetUrl;
}
function retSearchVal_rsZenToHan(retVal, objname){
	var form = document.mainForm;
	var tmp;

	switch (objname){
		case "G_ACCAUNT_NAME":
		tmp = retVal;
		form[objname].value = retVal;
		if(getByte(tmp) != tmp.length){
			alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
			return;
		}

		changeContactForm();
		changeClaimForm();
		break;

		case "P_ACCAUNT_NAME":
		tmp = retVal;
		form[objname].value = retVal;
		if(getByte(tmp) != tmp.length){
			alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
			return;
		}

		changeContactForm();
		changeClaimForm();
		break;

		case "M_ACCAUNT_NAME":
		tmp = retVal;
		form[objname].value = retVal;
		if(getByte(tmp) != tmp.length){
			alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
			return;
		}

		changeClaimFormValue();
		break;

		default:
		form[objname].value = retVal;
		break;
	}

}

function funcHiraganaToKatakana(obj){
	var ifmGetData = getData;
	var ifmSetUrl;

	ifmSetUrl = "./rs/commonRsSearch.asp?fncName=rsHiraganaToKatakana&value=" + obj.value;

	ifmSetUrl = ifmSetUrl + "&ArgVal=" + obj.name;
	ifmGetData.location.href = ifmSetUrl;
}
function retSearchVal_rsHiraganaToKatakana(retVal, objname){

	var form = document.mainForm;


	form[objname].value = retVal;

	switch (objname){
		case "P_C_KANA":
		case "G_KANA":
		changeContactForm();
		changeClaimForm();
		break;

		case "M_CO_G_KANA":
		case "M_CO_C_KANA":
		case "M_CL_G_KANA":
		case "M_CL_C_KANA":
		changeClaimFormValue();
		changeClaimForm();

		default:
		break;
	}

}
function OnLoad(){
}

function errProc(elem){
	if(elem.type != "hidden"){
		if(elem.type != "select-one") elem.select();
		elem.focus();
	}
	return false;
}


function OnConfirm(mode,url){
	var form = document.mainForm;
	var gid = form.set_lg_g_id.value;

	var cname;

	var value = form.P_C_EMAIL.value;
	var postid = form.postid.value;
	var ifmGetData = getData;
	var ifmSetUrl;



	if (mode == "chk") {


		if(IsNull(form.P_C_EMAIL.value, 'E-MAIL')) return errProc(form.P_C_EMAIL);
		if(IsLength(form.P_C_EMAIL.value, 0, 100, 'E-MAIL')) return errProc(form.P_C_EMAIL);
		if(IsNarrowPlus3(form.P_C_EMAIL.value, 'E-MAIL')) return errProc(form.P_C_EMAIL);

		if(isMail(form.P_C_EMAIL.value, 'E-MAIL')) return errProc(form.P_C_EMAIL);

		if(IsNull(form.P_C_EMAIL2.value, 'E-MAIL再入力')) return errProc(form.P_C_EMAIL2);
		if(form.P_C_EMAIL.value != form.P_C_EMAIL2.value){
			alert("E-MAILの内容と確認入力の内容が一致しません。\nE-MAILをもう一度確認して下さい");
			form.P_C_EMAIL2.value = "";
			form.P_C_EMAIL2.focus();
			return false;
		}


		ifmSetUrl = url+"func=CheckMailMagazineDel&gid=" + gid + "&cname=" + cname + "&value=" + value+"&postid="+postid;
		ifmGetData.location.href = ifmSetUrl;

	} else {
		if (confirm("メルマガの購読を中止します。\nよろしいですか？")) {
			form.mode.value = "delete";
			form.submit();
		}
	}


}


function moveBackPage(){
	window.history.back();
}


function retSearchVal_CheckMailMagazine(retSearchVal){
	var form = document.mainForm;


}


function retSearchVal_CheckMailMagazineDel(retSearchVal){
	var form = document.mainForm;
	if (retSearchVal == "") {
		alert("入力されたメールアドレスはメルマガ購読されていません。");
		return errProc(form.P_C_EMAIL);
	}

	if (confirm("メルマガの購読の中止を申請します。\nよろしいですか？")) {

		form.p_id.value = retSearchVal;

		form.mode.value = "deleteConfirm";

		form.submit();
	}
}


function ShowImage(url){
	location.href = "./window/ImageView.asp?img=" + url;
}
