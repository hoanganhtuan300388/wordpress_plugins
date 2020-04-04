var gToolWnd = null;  // 検索ウインドウ

// トップメニュー表示
function OnTop(){
  location.href = "../topmenu.asp";
}

// ログアウト
function OnLogout(){
  location.href = "../logout.asp";
}

// 個人詳細画面表示
function ShowPersonalDetailUser(p_id){
  gToolWnd = open('../PersonalDetail.asp?p_id=' + p_id + "&mode=0",
      'DetailWnd',
      'width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 組織詳細画面表示
function ShowGroupDetailUser(g_id){
  gToolWnd = open('../GroupDetail.asp?g_id=' + g_id + "&mode=0",
      'DetailWnd',
      'width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 組織検索
function OnSearchGroup(){
  gToolWnd = window.open('../search2.asp?mode=g&formName=mainForm.G_G_ID2',
    'SearchWnd',
    'width=500,height=420,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
}

// 個人検索
function OnSearchPersonal(){
  gToolWnd = window.open('../search2.asp?mode=p&formName=mainForm.P_P_ID2',
    'SearchWnd',
    'width=500,height=420,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
}

// アンロード処理
function OnUnload(){
  if (gToolWnd != null) {
    gToolWnd.close();
    gToolWnd = null;
  }
}

// 辞書ウインドウ表示
function ShowDictionaryWnd(formName, eleName, dicName){
  gToolWnd = open("../window/Select_Dic.asp?form=" + formName + "&item=" + dicName + "&text=" + eleName,
      'SearchWnd',
      'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}


// 組織変更
function OnBlurGid(){
  if (document.mainForm.G_PASSWORD.value == "") {
    // パスワードが未入力の場合、組織IDと同値を設定する
    document.mainForm.G_PASSWORD.value = document.mainForm.G_G_ID.value;
    document.mainForm.G_PASSWORD2.value = document.mainForm.G_G_ID.value;
  }
}

// 個人変更
function OnBlurPid(){
  if (document.mainForm.P_PASSWORD.value == "") {
    // パスワードが未入力の場合、組織IDと同値を設定する
    document.mainForm.P_PASSWORD.value = document.mainForm.P_P_ID.value;
    document.mainForm.P_PASSWORD2.value = document.mainForm.P_P_ID.value;
  }
}

// 下部組織変更
function OnKeyDownLgGid(){
  if (event.keyCode == 0x0d) {
    // enter キーの場合
    ShowLowerGroupName(document.mainForm.M_LG_ID.value);
  }
}

// 下部組織変更
function OnChangeLowerGroup(){
  if(document.mainForm.M_LG_ID != undefined){
    if (document.mainForm.M_LG_ID.type == "hidden") return;
    document.mainForm.M_LG_ID.value = document.mainForm.M_LG_ID_SEL.value;
    document.mainForm.M_LG_NAME.value = document.mainForm.M_LG_ID_SEL.options[document.mainForm.M_LG_ID_SEL.selectedIndex].text;
  }
}

// 入力データチェック
function CheckInputData(){
  var form = document.mainForm;
  var bCheckGdata;
  var bCheckPdata;
  var bCheckMdata;
  var bChange;
  var pdFlg = false;
  var gdFlg = false;
  var sdFlg = false;
  var mdFlg = false;
  bChange = false;
  // 組織データチェック
  bCheckGdata = false;
  if (form.m_chg.value == "1") {
    bCheckGdata = true;
    bChange = true;
  } else {
    if (form.useRegisteredGid.checked == false) {
      bCheckGdata = true;
    }
  }
  if (bCheckGdata != false) {
    gdFlg = true;
    if (gdCheckInputData(bChange) == false) {
      return false;
    }
  }
  // 個人データチェック
  bCheckPdata = false;
  if (form.m_chg.value == "1") {
    bCheckPdata = true;
  } else {
    if (form.useRegisteredPid.checked == false) {
      bCheckPdata = true;
    }
  }
  if (bCheckPdata != false) {
    pdFlg = true;
    if (pdCheckInputData(bChange) == false) {
      return false;
    }
  }
  // スタッフデータをチェックする
  sdFlg = true;
  if (sdCheckInputData(true) == false) {
    return false;
  }
  // 会員データチェック
  var bChgflg = 0;
  if (form.m_chg.value == "1") {
    bChgflg = 1;
    form.m_chg.value = 0;
  }
  mdFlg = true;
  if (mdCheckInputData(true) == false) {
    if (bChgflg) {
      form.m_chg.value = bChgflg;
    }
    return false;
  }
  if (bChgflg) {
    form.m_chg.value = bChgflg;
  }
  CheckInputData_searver(pdFlg,gdFlg,sdFlg,mdFlg,bChange,true);
  return true;
}

// 辞書検索
function OnDic(dicName, eleName, post_id){
  gToolWnd = ShowDicWnd2('window/Select_Dic.asp', dicName, eleName);
}
