$(function() {
  $('.pageimg a').lightBox();
  $('.itemimg a').lightBox();
});


function jump(link_type, url, cd, domain, root){
 if(link_type == 1){
  if(url.indexOf("http",0) == -1){
   window.open("http://"+url+"");
 }else{
   window.open(url);
 }
}else{
  if(cd == 7 || cd == 8){
    
   location.href = "https://"+domain+root+"/"+url;
   
 }else{
  
   location.href = "http://"+domain+root+"/"+url;
   
 }
}
}

function pass(cd, no){
  var url = "https://www.nakamacloud.com/dantai/edit/password.asp?page_no="+no;
  location.href = url;
}


function OnCommand(cmd){
 document.mainForm.cmd.value = cmd;
 document.mainForm.submit();
}

function OnCommandSp(cmd){
 document.mainForm.keyword.value =  document.mainForm.keyword_sp.value;
 document.mainForm.cmd.value = cmd;
 document.mainForm.submit();
}

function OnSearch(cmd){
 if(cmd == '1'){
  document.mainForm.search_mode.value = '1'
}else{
  document.mainForm.search_mode.value = '0'
}
document.mainForm.cmd;
document.mainForm.submit();
}
function OnTopChange(){
 if(document.mainForm.top_type.value == "2"){
  document.mainForm.top_type.value = "1";
}else{
  document.mainForm.top_type.value = "2";
}
document.mainForm.submit();
}


function SetHeightBySideMenu() {
  var l_table = 0;
  var r_table = 0;
  var head_div = 0;
  var body_div = 0;
  
  head_div = document.getElementById("head_div").offsetHeight;
  body_div = document.getElementById("body_div").offsetHeight;
  if (l_table > r_table) {
    if (l_table > head_div + body_div) {
      document.getElementById("body_div").style.height = l_table - head_div;
    }
  } else {
    if (r_table > head_div + body_div) {
      document.getElementById("body_div").style.height = r_table - head_div;
    }
  }
}

function OnDownloadFile(fileName){
  document.mainForm.fileName.value=fileName;
  document.mainForm.target="_self";
  document.mainForm.method="post";
  document.mainForm.action="index.asp";
  document.mainForm.submit();
}
function startEffect1() {
  if ( document.getElementById("slideshow1") != undefined ){
    document.getElementById("slideshow1").style.display = "";
  }
  $('#slideshow1').cycle({
    fx: '',
    timeout: '1000',
    speed: '1000',
    delay:  -1000,
    random:  0,
    pause:  0
  });
}
function startEffect2() {
  if ( document.getElementById("slideshow2") != undefined ){
    document.getElementById("slideshow2").style.display = "";
  }
  $('#slideshow2').cycle({
    fx: '',
    timeout: '1000',
    speed: '1000',
    delay:  -1000,
    random:  0,
    pause:  0
  });
}
function startEffect1() {
  if ( document.getElementById("slideshow1") != undefined ){
    document.getElementById("slideshow1").style.display = "";
  }
  $('#slideshow1').cycle({
    fx: '',
    timeout: '1000',
    speed: '1000',
    delay:  -1000,
    random:  0,
    pause:  0
  });
}
$(function(){
  $('iframe').each(function(){
    var IframeWidth=$(this).contents().find('body').width();
    var IframeHeight=$(this).contents().find('body').height();
    $(this).css({width:IframeWidth,height:IframeHeight});
  });
});