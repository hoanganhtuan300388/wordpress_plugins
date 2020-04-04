function chkinput(){
 if (document.mainForm.b_user_id.value == "" || document.mainForm.b_password.value == ""){
  if(document.mainForm.b_user_id.value == ""){
    alert("ユーザＩＤを入力して下さい。");
  }
  if (document.mainForm.b_password.value == ""){
    alert("パスワードを入力して下さい。");
  }
 }else{
   document.mainForm.submit();
 }

}

function pass(cd, no){

  var url = "https://www.nakamacloud.com/dantai/edit/password.asp?page_no="+no;

  location.replace(url);
}


function inquire_nakama(){
 var x=(screen.width-750)/2;
 var y=20;

 var wnd = window.open("inq_nakama.asp"
 + "?lg_g_id=6C44B5E962ABE85843C194CBFA6C9A6BA6D95C1FFA1CD59F"
 + "&page_no=65"
 , "inq_nakama"
 , "left=" + x
 + ",top=" + y
 + ",width=750"
 + ",height=600"
 + ",scrollbars=yes"
 + ",location=no"
 + ",menubar=no"
 + ",status=yes"
 + ",resizable=yes");

 wnd.focus();
}

function openRegActive(page_no, fmail){
 var x=(screen.width-750)/2;
 var y=20;


 var wnd = window.open("https://www.nakamacloud.com/dantai/nakama/login_active_ex.asp"
 + "?page_no=" + page_no
 + "&service_patten_cd=33"
 + "&service_page_no=65"
 , "regActive"
 , "left=" + x
 + ",top=" + y
 + ",width=1000"
 + ",height=900"
 + ",scrollbars=yes"
 + ",status=yes"
 + ",resizable=yes");

 wnd.focus();
}

function goBack() {
  window.history.back();
}
