jQuery(document).ready(function($) {

  $("#research_meta_group_id").change(function(){
    $("#research_meta_p_id").val("public_"+$(this).val());
  });
  
  var flg_tab_second = 0;
  var flg_tab_third = 0;
  var flg_tab_four = 0;
  $('#content_option').on('click', '.type_research ul#tab_research > li span', function(e) {
    var className = $(this).attr("class");
    $("#content_option ul#tab_research > li").removeClass("ui-tabs-active");
    $(this).parent().addClass("ui-tabs-active");
    $(".content-in").css("display", "none");
    $(".content-out-"+className).removeAttr("style");
    if(className != "first"){
      if(className == "second"){
        flg_tab_second++;
        if(flg_tab_second <=1){
          call_ajax_load_component(className)
        }
      }
      if(className == "third"){
        flg_tab_third++;
        if(flg_tab_third <=1){
          call_ajax_load_component(className)
        }
      }
      if(className == "four"){
        flg_tab_four++;
        if(flg_tab_four <=1){
          call_ajax_load_component(className)
        }
      }

    }
  });
  function call_ajax_load_component(className){
    $(".content-out-"+className).html('');
    $("#img_loading_tab").show();
    $.ajax({
      url: '../wp-admin/admin-ajax.php',
      type : "POST",
      data: {
        action: 'research_include_file',
        tab: className,
        postid: $("input[name=postID]").val(),
        tg_id: $("input[name=top_g_id]").val()
      },
      success: function(response) {
        $(".content-out-"+className).html(response);
        $("#img_loading_tab").hide();
      },
      error: function() {}
    });
  }
  var tg_id = $("input[name=top_g_id]").val();
  if(tg_id == ''){
    $("span.second").css("pointer-events","none")
    $("span.third").css("pointer-events","none")
    $("span.four").css("pointer-events","none")
  } else {
    $("span.second").css("pointer-events","auto")
    $("span.third").css("pointer-events","auto")
    $("span.four").css("pointer-events","auto")
  }
  $('#content_option').on('change', 'input#top_g_id', function(e) {
    flg_tab_second = flg_tab_third = flg_tab_four = 0;
    var tg_id = $("input[name=top_g_id]").val();
    var post_id = $("input[name=postID]").val();
    $("#table_setting_display").css("display","none");
    $(".jp-multiselect").css("display","none");
    $("table.setting-sort").css("display","none");
    $("#pre_loading").css("display","block");
    if(tg_id == ''){
      $("span.second").css("pointer-events","none")
      $("span.third").css("pointer-events","none")
      $("span.four").css("pointer-events","none")
    } else {
      $("span.second").css("pointer-events","auto")
      $("span.third").css("pointer-events","auto")
      $("span.four").css("pointer-events","auto")
    }
  });

  $("#content_option ul#tab_research > li span").click(function(e){
      var className = $(this).attr("class");
      $("#content_option ul#tab_research > li").removeClass("ui-tabs-active");
      $(this).parent().addClass("ui-tabs-active");
      $(".content-in").css("display", "none");
      $(".content-out-"+className).removeAttr("style");
  });
  $(".type_post").click(function(){
    var name = $(this).val();
    if(name){
      $(".type_member").css("display", "none");
      $("."+name).removeAttr("style");
    }
  });
  $('select.choise_codition').each(function(index, el) {
    $(this).change(function(event) {
      var choise = $(this).val();
      var parent = $(this).parents('tr');
      if(choise == '3'){
        parent.find('td.select_range').show();
      }else{
        parent.find('td.select_range').hide();
      }
    });
      var choise = $(this).val();
      var parent = $(this).parents('tr');
      if(choise == '3'){
        parent.find('td.select_range').show();
      }else{
        parent.find('td.select_range').hide();
      }
  });

  $('form#mainForm input#submit').attr('type', 'button').attr('onclick','javascript: inputReg();');
  $('select#type_setting_member').on('change', function(e) {
    $('#img_loading_content').show();
    $.ajax({
      url: '../wp-admin/admin-ajax.php',
      type : "POST",
      data: {
        action: 'member_showElement',
        type_setting : $(this).val(),
      },
      success: function(response) {
        $('#img_loading_content').hide();
        if(response == 0)
          $("#content_option").html('');
        else
          $("#content_option").html(response);
        var tg_id = $("input[name=top_g_id]").val();
        if(tg_id == ''){
          $("span.second").css("pointer-events","none")
          $("span.third").css("pointer-events","none")
          $("span.four").css("pointer-events","none")
        } else {
          $("span.second").css("pointer-events","auto")
          $("span.third").css("pointer-events","auto")
          $("span.four").css("pointer-events","auto")
        }
      }
    });
  });
});

