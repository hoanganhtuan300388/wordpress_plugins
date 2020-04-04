function dis(formName,objName,flg) {
  objForm = eval("document."+formName);
  var el = document.getElementsByTagName("a");
  if(objName == "all"){
    for(i=0;i<objForm.length;i++){
      if(objForm[i].type != undefined){
        if(objForm[i].type.toLowerCase() == "button" || objForm[i].type.toLowerCase() == "submit"){
          objForm[i].disabled = flg;
        }
      }
    }
    for (var i=0, len=el.length; i<len; i++) {
      el[i].removeAttribute('href');
    }
  }else{ 
    for(i=0;i<objForm.length;i++){
      if(objForm[i].type != undefined){
        if(objForm[i].type == objName){
          objForm[i].disabled = flg;
        }else if(objForm[i].name == objName){
          objForm[i].disabled = flg;
        }
      }
    }
  }
}
function log_out(url) {
  location.replace(url);
}
function log_out2(url) {
  if(confirm('ログアウトをして画面を閉じますがよろしいですか？')==true){
    document.mainForm.action = url;
    document.mainForm.submit();
  }
}
function home(url) {
  location.replace(url);
}
function edit_end(url, patten_cd, page_no, indication_flg){
  var url2;
  if(indication_flg != 0){
    url2 = "../index.asp";
  } else {
    url2 = "../index.asp?patten_cd="+patten_cd+"&page_no="+page_no;
  }
  location.replace(url2);
}
function home2(url, patten_cd, page_no, indication_flg){
  if(indication_flg != 0){
  } else {
    url = url+"?page_no="+page_no;
  }
  location.replace(url);
}
function htmle(str) {
  if (str!=''){
    str = str.replace(/</g,"&lt;");
    str = str.replace(/>/g,"&gt;");
    str = str.replace(/&lt; *STRONG *&gt;/gi,"<STRONG>");
    str = str.replace(/&lt; *\/ *STRONG *&gt;/gi,"<\/STRONG>");
    str = str.replace(/&lt; *EM *&gt;/gi,"<EM>");
    str = str.replace(/&lt; *\/ *EM *&gt;/gi,"<\/EM>");
    str = str.replace(/&lt; *U *&gt;/gi,"<U>");
    str = str.replace(/&lt; *\/ *U *&gt;/gi,"<\/U>");
    str = str.replace(/&lt; *BR *&gt;/gi,"<BR>");
    str = str.replace(/&lt; *FONT +([^&]*(&+(g|g[^&t][^&]*|[^&g][^&]*))*)&gt;/g,"<FONT $1>");
    str = str.replace(/&lt; *\/ *FONT *&gt;/g,"<\/FONT>");
    str = str.replace(/&lt; *A +([^&]*(&+(g|g[^&t][^&]*|[^&g][^&]*))*)&gt;/g,"<A $1>");
    str = str.replace(/&lt; *\/ *A *&gt;/g,"<\/A>");
    str = str.replace(/<A href=&quot;([^>]*)&quot;>/g, "<A href=\"$1\">");
    str = str.replace(/&quot;/gi,"\"");
  }
  return str;
}
function htmle2(str) {
  if (str!=''){
    str = str.replace(/&lt;/gi,"<");
    str = str.replace(/&gt;/gi,">");
    str = str.replace(/&quot;/gi,"\"");
    str = str.replace(/&amp;/gi, "\&");
    str = str.replace(/&lt; *A +([^&]*(&+(g|g[^&t][^&]*|[^&g][^&]*))*)&gt;/g,"<A $1>");
    str = str.replace(/&lt; *\/ *A *&gt;/g,"<\/A>");
    str = str.replace(/<A href=&quot;([^>]*)&quot;>/g, "<A href=\"$1\">");
  }
  return str;
}

