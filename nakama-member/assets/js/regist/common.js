//  SPAN TAGのテキストセット
//  ※通常innerTextでfireFoxの場合のみtextContentにセットする
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

// 都道府県コンボの値セット
function StateSelectOptions() {
  var state = new Array (
    "",
    "北海道",
    "青森県","岩手県","宮城県","秋田県","山形県","福島県",
    "埼玉県","千葉県","東京都","神奈川県",
    "茨城県","栃木県","群馬県","長野県","山梨県",
    "新潟県","富山県","石川県","福井県",
    "岐阜県","静岡県","愛知県","三重県",
    "滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県",
    "鳥取県","島根県","岡山県","広島県","山口県",
    "徳島県","香川県","愛媛県","高知県",
    "福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県",
    "沖縄県"
  );
  var i;

  for (i = 0; i < state.length; i++) {
    document.writeln('<option value="' + state[i] + '">' + state[i] + '</option>');
  }
}

// 都道府県コンボの値セット（初期選択あり）
function StateSelectOptions2(val) {
  var state = new Array (
    "",
    "北海道",
    "青森県","岩手県","宮城県","秋田県","山形県","福島県",
    "埼玉県","千葉県","東京都","神奈川県",
    "茨城県","栃木県","群馬県","長野県","山梨県",
    "新潟県","富山県","石川県","福井県",
    "岐阜県","静岡県","愛知県","三重県",
    "滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県",
    "鳥取県","島根県","岡山県","広島県","山口県",
    "徳島県","香川県","愛媛県","高知県",
    "福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県",
    "沖縄県"
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

// クッキーの値を取得
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

// クッキーに値(ページの有効期限等)をセット
function SetCookie(key, val, tmp) {
    tmp = key + "=" + escape(val) + "; ";
    tmp += "expires=Fri, 31-Dec-2030 23:59:59; ";
    document.cookie = tmp;
}

// メニュー選択
function OnChangeMenu(mode) {
  var buf = location.pathname.split("/");

  switch(mode) {
  case "1": //会員管理
    location.href = "/" + buf[1] + "/MemberMng/MemberList/list.asp";
    break;

  case "2": //スタッフ管理
    location.href = "/" + buf[1] + "/StaffMng/list.asp";
    break;

  case "3": //組織基本管理
    location.href = "/" + buf[1] + "/GroupMng/GroupList.asp";
    break;

  case "4": //下部組織管理
    location.href = "/" + buf[1] + "/LowerGroupMng/list.asp";
    break;

  case "5": //会員データ登録
    location.href = "/" + buf[1] + "/MemberDataReg/input.asp";
    break;

  case "6": //個人データ登録
    location.href = "/" + buf[1] + "/PersonalDataReg/input.asp";
    break;

  case "7": //組織データ登録
    location.href = "/" + buf[1] + "/GroupDataReg/input.asp";
    break;

  case "8": //メンテナンス
    location.href = "/" + buf[1] + "/Mainte/Dic_Regist.asp";
    break;

  //名刺管理
  case "9":
    if (param == 4) {
      location.href = "/" + buf[1] + "/CardMng/S_Kojinkoukan.asp";
    } else {
      location.href = "/" + buf[1] + "/CardMng/Kojinkoukan.asp";
    }
      break;
  }
}

// ヘルプウインドウ表示
function OnHelp(url){
  open(url, '_blank');
}

// プライバシー説明ウインドウ表示
function OnAboutPrivacy(url) {
  open(url, 'DetailWnd','width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

// 左スペース除去
function LTrim(str) {
   var whitespace = new String("　\x20\t\n\r\xa0");
   var s = new String(str);
   if (whitespace.indexOf(s.substr(0, 1)) != -1) {
      var j=0, i = s.length;
      while (j < i && whitespace.indexOf(s.substr(j, 1)) != -1)
      j++;
      s = s.substr(j, i);
   }
   return s;
}

// 右スペース除去
function RTrim(str) {
   var whitespace = new String("　 \t\n\r\xa0");
   var s = new String(str);

   if (whitespace.indexOf(s.substr(s.length-1, 1)) != -1) {
      var i = s.length - 1;
      while (i >= 0 && whitespace.indexOf(s.substr(i, 1)) != -1)
         i--;
      s = s.substr(0, i+1);
   }
   return s;
}

// スペース除去
function Trim(val) {
  return RTrim(LTrim(val));
}

// オブジェクト個数の取得
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

// オブジェクトの取得
function getObject(obj, i) {
  if (obj.name == undefined && obj.id == undefined) {
    return obj[i];
  } else {
    return obj;
  }
}

// カンマ編集
function conmaEdit(theVal){
  var i_part = "";
  var d_part = "";
  var decimal
  var comma  = 0;
  var i;
  var num;

  // 既定値を設定する
    decimal = 0;

  num = theVal;

  // 整数部と小数部に分割する
  num += "";
  if (num.indexOf(".") >= 0) {
    i_part = num.substr(0, num.indexOf("."));
    d_part = num.substr(num.indexOf(".") + 1);
  } else {
    i_part = num;
  }

  // カンマの挿入数を取得する
  comma = Math.floor((i_part.length - 1) / 3);
  if (num.charAt(0) == "-" && (i_part.length - 1) % 3 == 0) {
    comma -= 1;
  }

  // カンマを挿入する
  for (i = 0; i < comma; i++) {
    i_part = i_part.substr(0, i_part.length - (3 * (i + 1) + i)) + "," + i_part.substr(i_part.length - (3 * (i + 1) + i));
  }

  // 小数以下桁数が指定されている場合
  if (decimal > 0) {
    if (d_part.length < decimal) {
      do {
        d_part += "0";
      } while (d_part.length < decimal)
    } else if (d_part.length > decimal) {
      d_part = d_part.substr(0, decimal);
    }
  }

  // 既定値を設定する
  if (i_part + "" == "NaN") {
    i_part = "0";
  }
  // 書式変換した文字列を返す
  if (d_part == "") {
    return i_part;
  } else {
    return i_part + "." + d_part;
  }
}

// バイト長の取得
// 引数：value,  I, 処理の対象となる式
//     ：戻り値, O, value のバイト長
function getByte(value) {
  var narrow = 'abcdefghijklmnopqrstuvwxyz'
             + 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
             + '1234567890'
             + '!"#$%&\'()-=^~\\|@`[{;+:*]},<.>/?_ '
             + 'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮﾞﾟ｢｣､｡･';
  var len = 0;
  var i;
  // 定義した半角文字列にない文字を２バイト文字としてバイト長を求める
  for (i = 0; i < value.length; i++) {
    if (narrow.indexOf(value.charAt(i)) >= 0) {
      len++;
    } else {
      len += 2;
    }
  }
  return len;
}

// 全角/半角文字判定 
//引数 ： str チェックする文字列 
//flg 0:半角文字、1:全角文字 
//戻り値： true:含まれている、false:含まれていない  
function CheckLength(str,flg) { 
  for (var i = 0; i < str.length; i++) { 
    var c = str.charCodeAt(i); 
    // Shift_JIS: 0x0 ～ 0x80, 0xa0 , 0xa1 ～ 0xdf , 0xfd ～ 0xff 
    // Unicode : 0x0 ～ 0x80, 0xf8f0, 0xff61 ～ 0xff9f, 0xf8f1 ～ 0xf8f3 
    if ( (c >= 0x0 && c < 0x81) || (c == 0xf8f0) || (c >= 0xff61 && c < 0xffa0) || (c >= 0xf8f1 && c < 0xf8f4)) { 
      if(!flg) return true; 
    } else { 
      if(flg) return true; 
    } 
  } 
  return false; 
}