function inputReg(){
  if(!inputChk()) return;
  jQuery('form#mainForm input#submit').attr('type', 'submit').attr('onclick','').click();
}


function inputChk(){
  var form = document.mainForm;
  var buf;
  var val = jQuery('input.setting_where_value');
  var val2 = jQuery('input.setting_where_value_2');
  var inc = jQuery('select.choise_codition');
  var sel = jQuery('select.choise_item');

  var tempArray = new Array(0, 3, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0);

  for(i = 0; i < 6; i++){

    buf = val[i].value.replace(" ", ""); while(buf.indexOf(" ") >= 0){ buf = buf.replace(" ", ""); }
    buf = buf.replace("　", ""); while(buf.indexOf("　") >= 0){ buf = buf.replace("　", ""); }

    if(sel[i].value !== ''){
      switch(inc[i].value){
      case "1":
      case "0":
      case "2":
      case "6":
        if(buf == "") {
          alert('検索条件を入力して下さい。');
          val[i].focus();
          return false;
        }
        break;
      case "3":
        if(buf == "") {

          buf = val2[i].value.replace(" ", ""); while(buf.indexOf(" ") >= 0){ buf = buf.replace(" ", ""); }
          buf = buf.replace("　", ""); while(buf.indexOf("　") >= 0){ buf = buf.replace("　", ""); }
          if(buf == "") {
            alert('検索条件を入力して下さい。');
            val[i].focus();
            return false;
          }
        }
        break;
      }


      if(inc[i].value == "3" || (sel[i].value.charAt(0) == "d" && inc[i].value == "2")){

        if(sel[i].options[sel[i].selectedIndex].value.charAt(0) == "d"){

          if(val[i].value != ""){

            buf = val[i].value.replace("　", "");
            buf = buf.replace(" ", "");


            if(IsNarrowNum(buf, sel[i].options[sel[i].selectedIndex].text + "の検索時")){
              val[i].select(); val[i].focus();
              return false;
            }

            if(val[i].value.length != 8){
              alert(sel[i].options[sel[i].selectedIndex].text + "の検索時には８桁の数値（ＹＹＹＹＭＭＤＤ）を入力して下さい。");
              val[i].select(); val[i].focus();
              return false;
            }

            if(val[i].value.substr(4, 2) < 1 || val[i].value.substr(4, 2) > 12){
              alert("月の指定の部分は01～12を入力して下さい。");
              val[i].select(); val[i].focus();
              return false;
            }

            if(val[i].value.substr(6, 2) < 1 || val[i].value.substr(6, 2) > (31 - tempArray[val[i].value.substr(4, 2) - 1])){
              alert("日の指定の部分は01～" + 31 - tempArray[val[i].value.substr(4, 2) - 1] + "を入力して下さい。");
              val[i].select(); val[i].focus();
              return false;
            }
          }


          if(val2[i].value != ""){

            if(IsNarrowNum(val2[i].value, sel[i].options[sel[i].selectedIndex].text + "の検索時")){
              val2[i].select(); val2[i].focus();
              return false;
            }

            if(val2[i].value.length != 8 && val2[i].value.length != 0){
              alert(sel[i].options[sel[i].selectedIndex].text + "の検索時には８桁の数値（ＹＹＹＹＭＭＤＤ）を入力して下さい。");
              val2[i].select(); val2[i].focus();
              return false;
            }

            if(val2[i].value.substr(4, 2) < 1 || val2[i].value.substr(4, 2) > 12){
              alert("月の指定の部分は01～12を入力して下さい。");
              val2[i].select(); val2[i].focus();
              return false;
            }

            if(val2[i].value.substr(6, 2) < 1 || val2[i].value.substr(6, 2) > (31 - tempArray[val2[i].value.substr(4, 2) - 1])){
              alert("日の指定の部分は01～" + (31 - tempArray[val2[i].value.substr(4, 2) - 1]) + "を入力して下さい。");
              val2[i].select(); val2[i].focus();
              return false;
            }
          }
        }
      }


      if(sel[i].options[sel[i].selectedIndex].value.charAt(0) == "t" || sel[i].options[sel[i].selectedIndex].value.charAt(0) == "n"){

        switch(inc[i].value){
        case "1":
        case "0":
        case "2":
          buf = val[i].value.replace(" ", ""); while(buf.indexOf(" ") >= 0){ buf = buf.replace(" ", ""); }
          buf = buf.replace("　", ""); while(buf.indexOf("　") >= 0){ buf = buf.replace("　", ""); }
          break;
        default:
          buf = val[i].value;
          break;
        }
        buf = Trim(buf);

        if(IsNarrowNum(buf, sel[i].options[sel[i].selectedIndex].text + "の検索時")){
          val[i].select(); val[i].focus();
          return false;
        }


        switch(inc[i].value){
        case "1":
        case "0":
        case "2":
          buf = val2[i].value.replace(" ", ""); while(buf.indexOf(" ") >= 0){ buf = buf.replace(" ", ""); }
          buf = buf.replace("　", ""); while(buf.indexOf("　") >= 0){ buf = buf.replace("　", ""); }
          break;
        default:
          buf = val2[i].value;
          break;
        }
        buf = Trim(buf);
        if(IsNarrowNum(buf, sel[i].options[sel[i].selectedIndex].text + "の検索時")){
          val2[i].select(); val2[i].focus();
          return false;
        }
      }
    }
  }

  return true;
}


