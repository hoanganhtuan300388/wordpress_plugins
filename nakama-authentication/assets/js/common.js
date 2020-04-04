//  SPAN TAG�̃e�L�X�g�Z�b�g
//  ���ʏ�innerText��fireFox�̏ꍇ�̂�textContent�ɃZ�b�g����
function spanSetEx(tagID){
  var tagObj = document.getElementById(tagID);
  tagObj.setText = function (srcText) {
    if (navigator.userAgent.indexOf("Firefox") > -1) {
      tagObj.textContent = srcText;
    } else {
      tagObj.innerText = srcText;
    }
  }
  return tagObj;
}
function spanSetOpenerEx(tagID){
  var tagObj = window.opener.document.getElementById(tagID);
  tagObj.setText = function (srcText) {
    if (navigator.userAgent.indexOf("Firefox") > -1) {
      tagObj.textContent = srcText;
    } else {
      tagObj.innerText = srcText;
    }
  }
  return tagObj;
}

// �s���{���R���{�̒l�Z�b�g
function StateSelectOptions() {
  var state = new Array (
    "",
    "�k�C��",
    "�X��","��茧","�{�錧","�H�c��","�R�`��","������",
    "��ʌ�","��t��","�����s","�_�ސ쌧",
    "��錧","�Ȗ،�","�Q�n��","���쌧","�R����",
    "�V����","�x�R��","�ΐ쌧","���䌧",
    "�򕌌�","�É���","���m��","�O�d��",
    "���ꌧ","���s�{","���{","���Ɍ�","�ޗǌ�","�a�̎R��",
    "���挧","������","���R��","�L����","�R����",
    "������","���쌧","���Q��","���m��",
    "������","���ꌧ","���茧","�F�{��","�啪��","�{�茧","��������",
    "���ꌧ"
  );
  var i;

  for (i = 0; i < state.length; i++) {
    document.writeln('<option value="' + state[i] + '">' + state[i] + '</option>');
  }
}

// �s���{���R���{�̒l�Z�b�g�i�����I������j
function StateSelectOptions2(val) {
  var state = new Array (
    "",
    "�k�C��",
    "�X��","��茧","�{�錧","�H�c��","�R�`��","������",
    "��ʌ�","��t��","�����s","�_�ސ쌧",
    "��錧","�Ȗ،�","�Q�n��","���쌧","�R����",
    "�V����","�x�R��","�ΐ쌧","���䌧",
    "�򕌌�","�É���","���m��","�O�d��",
    "���ꌧ","���s�{","���{","���Ɍ�","�ޗǌ�","�a�̎R��",
    "���挧","������","���R��","�L����","�R����",
    "������","���쌧","���Q��","���m��",
    "������","���ꌧ","���茧","�F�{��","�啪��","�{�茧","��������",
    "���ꌧ"
  );
  var i;

  for (i = 0; i < state.length; i++) {
    if (state[i] == val) {
      document.writeln('<option value="' + state[i] + '" selected>' + state[i] + '</option>');
    } else {
      document.writeln('<option value="' + state[i] + '">' + state[i] + '</option>');
    }
  }
}

// �N�b�L�[�̒l���擾
function GetCookie(key,  tmp1, tmp2, xx1, xx2, xx3) {
    tmp1 = " " + document.cookie + ";";
    xx1 = xx2 = 0;
    len = tmp1.length;
    while (xx1 < len) {
        xx2 = tmp1.indexOf(";", xx1);
        tmp2 = tmp1.substring(xx1 + 1, xx2);
        xx3 = tmp2.indexOf("=");
        if (tmp2.substring(0, xx3) == key) {
            return(unescape(tmp2.substring(xx3 + 1, xx2 - xx1 - 1)));
        }
        xx1 = xx2 + 1;
    }
    return("");
}

// �N�b�L�[�ɒl(�y�[�W�̗L��������)���Z�b�g
function SetCookie(key, val, tmp) {
    tmp = key + "=" + escape(val) + "; ";
    tmp += "expires=Fri, 31-Dec-2030 23:59:59; ";
    document.cookie = tmp;
}

// ���j���[�I��
function OnChangeMenu(mode) {
  var buf = location.pathname.split("/");

  switch(mode) {
  case "1": //����Ǘ�
    location.href = "/" + buf[1] + "/MemberMng/MemberList/list.asp";
    break;

  case "2": //�X�^�b�t�Ǘ�
    location.href = "/" + buf[1] + "/StaffMng/list.asp";
    break;

  case "3": //�g�D��{�Ǘ�
    location.href = "/" + buf[1] + "/GroupMng/GroupList.asp";
    break;

  case "4": //�����g�D�Ǘ�
    location.href = "/" + buf[1] + "/LowerGroupMng/list.asp";
    break;

  case "5": //����f�[�^�o�^
    location.href = "/" + buf[1] + "/MemberDataReg/input.asp";
    break;

  case "6": //�l�f�[�^�o�^
    location.href = "/" + buf[1] + "/PersonalDataReg/input.asp";
    break;

  case "7": //�g�D�f�[�^�o�^
    location.href = "/" + buf[1] + "/GroupDataReg/input.asp";
    break;

  case "8": //�����e�i���X
    location.href = "/" + buf[1] + "/Mainte/Dic_Regist.asp";
    break;

  //���h�Ǘ�
  case "9":
    if (param == 4) {
      location.href = "/" + buf[1] + "/CardMng/S_Kojinkoukan.asp";
    } else {
      location.href = "/" + buf[1] + "/CardMng/Kojinkoukan.asp";
    }
      break;
  }
}

