var gToolWnd = null;

// �l�ڍ׉�ʕ\��
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

// �g�D�ڍ׉�ʕ\��
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

// ����ڍ׉�ʕ\��
function ShowMemberDetail(lgGid, pid, aid){
  gToolWnd = open('../../MemberDetail.asp?g_id=' + aid + "&mode=1" +
      "&p_id=" + pid +
      "&lg_g_id=" + lgGid,
      'DetailWnd',
      'width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// �ڍו\��
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

// �ڍו\��(��c���p)
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

// �����g�D�ύX
function OnChangeLowerGroup(){
  document.mainForm.cmd.value = "lg";
  document.mainForm.submit();
}

// ���ׂĂ��܂�
function OnIncludeLower(){
  document.mainForm.cmd.value = "incLower";
  document.mainForm.submit();
}

// �A�����[�h����
function OnUnload(){
  if (gToolWnd != null) {
    gToolWnd.close();
    gToolWnd = null;
  }
}

// �ύX����
function OnChange(rc){
  var buf = rc.split(".");
  if ((buf.length == undefined) || (buf.length < 3)) {
  } else {
    document.mainForm.action = "list.asp?chg=1&lgGid=" + buf[0] + "&gid=" + buf[1] + "&pid=" + buf[2];
    document.mainForm.cmd.value = "change";
    document.mainForm.submit();
  }
}

// �폜����
function OnDelete(rc){
  var buf = rc.split(".");
  if ((buf.length == undefined) || (buf.length < 3)) {
  } else {
    if (confirm("�폜���Ă���낵���ł����H") == false) {
      return;
    }
  }
}

// ���ꏊ���g�D�̈ꗗ�\��
function ShowSameGroupMember(gid){
  var form = document.mainForm;
  form.cmd.value = "gmlist";
  form.m_gmgid.value = gid;
  form.submit();
}
