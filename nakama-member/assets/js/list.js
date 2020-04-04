// ソートマーク（▲▼）表示
// 引数:[in] リストヘッダ
//     :[in] ソート順
function AddSortMark(obj, order) {
  var i;

  if (obj == undefined) {
    return;
  }
  if (order == "asc") {
    obj.innerHTML += "▲";
  } else {
    obj.innerHTML += "▼";
  }
}

// 全選択
function OnSelectAll(form, bSelect) {
  var i;
  if (bSelect == 0) {
    form.cmd.value = "relAll";
  } else {
    form.cmd.value = "selAll";
  }
  form.submit();
}

// 前ページ表示
function OnPrevPage(form) {
  form.cmd.value = "prev";
  form.submit();
}

// 次ページ表示
function OnNextPage(form) {
  form.cmd.value = "next";
  form.submit();
}

// 最終ページ表示
function OnLastPage(form) {
  form.cmd.value = "last";
  form.submit();
}

// 先頭ページ表示
function OnFirstPage(form) {
  form.cmd.value = "first";
  form.submit();
}

// ソート
function OnSort(column,sortBy,id) {
  var order;
  var sort = $("input[name=sort"+id+"]").val();
  var in_order = $("input[name=order"+id+"]").val();
  if(column != sort){
    if(in_order == ""){
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
    if(in_order == "asc"){
      order= "desc";
    }
    else {
      order = "asc";
    }
  }
  $("input[name=sort"+id+"]").val(column);
  $("input[name=order"+id+"]").val(order);
  indStart(id);
  $("form#mainForm"+id).submit();
}

// ソート
function OnSortOther() {
  var order;
  if (mainForm.order != undefined) {
    if (mainForm.order.value == "asc") {
      order = "desc";
    } else {
      order = "asc";
    }
    mainForm.sort.value = "";
    mainForm.order.value = order;
  } else {
    mainForm.sort.value = "";
  }
  mainForm.page.value = "";
  mainForm.cmd.value = "sortOther";
  mainForm.submit();
}

function notes(e) {
  if (!e) var e = event;
  if (document.all) {
    if (e.button == 2) {
      return false;
    }
  }
  if (document.layers) {
    if (e.which == 3) {
      return false;
    }
  }
}
if (document.layers) document.captureEvents(Event.MOUSEDOWN);
document.onmousedown = notes;

function OnMouseOver(buttonObj) {
  buttonObj.style.backgroundColor = "#abcdef";
  buttonObj.style.color = "blue";
}

function OnMouseOut(buttonObj) {
  buttonObj.style.backgroundColor = "buttonface";
  buttonObj.style.color = "buttontext";
}

function OnMouseOut2(buttonObj) {
  buttonObj.style.backgroundColor = "buttonface";
  buttonObj.style.color = "buttontext";
}


function OnTopChange(id) {
  var top_type = $("input[name=top_type]").val();
  if (top_type == "2") {
    $("input[name=top_type]").val(1);
  } else {
    $("input[name=top_type]").val(2);
  }
  indStart(id);
  $("form#mainForm"+id).submit();
}


function OnCommand(id,event) {

  if(event != null){
    if(event.target.checked == false){
      $("#in_wer_check").prop('checked', true);
    }
    else {
      $("#in_wer_check").prop('checked', false);
    }
  }
  $("input[name=page"+id+"]").value = '1';
  indStart(id);
  $("form#mainForm"+id).submit();
  
}

function ChangeMemberStatus(lgGid, gid, pid) {
  document.mainForm.cmd.value = "del";
  document.mainForm.action = "list.asp?lgGid=" + lgGid + "&gid=" + gid + "&pid=" + pid;
  document.mainForm.submit();
}

function OnDetaile() {

  gToolWnd = window.open('https://wn.cococica.com/nakama/sedai_link/login_nakama.asp?top_g_id=7783D7E5504009552D63B5B829DCA81BEE8C82&forward_mail=14F8399B33A360F30BBA00302AE4955FE048B5749AD5D3494C0B&p_id=91255D4B7025C712521D71802FAB6753F99CDF38783FAE13&pass=E35DCECD0C07A026D47F5CC39897', 'nakamaWnd', 'width=1024,height=768,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');

}

function OnFeeStatus() {
  gToolWnd = window.open('./FeeStatus.asp', 'DetailWnd', 'width=950,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function indStart(id) {
  document.getElementById('indidiv'+id).style.display = "";
}