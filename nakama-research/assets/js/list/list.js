function OnMouseOver(buttonObj){
  buttonObj.style.backgroundColor = "#abcdef";
  buttonObj.style.color = "blue";
}


function OnMouseOut(buttonObj){
  buttonObj.style.backgroundColor = "buttonface";
  buttonObj.style.color = "buttontext";
}


function OnMouseOut2(buttonObj){
  buttonObj.style.backgroundColor = "buttonface";
  buttonObj.style.color = "buttontext";
}


function OnMemberStatusChange(status){
  document.mainForm.status.value = status;
  document.mainForm.cmd.value = "changeStatus";
  document.mainForm.submit();
}


function OnTopChange(){
  if(document.mainForm.top_type.value == "2"){
    document.mainForm.top_type.value = "1";
  } else {
    document.mainForm.top_type.value = "2";
  }
  indStart();
  document.mainForm.submit();
}


function OnCommand(cmd){
  if (cmd == '1') {
    document.mainForm.search_mode.value = '1'
  } else {
    document.mainForm.search_mode.value = '0'
  }
  indStart();
  document.mainForm.cmd;
  document.mainForm.submit();
}


function ShowResearchDetail(post_id, top_g_id, research_id, page_link){
  var buf;
  buf = page_link+'top_g_id=' + top_g_id + '&research_id=' + research_id + '&post_id=' + post_id;

  gToolWnd = open(buf,
      'DetailWnd',
      'width=850,height=830,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}


function RetShowResearch(){
  document.mainForm.submit();
}


function ShowGroupDetail(gid, param){
  var buf;
  buf = 'GroupDetail.asp?g_id=' + gid + "&mode=100";
  if (param != "") {
    buf = buf + "&" + param;
  }
  gToolWnd = open(buf,
      'DetailWnd',
      'width=750,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}


function indStart(){
  document.getElementById('indidiv').style.display="";
}
function OnSort(column,sortBy){
  console.log(column)
  var order;
  if(column != mainForm.sort.value){
    if(mainForm.order.value == ""){
      if(sortBy == "asc"){
        order= "desc";
      }
      else {
        order = "asc";
      }
    }
    else {
      order = "asc"
    }
  }
  else {
    if(mainForm.order.value == "asc"){
      order= "desc";
    }
    else {
      order = "asc";
    }
  }
  mainForm.sort.value = column;
  mainForm.order.value = order;
  indStart();
  mainForm.submit();
}
function Pagination(page){
  document.mainForm.page.value = page;
  indStart();
  document.mainForm.submit();
}
