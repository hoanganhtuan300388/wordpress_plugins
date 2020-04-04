function getDatatoJson() {
  var classSetting = $("#table_setting").attr("class");
  var regGroup = '';
  var regItem = '';
  var regValue = '';
  if(classSetting == 'input_table'){
    regGroup = "RegGroup";
    regItem = "RegItem";
    regValue = "RegValue";
  }else{
    regGroup = "RegGroup_noline";
    regItem = "RegItem_noline";
    regValue = "RegValue_noline";
  }
  var tr_length = $('table.'+classSetting+' tr').length;
  var tr_element = $('table.'+classSetting+' tr');
  var arrElement = [];
  if(classSetting == 'input_table_noline'){
    var object_first = {
      group : '',
      rowspan : '',
      label : '',
      value : '',
      selected : '',
      new: ''
    };
    arrElement.push(object_first);
  }

  var j = 0;
  for(i=0; i<tr_length; i++){
    var group = $.trim($(tr_element[i]).find('td.'+regGroup).text());
    if(classSetting == "input_table_noline"){
      if($.trim($(tr_element[i]).find('td.'+regGroup).next().val()) != ''){
        var rowspan = $.trim($(tr_element[i]).find('td.'+regGroup).next().val());
        i  = i + 1;
      }else{
        var rowspan = '';
      }
    }else{
      var rowspan = $.trim($(tr_element[i]).find('td.'+regGroup).attr('rowspan'));
    }
    var label = $.trim($(tr_element[i]).find('td.'+regItem).text());
    if(label.indexOf("※") != -1){
      label = label.replace("※","");
      label = label.trim()
    }
    
    var check_value = $(tr_element[i]).find('td.'+regValue+':first').children().first();
    var value = '';
    if($(check_value).is('input')) {
      var type = check_value.attr('type');
      //Format TEL
      if($.inArray( label, arrThreeInput ) !== -1 ){
        value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
      }else if($.inArray( label, arrTwoInput ) !== -1 ){

        value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
      }else if($.inArray( label, arrGetFromHidden ) !== -1 ){
        if($("input[name=FAX_TIMEZONE]:checked").val() != 4){
          value = $("input[name=FAX_TIMEZONE]:checked").next('label:first').html();
        }
        else{
          value = "深夜("+$("select[name=FAX_TIME_FROM_H] option:selected").val()+":"+$("select[name=FAX_TIME_FROM_N] option:selected").val()+"～"+$("select[name=FAX_TIME_TO_H] option:selected").val()+":"+$("select[name=FAX_TIME_TO_N] option:selected").val()+")";
        }
        
      } else if($.inArray(label, arrInputRadio) !== -1){
        value = $(tr_element[i]).find('td.'+regValue+':first input[type="radio"]:checked').val();
        if(value == undefined)
          value = $(tr_element[i]).find('td.'+regValue+':first input').val();
      }else if($.inArray(label, arrRadioGetLabel) !== -1){
        value = $(tr_element[i]).find('td.'+regValue+':first input[type="radio"]:checked').next('label:first').html();
      } else if($.inArray( label, arrDatetime ) !== -1 ){
        if( label == label_P_P_BIRTH){
          value = $(tr_element[i]).find('td.'+regValue+':first input[name="P_P_BIRTH"]').val();
        }else if( label == label_G_FOUND_DATE){
          value = $(tr_element[i]).find('td.'+regValue+':first input[name="G_FOUND_DATE"]').val();
        } else {
          value = $(tr_element[i]).find('td.'+regValue+':first input:eq(5)').val();
        }
      } else if(type == "checkbox"){
        let arr_value = [];
        $(tr_element[i]).find('td.'+regValue+':first input[type="checkbox"]:checked').map(function (key, el) {
          arr_value.push($(el).val());
        })
        value = arr_value.join("|");
      } else if(type == "radio"){
        let arr_value = [];
        $(tr_element[i]).find('td.'+regValue+':first input[type="radio"]:checked').map(function (key, el) {
          arr_value.push($(el).val());
        })
        value = arr_value.join("|");
      }
      else{
        value = $(tr_element[i]).find('td.'+regValue+':first input').val();
      }
    }else if($(check_value).is('select')){
      if($(tr_element[i]).find('td.'+regValue+':first select').val() == '')
        value = '';
      else 
        value = $(tr_element[i]).find('td.'+regValue+':first option:selected').text();
      if($.inArray( label, arrDatetime ) !== -1 ){
        if( label == label_P_P_BIRTH){
          value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
        } else if( label == label_G_FOUND_DATE){
          value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
        } else {
          value = $(tr_element[i]).find('td.'+regValue+':first input:eq(4)').val();
        }
      }
      if($.inArray( label, arrSelectandInput ) !== -1 ){
        if( label == label_M_LG_NAME){
          value = $(tr_element[i]).find('td.'+regValue+':first input:eq(0)').val() +' '+ $(tr_element[i]).find('td.'+regValue+':first option:selected').text();
        }
      }
    }else if($(check_value).is('textarea')){
      value = $(tr_element[i]).find('td.' + regValue + ':first textarea').val();
    }else if (label == label_M_CONTRACT_TYPE) {
      var contact_type = $(tr_element[i]).find('td.' + regValue + ':first input[type="hidden"]').val();
      switch (contact_type) {
        case '1':
          value = 'E-MAIL会員';
          break;
        case '2':
          value = 'FAX会員';
          break;
        case '3':
          value = '郵送会員';
          break;
        default:
          value = '不明';
          break;
      }
    }
    var selected = $(tr_element[i]).find('td:last-child.'+regValue+' option:selected').text();
    var object = {
      group : group,
      rowspan : rowspan,
      label : label,
      value : $.trim(value),
      selected : $.trim(selected),
      new: ''
    };
    arrElement.push(object);
  }

  var m_connect = $('textarea[name=M_CONNECTION]');
  if(m_connect.length){
    var object_connect = {
      group : '',
      rowspan : '',
      label : '',
      value : m_connect.val(),
      selected : '',
      new: 'add'
    };
    arrElement.push(object_connect);

  }
  $('input[name="arrElement"]').val(JSON.stringify(Object.assign({}, arrElement)));
  if(validateItem() == 0) {
    $('form#mainForm').submit();
    $('.base_button.regist_confirm_btn').attr('disabled','disabled');
    $('.preLoading').css('display', 'block');
    if (typeof(Storage) !== 'undefined') {
      var allitem = getAllItem();
      sessionStorage.setItem('allitem', JSON.stringify(allitem));
      sessionStorage.removeItem('confirm_back');
    }
  };
}
function getDatatoJsonBeforeEdit() {
  var classSetting = $("#table_setting").attr("class");
  var regGroup = '';
  var regItem = '';
  var regValue = '';
  if(classSetting == 'input_table'){
    regGroup = "RegGroup";
    regItem = "RegItem";
    regValue = "RegValue";
  }else{
    regGroup = "RegGroup_noline";
    regItem = "RegItem_noline";
    regValue = "RegValue_noline";
  }
  var tr_length = $('table.'+classSetting+' tr').length;
  var tr_element = $('table.'+classSetting+' tr');
  var arrElement = [];
  if(classSetting == 'input_table_noline'){
    var object_first = {
      group : '',
      rowspan : '',
      label : '',
      value : '',
      selected : '',
      new: ''
    };
    arrElement.push(object_first);
  }

  var j = 0;
  for(i=0; i<tr_length; i++){
    var group = $.trim($(tr_element[i]).find('td.'+regGroup).text());
    if(classSetting == "input_table_noline"){
      if($.trim($(tr_element[i]).find('td.'+regGroup).next().val()) != ''){
        var rowspan = $.trim($(tr_element[i]).find('td.'+regGroup).next().val());
        i  = i + 1;
      }else{
        var rowspan = '';
      }
    }else{
      var rowspan = $.trim($(tr_element[i]).find('td.'+regGroup).attr('rowspan'));
    }
    var label = $.trim($(tr_element[i]).find('td.'+regItem).text());
    if(label.indexOf("※") != -1){
      label = label.replace("※","");
      label = label.trim()
    }
    
    var check_value = $(tr_element[i]).find('td.'+regValue+':first').children().first();
    var value = '';
    if($(check_value).is('input')) {
      var type = check_value.attr('type');
      //Format TEL
      if($.inArray( label, arrThreeInput ) !== -1 ){
        value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
      }else if($.inArray( label, arrTwoInput ) !== -1 ){

        value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
      }else if($.inArray( label, arrGetFromHidden ) !== -1 ){
        if($("input[name=FAX_TIMEZONE]:checked").val() != 4){
          value = $("input[name=FAX_TIMEZONE]:checked").next('label:first').html();
        }
        else{
          value = "深夜("+$("select[name=FAX_TIME_FROM_H] option:selected").val()+":"+$("select[name=FAX_TIME_FROM_N] option:selected").val()+"～"+$("select[name=FAX_TIME_TO_H] option:selected").val()+":"+$("select[name=FAX_TIME_TO_N] option:selected").val()+")";
        }
        
      } else if($.inArray(label, arrInputRadio) !== -1){
        value = $(tr_element[i]).find('td.'+regValue+':first input[type="radio"]:checked').val();
        if(value == undefined)
          value = $(tr_element[i]).find('td.'+regValue+':first input').val();
      }else if($.inArray(label, arrRadioGetLabel) !== -1){
        value = $(tr_element[i]).find('td.'+regValue+':first input[type="radio"]:checked').next('label:first').html();
      } else if($.inArray( label, arrDatetime ) !== -1 ){
        if( label == label_P_P_BIRTH){
          value = $(tr_element[i]).find('td.'+regValue+':first input[name="P_P_BIRTH"]').val();
        }else if( label == label_G_FOUND_DATE){
          value = $(tr_element[i]).find('td.'+regValue+':first input[name="G_FOUND_DATE"]').val();
        } else {
          value = $(tr_element[i]).find('td.'+regValue+':first input:eq(5)').val();
        }
      }else if(type == "checkbox"){
        let arr_value = [];
        $(tr_element[i]).find('td.'+regValue+':first input[type="checkbox"]:checked').map(function (key, el) {
          arr_value.push($(el).val());
        })
        value = arr_value.join("|");
      } else if(type == "radio"){
        let arr_value = [];
        $(tr_element[i]).find('td.'+regValue+':first input[type="radio"]:checked').map(function (key, el) {
          arr_value.push($(el).val());
        })
        value = arr_value.join("|");
      }
      else{
        value = $(tr_element[i]).find('td.'+regValue+':first input').val();
      }
    }else if($(check_value).is('select')){
      if($(tr_element[i]).find('td.'+regValue+':first select').val() == '')
        value = '';
      else 
        value = $(tr_element[i]).find('td.'+regValue+':first option:selected').text();
      if($.inArray( label, arrDatetime ) !== -1 ){
        if( label == label_P_P_BIRTH){
          value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
        } else if( label == label_G_FOUND_DATE){
          value = $(tr_element[i]).find('td.'+regValue+':first input[type="hidden"]').val();
        } else {
          value = $(tr_element[i]).find('td.'+regValue+':first input:eq(4)').val();
        }
      }
      if($.inArray( label, arrSelectandInput ) !== -1 ){
        if( label == label_M_LG_NAME){
          value = $(tr_element[i]).find('td.'+regValue+':first input:eq(0)').val() +' '+ $(tr_element[i]).find('td.'+regValue+':first option:selected').text();
        }
      }
    }else if($(check_value).is('textarea')){
      value = $(tr_element[i]).find('td.'+regValue+':first textarea').val();
    }else if (label == label_M_CONTRACT_TYPE) {
      var contact_type = $(tr_element[i]).find('td.' + regValue + ':first input[type="hidden"]').val();
      switch (contact_type) {
        case '1':
          value = 'E-MAIL会員';
          break;
        case '2':
          value = 'FAX会員';
          break;
        case '3':
          value = '郵送会員';
          break;
        default:
          value = '不明';
          break;
      }
    }
    var selected = $(tr_element[i]).find('td:last-child.'+regValue+' option:selected').text();
    var object = {
      group : group,
      rowspan : rowspan,
      label : label,
      value : $.trim(value),
      selected : $.trim(selected),
      new: ''
    };
    arrElement.push(object);
  }

  var m_connect = $('textarea[name=M_CONNECTION]');
  if(m_connect.length){
    var object_connect = {
      group : '',
      rowspan : '',
      label : '',
      value : m_connect.val(),
      selected : '',
      new: 'add'
    };
    arrElement.push(object_connect);

  }
  $('input[name="arrElementBefore"]').val(JSON.stringify(Object.assign({}, arrElement)));
}
jQuery(document).ready(function(){
  getDatatoJsonBeforeEdit();
});