function version(){
  var x,y;
  var wnd;
  x = (screen.width - 350) / 2;
  y = (screen.height - 180) / 2;
  wnd = window.open("../window/version.asp", "version", "left="+x+", top="+y+", width=350, height=180, scrollbars=no,location=no,menubar=no, status=no");
  wnd.focus();
}
function manual(url){
  var x,y;
  var wnd;
  x = (screen.width - 900) / 2;
  y = 5;
  wnd = window.open(url, "manual", "left="+x+", top="+y+", width=900, height=740,location=yes,menubar=yes,personalbar=yes,resizable=yes,scrollbars=yes,status=yes,titlebar=yes,toolbar=yes");
  wnd.focus();
}
function sozai(){
  var x,y;
  var wnd;
  x = (screen.width - 900) / 2;
  y = 5;
  wnd = window.open("http://coco.cococica.com/gallery/index.asp", "sozai", "left="+x+", top="+y+", width=900, height=740,location=yes,menubar=yes,personalbar=yes,resizable=yes,scrollbars=yes,status=yes,titlebar=yes,toolbar=yes");
  wnd.focus();
}
function iif(expr, truepart, falsepart){
  if (expr) {
    return truepart;
  }else{
    return falsepart;
  }
}
function go_public(cd, no, aspnm, flg, censor, competence){
  var x,y;
  var wnd;
  if(flg == 2){
    alert("先に上位ページを公開して下さい。");
  } else {
    if(competence==0){
      document.mainForm.patten_cd.value = cd;
      document.mainForm.page_no.value = no;
      document.mainForm.target="_self";
      document.mainForm.method="post";
      document.mainForm.mode.value = "update";
      document.mainForm.action=aspnm+".asp";
      dis("mainForm","all",true);
      document.mainForm.submit();
    }else{
      if(censor == 1 || censor == 2){
        x = (screen.width - 740) / 2;
        y = (screen.height - 650) / 2;
        wnd = window.open("../window/public_comment.asp?cd="+cd+"&no="+no+"&aspnm="+aspnm, "公開確認", "left="+x+", top="+y+", width=740, height=650, scrollbars=yes,location=no, menubar=no, status=yes, resizable=yes");
        wnd.focus();
      } else {
        document.mainForm.patten_cd.value = cd;
        document.mainForm.page_no.value = no;
        document.mainForm.target="_self";
        document.mainForm.method="post";
        document.mainForm.mode.value = "update";
        document.mainForm.action=aspnm+".asp";
        dis("mainForm","all",true);
        document.mainForm.submit();
      }
    }
  }
}
function go_public2(cd, no, aspnm, cmt){
  document.mainForm.patten_cd.value = cd;
  document.mainForm.page_no.value = no;
  document.mainForm.target="_self";
  document.mainForm.method="post";
  document.mainForm.public_comment.value = cmt;
  document.mainForm.mode.value = "update";
  dis("mainForm","all",true);
  document.mainForm.submit();
}
function openNewWin(url, name, w, h, status){
  var url_href = location.href;
  var url_path = location.pathname;
  if (w == ""){
     w = 600;
  }
  if (h == ""){
     h = 400;
  }
  if (status == ""){
     status = no;
  }
  return window.open(url, name , "resizable=yes, scrollbars=yes, menubar=no, location=no, width=" + w + ",  height=" + h + ", status");
}
function movepage(page) {
  document.pageForm.page.value = page;
  document.pageForm.disp_page.value = page;
  document.pageForm.submit();
}
function getlength(obj) {
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
function getObject(obj, i) {
  if (obj.name == undefined && obj.id == undefined) {
    return obj[i];
  } else {
    return obj;
  }
}
function getByte(value) {
  var narrow = 'abcdefghijklmnopqrstuvwxyz'
             + 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
             + '1234567890'
             + '!"#$%&\'()-=^~\\|@`[{;+:*]},<.>/?_ '
             + 'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮﾞﾟ｢｣､｡･';
  var len = 0;
  var i;
  for (i = 0; i < value.length; i++) {
    if (narrow.indexOf(value.charAt(i)) >= 0) {
      len++;
    } else {
      len += 2;
    }
  }
  return len;
}
function isNumeric(value, decimal, minus) {
  var idx_decimal;
  var idx_minus;
  var tmp_value;
  var i;
  if (decimal == 'undefined') { decimal = 0; }
  if (minus   == 'undefined') { minus   = false; }
  value += '';
  if (value == '') {
    return false;
  } else if (value.match(/[^0123456789\.\-]/)) {
    return false;
  }
  idx_decimal = value.indexOf('.');
  if (idx_decimal >= 0) {
    if (decimal <= 0 || idx_decimal == 0 || idx_decimal == value.length - 1 || value.indexOf('.', idx_decimal + 1) >= 0 || value.length - idx_decimal - 1 > decimal) {
      return false;
    }
  }
  idx_minus = value.indexOf('-');
  if (idx_minus >= 0) {
    if (!minus || idx_minus > 0 || value.indexOf('-', idx_minus + 1) >= 0) {
      return false;
    }
  }
  return true;
}
function isNarrow(value) {
  var narrow = 'abcdefghijklmnopqrstuvwxyz'
             + 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
             + '1234567890'
             + '!"#$%&\'()-=^~\\|@`[{;+:*]},<.>/?_ '
             + 'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮﾞﾟ｢｣､｡･';
  var i;
  for (i = 0; i < value.length; i++) {
    if (narrow.indexOf(value.charAt(i)) < 0) {
      return false;
    }
  }
  return true;
}

function isWide(value) {
  var narrow = 'abcdefghijklmnopqrstuvwxyz'
             + 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
             + '1234567890'
             + '!"#$%&\'()-=^~\\|@`[{;+:*]},<.>/?_ '
             + 'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮﾞﾟ｢｣､｡･';
  var i;
  for (i = 0; i < value.length; i++) {
    if (narrow.indexOf(value.charAt(i)) >= 0) {
      return false;
    }
  }
  return true;
}
function isDate(year, month, day) {
  year = parseInt(year, 10);
  if (isNaN(year)) {return false;}
  if (year < 100) {return false;}
  month = parseInt(month, 10);
  if (isNaN(month)) {return false;}
  if (month < 1 || month > 12) {return false;}
  day = parseInt(day, 10);
  if (isNaN(day)) {return false;}
  if (day < 1 || day > 31) {return false;}
  if (month == 2) {
    if (day > 29) {return false;}
    if (day == 29 && (year % 4 != 0 || year % 100 == 0 && year % 400 != 0)) {return false;}
  }
  else if (month == 4 || month == 6 || month == 9 || month == 11) {
    if (day > 30) {return false;}
  }
  return true;
}
function isTime(hour, minute, second) {
  hour = parseInt(hour, 10);
  if (isNaN(hour)) {return false;}
  if (hour < 0 || hour > 23) {return false;}
  minute = parseInt(minute, 10);
  if (isNaN(minute)) {return false;}
  if (minute < 0 || minute > 59) {return false;}
  second = parseInt(second, 10);
  if (isNaN(second)) {return false;}
  if (second < 0 || second > 59) {return false;}
  return true;
}
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
function Trim(val) {
  return RTrim(LTrim(val));
}
function isUrl(value) {
  var narrow = '<>';
  var i;
  for (i = 0; i < value.length; i++) {
    if (narrow.indexOf(value.charAt(i)) < 0) {
    } else {
      return false;
    }
  }
  return true;
}
if (!window.console){
  window.console = {
    log : function(msg){
      // do nothing.
    }
  };
}
