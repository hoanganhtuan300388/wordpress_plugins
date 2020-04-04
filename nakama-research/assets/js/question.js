history.forward();

function OnOtherRadio(val){
  var form = document.mainForm;
  var i;
  if(val != ""){
    for(i=0;i<form.other_qvalue.length;i++){
      if(form.other_qvalue[i].id == val){
        if(form.other_qvalue[i].type != "hidden"){
          form.other_qvalue[i].readOnly = "";
          form.other_qvalue[i].style.backgroundColor = "#FFFFFF";
        }
      }else{
        if(form.other_qvalue[i].type != "hidden"){
          form.other_qvalue[i].readOnly = "readonly";
          form.other_qvalue[i].style.backgroundColor = "#C0C0C0";
        }
      }
    }
  }else{
    for(i=0;i<form.other_qvalue.length;i++){
      if(form.other_qvalue[i].type != "hidden"){
        form.other_qvalue[i].readOnly = "readonly";
        form.other_qvalue[i].style.backgroundColor = "#C0C0C0";
      }
    }
  }
}


function OnOtherCheckBox(val){
  var form = document.mainForm;
  if(val != ""){
    if(form[val].type != "hidden"){
      if(form[val].readOnly != true){
        form[val].readOnly = "readonly";
        form[val].style.backgroundColor = "#C0C0C0";
      }else{
        form[val].readOnly = "";
        form[val].style.backgroundColor = "#FFFFFF";
      }
    }
  }
}


function OnCommand(cmd){
  var form = document.mainForm;
  var i,j;
  var word;

  
  form.action = "ans_research.asp";

  switch (cmd) {
  case "back":

    form.action = "agreement.asp";

    break;
  case "stop":

    if(!confirm("入力内容を破棄します。よろしいですか？")){

      return;
    }
    window.close();
    break;
  case "next":
    form.cmd.value = cmd;

    j = 0;
    for(i=0;i<eval(form.qvalue.length);i++){
      if(form.qvalue[i].checked == true){
        form.q_no.value = form.qnext[i].value;
        j = 1;
      }
    }
  
    if(j == 0){
      alert("必ず回答するようお願いいたします。");
      return;
    }
  
    break;

  case "download":
    document.mainForm.action = "detailDownload.asp";
    break;


  defalt:
    break;
  }

  if(form.q_no.value == form.now_q_no.value){
    form.q_no.value = eval(form.q_no.value) + 1;
  }

  form.submit();

}

function IsLengthB2(tempStr,tempMin,tempMax,errorMsg){
var i;
var len = 0;
for(i=0;i<tempStr.length;i++)
(tempStr.charAt(i).match(/[ｱ-ﾝ]/)||escape(tempStr.charAt(i)).length<4)?len++:len+=2;
 if((len<tempMin)||(len>tempMax)) {
  alert(errorMsg+"は\n"+tempMin+"文字から"+tempMax+"文字\nの間で入力して下さい。");
  return -1;
 }
 return 0;
}


function DispDetail(top_g_id, g_research_id, filename, dl){
  gToolWnd=open('./DispDetail.asp?top_g_id='+top_g_id+'&research_id='+g_research_id+'&filename='+filename+'&dl='+dl,
                'DispDetail',
                'width=850,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function OnDownloadFile(fileName){
  document.mainForm.filename.value = fileName;
  OnCommand("download");
}