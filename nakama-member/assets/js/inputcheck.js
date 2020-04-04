// 許可文字判定
function IsString(tempStr, tempAllow, errorMsg){
  re = new RegExp("[^" + tempAllow + "]");
  alert(re);
  if(tempStr.search(re) != -1){
    alert(errorMsg + "に\n" + tempAllow + "\n以外の文字が使われています。");
    return(-1);
  }
  return(0);
}

// 半角文字判定
function IsNarrow(tempStr, errorMsg){
  re = new RegExp("[^ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角英数\n以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}

// 半角文字判定(※半角スペース有り)
function IsNarrow2(tempStr, errorMsg){
  re = new RegExp("[^abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角英数以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}

// パスワード判定
function IsNarrowPassword(tempStr, errorMsg){
  if (tempStr.match(/[^ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\+\-\/\!\#\$\%\&\(\)\=\~\_\?\^\[\]\@\.]/i)) {
      alert(errorMsg + "には\n半角英数か記号\n以外の文字は使わないで下さい。\nあるいは、使用できない記号が含まれています。\n使用可能な記号 +-/!#$%&()=~_?^[]@.");
      return(-1);
  }
  return(0);
}

// 半角文字判定（英数字＋記号）
function IsNarrowPlus(tempStr, errorMsg){
  if (tempStr.match(/[^ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\+\-\/\!\#\$\%\&\(\)\=\~\_\?\^\[\]\@\.\:]/i)) {
      alert(errorMsg + "には\n半角英数か記号\n以外の文字は使わないで下さい。\nあるいは、使用できない記号が含まれています。\n使用可能な記号 +-/!#$%&()=~_?^[]@.");
      return(-1);
  }
  return(0);
}

// 半角文字判定（英数字＋記号）
function IsNarrowPlus2(tempStr,errorMsg){
 if (tempStr.match(/[^ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\,\+\-\/\!\#\$\%\&\(\)\=\~\_\?\^\[\]\@\.\:]/i)) {
  alert(errorMsg+"には\n半角英数か記号\n以外の文字は使わないで下さい。\nあるいは、使用できない記号が含まれています。\n使用可能な記号 +-/!#$%&()=~_?^[]@.");
  return(-1);
 }
 return(0);
}
// 半角文字判定（英数字＋記号）
function IsNarrowPlus3(tempStr,errorMsg){
 if (tempStr.match(/[<>&""?%]/i)) {
  alert(errorMsg+"には\n半角英数か記号\n以外の文字は使わないで下さい。\nあるいは、使用できない記号が含まれています。\n使用不可な記号 <>&\"?%");
  return(-1);
 }
 return(0);
}
// 全角文字判定
function IsWide(tempStr, errorMsg){
  re = new RegExp("[ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞﾟ ]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n全角文字\n以外の文字は使わないで下さい。");
    return(-1);
  }
  for(i=0;i<tempStr.length;i++){
    if(tempStr.charCodeAt(i) > 0 && tempStr.charCodeAt(i) < 166){
      alert(errorMsg + "には\n全角文字\n以外の文字は使わないで下さい。");
      return(-1);
    }
  }
  return(0);
}

// 全角カタカナ文字判定
function IsWideKn(tempStr, errorMsg){
  return IsWide(tempStr, errorMsg);
}
// 電話番号文字判定
function IsNarrowTelNum(tempStr, errorMsg){
  re = new RegExp("[^0123456789\-]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角英数とハイフン(-)\n以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}
// 半角カタカナ文字判定
function IsNarrowKn(tempStr, errorMsg){
  re = new RegExp("[^ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞﾟ ]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角カタカナ\n以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}
// 半角数字文字判定
function IsNarrowNum(tempStr, errorMsg){
  re = new RegExp("[^0123456789]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角数字\n以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}
// 文字数判定
function IsLength(tempStr, tempMin, tempMax, errorMsg){
  if(tempStr.length < tempMin && tempStr != "" || tempStr.length > tempMax){
    alert(errorMsg + "は\n" + tempMin + "文字から" + tempMax + "文字\nの間で入力して下さい");
    return(-1);
  }
  return(0);
}

// バイト数判定
function IsLengthB(tempStr, tempMin, tempMax, errorMsg){
  len = 0;
for(i = 0; i < tempStr.length; i++)
(tempStr.charAt(i).match(/[ｱ-ﾝ]/) || escape(tempStr.charAt(i)).length < 4)?len++:len+=2;
  if ((len < tempMin) || (len > tempMax)) {
    alert(errorMsg + "は\n" + tempMin + "文字から" + tempMax + "文字\nの間で入力して下さい\n" + "（全角文字は2文字としてカウントします）");
    return -1;
  }
  return 0;
}

// 必須入力判定
function IsNull(tempStr, errorMsg){
  if(tempStr==""){
    alert(errorMsg + "\nは必ず入力して下さい");
    return(-1);
  }
  return(0);
}
// 文字数判定(フル桁必須チェック)
function IsLengthF(tempStr,tempMax,errorMsg){
 if(tempStr!=""&&tempStr.length!=tempMax){
  alert(errorMsg+"は、\n"+tempMax+"文字で入力して下さい。");
  return(-1);
 }
 return(0);
}
// 日付判定
function IsDate(tempY, tempM, tempD, tempDate, errorMsg){
  var year;
  switch (tempDate) {
  case "AD":
  case "M":
  case "T":
  case "S":
  case "H":
    year = JP2AD(tempDate, tempY.value).toString();
    break;
  default:
    year = tempY.value;
    break;
  }
  tempArray = new Array(0,3,0,1,0,1,0,0,1,0,1,0);
  if(year != "" || tempM.value != "" || tempD.value != ""){
    if(year == ""){
      alert(errorMsg + "\nを登録する場合は\n全ての欄を入力して下さい");
      tempY.select();
      tempY.focus();
      return(-1);
    }
    if(IsNarrowNum(year, errorMsg)){
      tempY.select();
      tempY.focus();
      return(-1);
    }
    if(year < 100 || tempY.value > 9999){
      alert("年は\n100から9999年\nの間を入力して下さい");
      tempY.select();
      tempY.focus();
      return(-1);
    }
    if(tempM.value == ""){
      alert(errorMsg + "\nを登録する場合は\n全ての欄を入力して下さい");
      tempM.select();
      tempM.focus();
      return(-1);
    }
    if(IsNarrowNum(tempM.value, errorMsg)){
      tempM.select();
      tempM.focus();
      return(-1);
    }
    if(tempM.value < 1 || tempM.value > 12){
      alert("月は\n1から12月\nの間を入力して下さい");
      tempM.select();
      tempM.focus();
      return(-1);
    }
    if(tempD.value == ""){
      alert(errorMsg + "\nを登録する場合は\n全ての欄を入力して下さい");
      tempD.select();
      tempD.focus();
      return(-1);
    }
    if(IsNarrowNum(tempD.value, errorMsg)){
      tempD.select();
      tempD.focus();
      return(-1);
    }
    if (((year % 4) == 0) || ((tempY.value % 100) == 0) || ((tempY.value % 400) == 0)) {
      // うるう年
      tempArray[1] = 2;
    }
    if(tempD.value < 1 || tempD.value > (31 - tempArray[tempM.value - 1])){
      alert("日は\n1から" + (31 - tempArray[tempM.value - 1]) + "日\nの間を入力して下さい");
      tempD.select();
      tempD.focus();
      return(-1);
    }
    if (tempDate.value != undefined) {
      tempY.value = ("0000" + tempY.value).substr(("0000" + tempY.value).length - 4, 4);
    }
    tempM.value = ("00" + tempM.value).substr(("00" + tempM.value).length - 2, 2);
    tempD.value = ("00" + tempD.value).substr(("00" + tempD.value).length - 2, 2);
    if (tempDate.value != undefined) {
      tempDate.value = tempY.value + tempM.value + tempD.value;
    }
  }
  return(0);
}

// 入力日付判定
function IsDateImp(imperial, tYear, tMonth, tDay, errorMsg){
  return IsDate(tYear, tMonth, tDay, imperial, errorMsg);
}

// 銀行文字判定
function IsNarrowBank(tempStr, errorMsg){
  re = new RegExp("[^ｦｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞﾟ\｢\｣\(\)\/\ \-]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n使用可能な文字以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}

//口座名義文字チェック
function IsNarrowAccaunt(tempStr, errorMsg){
 re = new RegExp("[^ｦｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝｯｧｨｩｪｫｬｭｮABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzﾞﾟ\｢\｣\(\)\/\ \-\.\･]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n使用可能な文字以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}

// ナビデータ文字判定
function IsNaviData(tempStr, errorMsg){
  re = new RegExp("[^ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\.\,]", "i");
  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角英数 .,\n以外の文字は使わないで下さい。");
    return(-1);
  }
  return(0);
}

// 和暦→西暦変換
function JP2AD(imperial, val){
  var res;
  switch (imperial) {
  case "M": // 明治
    res = Number(val) + 1867;
    break;
  case "T": // 大正
    res = Number(val) + 1911;
    break;
  case "S": // 昭和
    res = Number(val) + 1925;
    break;
  case "H": // 平成
    res = Number(val) + 1988;
    break;
  default:
    res = val;
    break;
  }
  return res;
}

// 日付編集（YYYYMMDD）
// 備考:年（西暦の場合）、月、日は左ゼロ詰めされる
function MakeYMD(imperial, y, m, d){
  if (imperial != null) {
    y = JP2AD(imperial, y);
  }
  y = ("0000" + y).slice(-4);
  m = ("00" + m).slice(-2);
  d = ("00" + d).slice(-2);
  return (y + m + d);
}

// 日付未入力チェック
function IsDateEmpty(y, m, d){
  if ((y == "") && (m == "") && (d == "")) {
    return true;
  }
  return false;
}

// URL判定
// 備考:判定文字列が http:// または https:// で始まっていれば正当と評価する
function IsURL(tempStr, errorMsg) {

  if (!(tempStr.match(/(http|https):\/\//))) {
    alert(errorMsg + "は\nhttp:// または https:// で始めて下さい");
    return (-1);
  }
  return (0);
}

//  Mailﾁｪｯｸ
function isMail(value,errorMsg) {
 if (value == "") return(0);
 if (value.match(/[!#-9A-~]+@[\w\.-]+\.\w{2,}$/) == null){
  alert(errorMsg+"が\n正しく入力されていません。");
  return(-1);
 }
 return(0);
}

//  Mailﾁｪｯｸ(追加Mail)
function isMail2(value,errorMsg) {
 var buf;
 var i;
 if (value == "") return(0);

 buf = value.split(',');

 for(i=0;i<=buf.length-1;i++){
   if (buf[i].match(/[!#-9A-~]+[@][a-z0-9]+.+[^.]$/i) == null){
   alert(errorMsg+"が\n正しく入力されていません。");
   return(-1);
  }
 }
 return(0);
}
// パスワード判定（英小文字＋数字)
function IsPassCombi1(tempStr,errorMsg){
 var narrow;
 var checkflg1 = false;
 var checkflg2 = false;

 //半角小文字がふくまれていないとエラーとする
 narrow='abcdefghijklmnopqrstuvwxyz';
 for(i=0;i<narrow.length;i++){
  if(tempStr.indexOf(narrow.charAt(i))!=-1){
   checkflg1 = true;
  }
 }
 if(checkflg1 == false){
   alert(errorMsg+"には\n半角小文字を含めて下さい。");
   return(-1);
 }

 //半角数字がふくまれていないとエラーとする
 narrow='1234567890';
 for(i=0;i<narrow.length;i++){
  if(tempStr.indexOf(narrow.charAt(i))!=-1){
   checkflg2 = true;
  }
 }
 if(checkflg2 == false){
   alert(errorMsg+"には\n半角数字を含めて下さい。");
   return(-1);
 }

 return(0);
}
// パスワード判定（英小文字＋英大文字＋数字)
function IsPassCombi2(tempStr,errorMsg){
 var narrow;
 var checkflg1 = false;
 var checkflg2 = false;
 var checkflg3 = false;
 //半角大文字がふくまれていないとエラーとする
 narrow='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
 for(i=0;i<narrow.length;i++){
  if(tempStr.indexOf(narrow.charAt(i))!=-1){
   checkflg1 = true;
  }
 }
 if(checkflg1 == false){
   alert(errorMsg+"には\n半角大文字を含めて下さい。");
   return(-1);
 }

 //半角小文字がふくまれていないとエラーとする
 narrow='abcdefghijklmnopqrstuvwxyz';
 for(i=0;i<narrow.length;i++){
  if(tempStr.indexOf(narrow.charAt(i))!=-1){
   checkflg2 = true;
  }
 }
 if(checkflg2 == false){
   alert(errorMsg+"には\n3半角小文字を含めて下さい。");
   return(-1);
 }

 //半角数字がふくまれていないとエラーとする
 narrow='1234567890';
 for(i=0;i<narrow.length;i++){
  if(tempStr.indexOf(narrow.charAt(i))!=-1){
   checkflg3 = true;
  }
 }
 if(checkflg3 == false){
   alert(errorMsg+"には\n半角数字を含めて下さい。");
   return(-1);
 }

 return(0);
}
