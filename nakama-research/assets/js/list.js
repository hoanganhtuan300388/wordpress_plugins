// �\�[�g�}�[�N�i�����j�\��
// ����:[in] ���X�g�w�b�_
//     :[in] �\�[�g��
function AddSortMark(obj, order){
  var i;

  if (obj == undefined) {
    return;
  }
  if (order == "asc") {
    obj.innerHTML += "��";
  } else {
    obj.innerHTML += "��";
  }
}

// �S�I��
function OnSelectAll(form, bSelect){
  var i;
  if (bSelect == 0) {
    form.cmd.value = "relAll";
  } else {
    form.cmd.value = "selAll";
  }
  form.submit();
}

// �O�y�[�W�\��
function OnPrevPage(form){
  form.cmd.value = "prev";
  form.submit();
}

// ���y�[�W�\��
function OnNextPage(form){
  form.cmd.value = "next";
  form.submit();
}

// �ŏI�y�[�W�\��
function OnLastPage(form){
  form.cmd.value = "last";
  form.submit();
}

// �擪�y�[�W�\��
function OnFirstPage(form){
  form.cmd.value = "first";
  form.submit();
}

// �\�[�g
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

// �\�[�g
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
