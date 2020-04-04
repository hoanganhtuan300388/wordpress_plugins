var gToolWnd = null;

// 個人詳細画面表示
function ShowPersonalDetail(gid, pid, param){
  var buf;
  buf = 'PersonalDetail.asp?p_id=' + pid + "&mode=1" + "&g_id=" + gid;
  if (param != "") {
    buf = buf + "&" + param;
  }
  gToolWnd = open(buf,
      'DetailWnd',
      'width=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 組織詳細画面表示
function ShowGroupDetail(gid, param){
  var buf;
  buf = 'GroupDetail.asp?g_id=' + gid + "&mode=1";
  if (param != "") {
    buf = buf + "&" + param;
  }
  gToolWnd = open(buf,
      'DetailWnd',
      'width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 会員詳細画面表示
function ShowMemberDetail(lgGid, pid, aid){
  gToolWnd = open('../../MemberDetail.asp?g_id=' + aid + "&mode=1" +
      "&p_id=" + pid +
      "&lg_g_id=" + lgGid,
      'DetailWnd',
      'width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 詳細表示
function ShowDetail(gid, pid, disp, ltype, topgid, toptype, param){
  var buf;
  buf = 'Detail.asp?p_id=' + pid + "&mode=1" + "&g_id=" + gid;
  buf = buf + "&disp=" + disp + "&ltype=" + ltype + "&ttype=" + toptype + "&topgid=" + topgid;
  if (param != "") {
    buf = buf + "&" + param;
  }
  gToolWnd = open(buf,
      'DetailWnd',
      'width=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 詳細表示(会議室用)
function ShowDetail2(gid, pid, disp, ltype, topgid, toptype, param){
  var buf;
  buf = '../nakama/Detail.asp?p_id=' + pid + "&mode=1" + "&g_id=" + gid;
  buf = buf + "&disp=" + disp + "&ltype=" + ltype + "&ttype=" + toptype + "&topgid=" + topgid;
  if (param != "") {
    buf = buf + "&" + param;
  }
  gToolWnd = open(buf,
      'DetailWnd',
      'width=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 下部組織変更
function OnChangeLowerGroup(){
  document.mainForm.cmd.value = "lg";
  document.mainForm.submit();
}

// すべてを含む
function OnIncludeLower(){
  document.mainForm.cmd.value = "incLower";
  document.mainForm.submit();
}

// アンロード処理
function OnUnload(){
  if (gToolWnd != null) {
    gToolWnd.close();
    gToolWnd = null;
  }
}

// 変更処理
function OnChange(rc){
  var buf = rc.split(".");
  if ((buf.length == undefined) || (buf.length < 3)) {
  } else {
    document.mainForm.action = "list.asp?chg=1&lgGid=" + buf[0] + "&gid=" + buf[1] + "&pid=" + buf[2];
    document.mainForm.cmd.value = "change";
    document.mainForm.submit();
  }
}

// 削除処理
function OnDelete(rc){
  var buf = rc.split(".");
  if ((buf.length == undefined) || (buf.length < 3)) {
  } else {
    if (confirm("削除してもよろしいですか？") == false) {
      return;
    }
  }
}

// 同一所属組織の一覧表示
function ShowSameGroupMember(gid){
  var form = document.mainForm;
  form.cmd.value = "gmlist";
  form.m_gmgid.value = gid;
  form.submit();
}
