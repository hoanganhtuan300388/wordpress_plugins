// ソートマーク（▲▼）表示
// 引数:[in] リストヘッダ
//     :[in] ソート順
function AddSortMark(obj, order){
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
function OnSelectAll(form, bSelect){
  var i;
  if (bSelect == 0) {
    form.cmd.value = "relAll";
  } else {
    form.cmd.value = "selAll";
  }
  form.submit();
}

// 前ページ表示
function OnPrevPage(form){
  form.cmd.value = "prev";
  form.submit();
}

// 次ページ表示
function OnNextPage(form){
  form.cmd.value = "next";
  form.submit();
}

// 最終ページ表示
function OnLastPage(form){
  form.cmd.value = "last";
  form.submit();
}

// 先頭ページ表示
function OnFirstPage(form){
  form.cmd.value = "first";
  form.submit();
}

// ソート
function OnSort(column){
  var order;
  if (mainForm.order != undefined) {
    if (column == mainForm.sort.value) {
      if (mainForm.order.value == "asc") {
        order = "desc";
      } else {
        order = "asc";
      }
    } else {
      order = "asc";
    }
    mainForm.sort.value = column;
    mainForm.order.value = order;
  } else {
    mainForm.sort.value = column;
    mainForm.order.value = "asc";
  }
  mainForm.page.value = "";
  mainForm.cmd.value = "sort";
  mainForm.submit();
}

// ソート
function OnSortOther(){
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