// �w���v�E�C���h�E�\��
function OnHelp(url){
  open(url, '_blank');
}

// �v���C�o�V�[�����E�C���h�E�\��
function OnAboutPrivacy(url) {
  open(url, 'DetailWnd','width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// ���X�y�[�X����
function LTrim(str) {
   var whitespace = new String("�@\x20\t\n\r\xa0");
   var s = new String(str);
   if (whitespace.indexOf(s.substr(0, 1)) != -1) {
      var j=0, i = s.length;
      while (j < i && whitespace.indexOf(s.substr(j, 1)) != -1)
      j++;
      s = s.substr(j, i);
   }
   return s;
}

// �E�X�y�[�X����
function RTrim(str) {
   var whitespace = new String("�@ \t\n\r\xa0");
   var s = new String(str);

   if (whitespace.indexOf(s.substr(s.length-1, 1)) != -1) {
      var i = s.length - 1;
      while (i >= 0 && whitespace.indexOf(s.substr(i, 1)) != -1)
         i--;
      s = s.substr(0, i+1);
   }
   return s;
}

// �X�y�[�X����
function Trim(val) {
  return RTrim(LTrim(val));
}

// �I�u�W�F�N�g���̎擾
function getLength(obj) {
  if (obj) {
    if (obj.name == undefined && obj.id == undefined) {
      return obj.length;
    } else {
      return 1;
    }
  } else {
    return 0;
  }
}

// �I�u�W�F�N�g�̎擾
function getObject(obj, i) {
  if (obj.name == undefined && obj.id == undefined) {
    return obj[i];
  } else {
    return obj;
  }
}

// �J���}�ҏW
function conmaEdit(theVal){
  var i_part = "";
  var d_part = "";
  var decimal
  var comma  = 0;
  var i;
  var num;

  // ����l��ݒ肷��
    decimal = 0;

  num = theVal;

  // �������Ə������ɕ�������
  num += "";
  if (num.indexOf(".") >= 0) {
    i_part = num.substr(0, num.indexOf("."));
    d_part = num.substr(num.indexOf(".") + 1);
  } else {
    i_part = num;
  }

  // �J���}�̑}�������擾����
  comma = Math.floor((i_part.length - 1) / 3);
  if (num.charAt(0) == "-" && (i_part.length - 1) % 3 == 0) {
    comma -= 1;
  }

  // �J���}��}������
  for (i = 0; i < comma; i++) {
    i_part = i_part.substr(0, i_part.length - (3 * (i + 1) + i)) + "," + i_part.substr(i_part.length - (3 * (i + 1) + i));
  }

  // �����ȉ��������w�肳��Ă���ꍇ
  if (decimal > 0) {
    if (d_part.length < decimal) {
      do {
        d_part += "0";
      } while (d_part.length < decimal)
    } else if (d_part.length > decimal) {
      d_part = d_part.substr(0, decimal);
    }
  }

  // ����l��ݒ肷��
  if (i_part + "" == "NaN") {
    i_part = "0";
  }
  // �����ϊ������������Ԃ�
  if (d_part == "") {
    return i_part;
  } else {
    return i_part + "." + d_part;
  }
}

// �o�C�g���̎擾
// �����Fvalue,  I, �����̑ΏۂƂȂ鎮
//     �F�߂�l, O, value �̃o�C�g��
function getByte(value) {
  var narrow = 'abcdefghijklmnopqrstuvwxyz'
             + 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
             + '1234567890'
             + '!"#$%&\'()-=^~\\|@`[{;+:*]},<.>/?_ '
             + '�������������������������������������������ܦݧ���������ߢ����';
  var len = 0;
  var i;
  // ��`�������p������ɂȂ��������Q�o�C�g�����Ƃ��ăo�C�g�������߂�
  for (i = 0; i < value.length; i++) {
    if (narrow.indexOf(value.charAt(i)) >= 0) {
      len++;
    } else {
      len += 2;
    }
  }
  return len;
}

// �S�p/���p�������� 
//���� �F str �`�F�b�N���镶���� 
//flg 0:���p�����A1:�S�p���� 
//�߂�l�F true:�܂܂�Ă���Afalse:�܂܂�Ă��Ȃ�  
function CheckLength(str,flg) { 
  for (var i = 0; i < str.length; i++) { 
    var c = str.charCodeAt(i); 
    // Shift_JIS: 0x0 �` 0x80, 0xa0 , 0xa1 �` 0xdf , 0xfd �` 0xff 
    // Unicode : 0x0 �` 0x80, 0xf8f0, 0xff61 �` 0xff9f, 0xf8f1 �` 0xf8f3 
    if ( (c >= 0x0 && c < 0x81) || (c == 0xf8f0) || (c >= 0xff61 && c < 0xffa0) || (c >= 0xf8f1 && c < 0xf8f4)) { 
      if(!flg) return true; 
    } else { 
      if(flg) return true; 
    } 
  } 
  return false; 
}