function IsNarrowNum(tempStr, errorMsg){
  var re = new RegExp("[^0123456789]", "i");

  if(tempStr.search(re) != -1){
    alert(errorMsg + "には\n半角数字\n以外の文字は使わないで下さい。");
    return(-1);
  }

  return(0);
}

function onClear(){
  var form = document.mainForm;

  with(form){
    cond[0].value = 1;
    for(var i = 1; i < cond.length; i++){
      cond[i].selectedIndex = 0;
    }
    for(i = 0; i < sel.length; i++){
      sel[i].selectedIndex = 0;
    }
    for(i = 0; i < inc.length; i++){
      inc[i].selectedIndex = 0;
    }
    for(i = 0; i < val.length; i++){
      val[i].value = "";
    }
    for(i = 0; i < val2.length; i++){
      val2[i].value = "";
    }
    for(i = 0; i < area.length; i++){
      area[i].style.visibility = "hidden";
    }
  }
}
function OnSelChange(idx_no){
  var form = document.mainForm;

  var elem_inc = jQuery('select.choise_codition');
  var elem_sel = jQuery('select.choise_item');
  var elem_cond = jQuery('select.add_where');
  if(elem_sel[idx_no].selectedIndex != 0){

    if(elem_inc[idx_no].selectedIndex == 0) elem_inc[idx_no].selectedIndex = 1;

    if(idx_no != 0 && elem_cond[idx_no - 1].selectedIndex == 0) elem_cond[idx_no - 1].selectedIndex = 1;
  }

}
