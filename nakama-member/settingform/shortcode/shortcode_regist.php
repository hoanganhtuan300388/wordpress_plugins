
<?php
  define('__ROOT__', dirname(dirname(dirname(__FILE__)))); 
  require_once(__ROOT__.'/config/constant.php'); 
  require_once(__ROOT__.'/controller/memberController.php');
  $members = new memberController();
  $header_file = $dataSetting['disp_header_file_reg'][0];
  $file_ini = $dataSetting['disp_entry_file'][0];
  $disp_FeeStatus = $dataSetting['entry_setting5'][0];
  $class = array();
  $class['table'] = "input_table";
  $class['tdHead'] = "RegField";
  $class['tdOne'] = "RegGroup";
  $class['tdSecond'] = "RegItem_add";
  $class['tdThird'] = "RegValue";
  $class['tdFour'] = "RegItem";
  $bodyArr = array();
  if($_POST){
    $_SESSION['flg_shortcode'] = true;
    $_SESSION['post_id'] = $args['id'];
    if($_FILES['G_IMG']){
      $_POST['G_IMG'] = $_POST['G_G_ID'].$_FILES['G_IMG']['name'];
      if($_FILES['G_IMG']['name'] == '')
        $_POST['G_IMG'] = $_POST['m_curImgG'];
      if($_POST['m_delImgG'] == 1)
        $_POST['G_IMG'] = '';
    }
    
    if($_FILES['G_LOGO']){
      $_POST['G_LOGO'] = $_POST['G_G_ID'].$_FILES['G_LOGO']['name'];
      if($_FILES['G_LOGO']['name'] == '')
        $_POST['G_LOGO'] = $_POST['m_curLogoG'];
      if($_POST['m_delLogoG'] == 1)
        $_POST['G_LOGO'] = '';
    }
    if($_FILES['P_C_IMG']){
      $_POST['P_C_IMG'] = $_POST['G_G_ID'].$_FILES['P_C_IMG']['name'];
      if($_FILES['P_C_IMG']['name'] == '')
        $_POST['P_C_IMG'] = $_POST['m_curImgP'];
      if($_POST['m_delImgP'] == 1)
        $_POST['P_C_IMG'] = '';
    }

    if($_FILES['P_C_IMG2']){
      $_POST['P_C_IMG2'] = $_POST['G_G_ID'].$_FILES['P_C_IMG2']['name'];
      if($_FILES['P_C_IMG2']['name'] == '')
        $_POST['P_C_IMG2'] = $_POST['m_curImgP2'];
      if($_POST['m_delImgP2'] == 1)
        $_POST['P_C_IMG2'] = '';
    }

    if($_FILES['P_C_IMG3']){
      $_POST['P_C_IMG3'] = $_POST['G_G_ID'].$_FILES['P_C_IMG3']['name'];
      if($_FILES['P_C_IMG3']['name'] == '')
        $_POST['P_C_IMG3'] = $_POST['m_curImgP3'];
      if($_POST['m_delImgP3'] == 1)
        $_POST['P_C_IMG3'] = '';
    }
 
    $_SESSION['arrRegist'] = $_POST;
    uploadFile($_POST['G_G_ID']);
    ?>
    <script>window.location = "<?php echo get_permalink(get_page_by_path('nakama-member-confirm')->ID); ?>"; </script>
    <?php
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo get_the_title($args['id']); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/smart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/regist.css">
    <script type="text/javascript">
    	function OnZipCode(name1, name2, post_id, page_link){
	         var flag = 0;
	         if(name1 == "P_C_POST_u"){
	            flag = 1;
	         }else if(name1 == "G_POST_u"){
	            flag = 2;
	         }else if(name1 == "P_P_POST_u"){
	            flag = 3;
	         }else if(name1 == "M_CL_C_POST_u"){
	            flag = 4;
	         }else if(name1 == "M_CO_C_POST_u"){
	            flag = 5;
	         }
	         var zip1 = document.getElementsByName(name1);
	         zip1 = zip1[0].value;
	         var zip2 = document.getElementsByName(name2);
	         zip2 = zip2[0].value;
	         var buf = document.URL.split("/");
	         var path;
	         if(zip1.length != 3){
	            alert("郵便番号は３桁以上を入力して下さい。");
	            return false;
	         }
	         buf = page_link+'zipcode=' + zip1 + zip2+'&flag='+flag+'&post_id='+post_id;
	         gToolWnd = open(buf,
	            'DetailWnd',
	            'width=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
	      }
		function gdCheckInputData(bChange){
	        var form = document.mainForm;


	        if(bChange == false){
	          if(form.G_G_ID.type != "hidden"){
	            if(IsNull(form.G_G_ID.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織ID')), '組織ID');?>")){
	              return errProc(form.G_G_ID);
	            }
	            if(IsLength(form.G_G_ID.value, 4, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織ID')), '組織ID');?>")){
	              return errProc(form.G_G_ID);
	            }
	            if(IsNarrow(form.G_G_ID.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織ID')), '組織ID');?>")){
	              return errProc(form.G_G_ID);
	            }
	          }
	        }


	        if(form.G_NAME != undefined && form.G_NAME.type != "hidden"){
	          if(IsLengthB(form.G_NAME.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会社名')), '会社名');?>")){
	            return errProc(form.G_NAME);
	          }
	        }

	        if(form.G_NAME_KN != undefined && form.G_NAME_KN.type != "hidden"){
	          if(IsLengthB(form.G_NAME_KN.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織フリガナ')), '組織フリガナ');?>")){
	            return errProc(form.G_NAME_KN);
	          }
	        }


	        if(form.G_INDUSTRY_CD != undefined && form.G_INDUSTRY_CD.type != "hidden"){
	          if(IsLength(form.G_INDUSTRY_CD.value, 0, 6, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('業種コード')), '業種コード');?>")){
	            return errProc(form.G_INDUSTRY_CD);
	          }
	          if(IsNarrow(form.G_INDUSTRY_CD.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('業種コード')), '業種コード');?>")){
	            return errProc(form.G_INDUSTRY_CD);
	          }
	        }

	        if(form.G_INDUSTRY_NM != undefined && form.G_INDUSTRY_NM.type != "hidden"){
	          if(IsLengthB(form.G_INDUSTRY_NM.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('業種')), '業種');?>")){
	            return errProc(form.G_INDUSTRY_NM);
	          }
	        }

	        if(form.G_URL != undefined && form.G_URL.type != "hidden"){
	          if(IsLength(form.G_URL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織URL')), '組織URL');?>")){
	           return errProc(form.G_URL);
	          }
	          if(IsNarrowPlus(form.G_URL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織URL')), '組織URL');?>")){
	           return errProc(form.G_URL);
	          }
	        }


	        if(form.G_P_URL != undefined && form.G_P_URL != undefined){
	          if(form.G_P_URL.type != "hidden"){
	            if(IsLength(form.G_P_URL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織携帯URL')), '組織携帯URL');?>")){
	              return errProc(form.G_P_URL);
	            }
	            if(IsNarrowPlus(form.G_P_URL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織携帯URL')), '組織携帯URL');?>")){
	              return errProc(form.G_P_URL);
	            }
	          }
	        }


	        if(form.G_EMAIL.type != "hidden"){
	          if(IsLength(form.G_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織E-MAIL')), '組織E-MAIL');?>")){
	            return errProc(form.G_EMAIL);
	          }
	          if(IsNarrowPlus3(form.G_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織E-MAIL')), '組織E-MAIL');?>")){
	            return errProc(form.G_EMAIL);
	          }
	          if(isMail(form.G_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織E-MAIL')), '組織E-MAIL');?>")){
	            return errProc(form.G_EMAIL);
	          }
	        }

	        if(form.G_EMAIL2 != undefined){
	          if(form.G_EMAIL2.type != "hidden"){
	            if(IsLength(form.G_EMAIL2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織E-MAIL再入力')), '組織E-MAIL再入力');?>")){
	              return errProc(form.G_EMAIL2);
	            }
	            if(IsNarrowPlus3(form.G_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織E-MAIL再入力')), '組織E-MAIL再入力');?>")){
	              return errProc(form.G_EMAIL2);
	            }
	            if(isMail(form.G_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織E-MAIL再入力')), '組織E-MAIL再入力');?>")){
	              return errProc(form.G_EMAIL2);
	            }
	            if(form.G_EMAIL.value != form.G_EMAIL2.value){
	              alert("組織E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
	              form.G_EMAIL2.value = "";
	              form.G_EMAIL2.focus();
	              return false;
	            }
	          }
	        }

	        if(form.G_CC_EMAIL != undefined){
	          if(form.G_CC_EMAIL.type != "hidden"){
	            if(IsLength(form.G_CC_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織追加送信先E-MAIL')), '組織追加送信先E-MAIL');?>")){
	              return errProc(form.G_CC_EMAIL);
	            }
	            if(IsNarrowPlus3(form.G_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織追加送信先E-MAIL')), '組織追加送信先E-MAIL');?>")){
	              return errProc(form.G_CC_EMAIL);
	            }
	            if(isMail(form.G_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織追加送信先E-MAIL')), '組織追加送信先E-MAIL');?>")){
	              return errProc(form.G_CC_EMAIL);
	            }
	          }
	        }

	        if(form.G_TEL_1 != undefined || form.G_TEL_2 != undefined || form.G_TEL_3 != undefined){
	          if(form.G_TEL_1.value != "" || form.G_TEL_2.value != "" || form.G_TEL_3.value != ""){
	            if(form.G_TEL_1.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.G_TEL_1);
	            }
	            if(form.G_TEL_2.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.G_TEL_2);
	            }
	            if(form.G_TEL_3.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.G_TEL_3);
	            }
	            if(IsNarrowTelNum(form.G_TEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>")){
	              return errProc(form.G_TEL_1);
	            }
	            if(IsNarrowTelNum(form.G_TEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>")){
	              return errProc(form.G_TEL_2);
	            }
	            if(IsNarrowTelNum(form.G_TEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>")){
	              return errProc(form.G_TEL_3);
	            }
	            form.G_TEL.value = form.G_TEL_1.value + "-" + form.G_TEL_2.value + "-" + form.G_TEL_3.value;
	            if(form.G_TEL.type != "hidden"){
	              if(IsLength(form.G_TEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織TEL')), '組織TEL');?>")){
	                form.G_TEL_1.focus();
	                return false;
	              }
	            }
	          } else {
	            form.G_TEL.value = "";
	          }
	        }
	        
	        if(form.G_FAX_1 != undefined || form.G_FAX_2 != undefined || form.G_FAX_3 != undefined){
	          if(form.G_FAX_1.value != "" || form.G_FAX_2.value != "" || form.G_FAX_3.value != ""){
	            if(form.G_FAX_1.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.G_FAX_1);
	            }
	            if(form.G_FAX_2.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.G_FAX_2);
	            }
	            if(form.G_FAX_3.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.G_FAX_3);
	            }
	            if(IsNarrowTelNum(form.G_FAX_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>")){
	              return errProc(form.G_FAX_1);
	            }
	            if(IsNarrowTelNum(form.G_FAX_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>")){
	              return errProc(form.G_FAX_2);
	            }
	            if(IsNarrowTelNum(form.G_FAX_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>")){
	              return errProc(form.G_FAX_3);
	            }
	            form.G_FAX.value = form.G_FAX_1.value + "-" + form.G_FAX_2.value + "-" + form.G_FAX_3.value;
	            if(form.G_FAX.type != "hidden"){
	              if(IsLength(form.G_FAX.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織FAX')), '組織FAX');?>")){
	                form.G_FAX_1.value = form.G_FAX_2.value = form.G_FAX_3.value = ""
	                form.G_FAX_1.focus();
	                return false;
	              }
	            }
	          } else {
	            form.G_FAX.value = "";
	          }
	        }
	        

	        if((form.G_FOUND_YEAR.value != "") || (form.G_FOUND_MONTH.value != "") || (form.G_FOUND_DAY.value != "")){
	          if(IsDateImp(form.m_foundImperialG.value, form.G_FOUND_YEAR, form.G_FOUND_MONTH, form.G_FOUND_DAY, "設立年月日")){
	            return false;
	          }
	          form.G_FOUND_DATE.value = MakeYMD(form.m_foundImperialG.value, form.G_FOUND_YEAR.value, form.G_FOUND_MONTH.value, form.G_FOUND_DAY.value);
	        } else {
	          form.G_FOUND_DATE.value = "";
	        }
	        if(form.G_CAPITAL != undefined){
	          if(form.G_CAPITAL.type != "hidden"){
	            if(IsLength(form.G_CAPITAL.value, 0, 13, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('資本金')), '資本金');?>")){
	              return errProc(form.G_CAPITAL);
	            }
	            if(IsNarrowNum(form.G_CAPITAL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('資本金')), '資本金');?>")){
	              return errProc(form.G_CAPITAL);
	            }
	          }
	        }
	        

	        if(form.G_REPRESENTATIVE_NM.type != "hidden"){
	          if(IsLengthB(form.G_REPRESENTATIVE_NM.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('代表者')), '代表者');?>")){
	            return errProc(form.G_REPRESENTATIVE_NM);
	          }
	        }

	        if(form.G_REPRESENTATIVE_KN.type != "hidden"){
	          if(IsLengthB(form.G_REPRESENTATIVE_KN.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('代表者フリガナ')), '代表者フリガナ');?>")){
	            return errProc(form.G_REPRESENTATIVE_KN);
	          }
	        }
	        if(form.G_POST_u != undefined || form.G_POST_l != undefined){
	          if(form.G_POST_u.value != "" || form.G_POST_l.value != ""){
	            if(form.G_POST_u.type != "hidden"){
	              if(form.G_POST_u.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織〒')), '組織〒');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.G_POST_u);
	              }
	              if(IsLength(form.G_POST_u.value, 3, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織〒')), '組織〒');?>(上３桁)")){
	                return errProc(form.G_POST_u);
	              }
	              if(IsNarrowNum(form.G_POST_u.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織〒')), '組織〒');?>")){
	                return errProc(form.G_POST_u);
	              }
	            }
	            if(form.G_POST_l.type != "hidden"){
	              if(form.G_POST_l.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織〒')), '組織〒');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.G_POST_l);
	              }
	              if(IsLength(form.G_POST_l.value, 4, 4, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織〒')), '組織〒');?>(下４桁)")){
	                return errProc(form.G_POST_l);
	              }
	              if(IsNarrowNum(form.G_POST_l.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織〒')), '組織〒');?>")){
	                return errProc(form.G_POST_l);
	              }
	              form.G_POST.value = form.G_POST_u.value + "-" + form.G_POST_l.value;
	            }
	          } else {
	            form.G_POST.value = "";
	          }
	        }
	        

	        if('dmshibuya' == 'jeca2'){
	          if(form.G_STA.value == ""){
	            alert("組織都道府県を指定してください。");
	            return errProc(form.G_STA);
	          }
	        }
	        if(form.G_ADR != undefined){
	          if(form.G_ADR.type != "hidden"){
	            if(IsLengthB(form.G_ADR.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織住所１')), '組織住所１');?>")){
	              return errProc(form.G_ADR);
	            }
	          }
	        }
	        
	        if(form.G_ADR2 != undefined){
	          if(form.G_ADR2.type != "hidden"){
	            if(IsLengthB(form.G_ADR2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織住所２')), '組織住所２');?>")){
	              return errProc(form.G_ADR2);
	            }
	          }
	        }
	        
	        if(form.G_ADR3 != undefined){
	          if(form.G_ADR3.type != "hidden"){
	            if(IsLengthB(form.G_ADR3.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("組織住所３")), "組織住所３"); ?>")){
	              return errProc(form.G_ADR3);
	            }
	          }
	        }
	        
	        if(form.G_APPEAL != undefined){
	          if(form.G_APPEAL.type != "hidden"){

	            if(IsLength(form.G_APPEAL.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織アピール')), '組織アピール');?>")){
	              return errProc(form.G_APPEAL);
	            }

	          }

	          if(form.G_APPEAL.value != ""){
	          form.G_O_APPEAL.value = "1";
	          }else{
	          form.G_O_APPEAL.value = "0";
	          }
	        }

	        if(form.G_IMG.value != ""){
	          form.G_O_IMG.value = "1";
	        }else{
	          form.G_O_IMG.value = "0";
	        }

	        if(form.G_LOGO.value != ""){
	          form.G_O_LOGO.value = "1";
	        }else{
	          form.G_O_LOGO.value = "0";
	        }

	        if(form.G_FREE1 == true){
	          if(form.G_FREE1.type == "text"){
	            if(IsLength(form.G_FREE1.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１')), '組織自由項目１');?>")){
	              return errProc(form.G_FREE1);
	            }
	          }
	        }
	        if(form.G_FREE2 == true){
	          if(form.G_FREE2.type == "text"){
	            if(IsLength(form.G_FREE2.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２')), '組織自由項目２');?>")){
	              return errProc(form.G_FREE2);
	            }
	          }
	        }
	        if(form.G_FREE3 == true){
	          if(form.G_FREE3.type == "text"){
	            if(IsLength(form.G_FREE3.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目３')), '組織自由項目３');?>")){
	              return errProc(form.G_FREE3);
	            }
	          }
	        }
	        if(form.G_FREE4 == true){
	          if(form.G_FREE4.type == "text"){
	            if(IsLength(form.G_FREE4.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目４')), '組織自由項目４');?>")){
	              return errProc(form.G_FREE4);
	            }
	          }
	        }
	        if(form.G_FREE5 == true){
	          if(form.G_FREE5.type == "text"){
	            if(IsLength(form.G_FREE5.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目５')), '組織自由項目５');?>")){
	              return errProc(form.G_FREE5);
	            }
	          }
	        }
	        if(form.G_FREE6 == true){
	          if(form.G_FREE6.type == "text"){
	            if(IsLength(form.G_FREE6.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目６')), '組織自由項目６');?>")){
	              return errProc(form.G_FREE6);
	            }
	          }
	        }
	        if(form.G_FREE7 == true){
	          if(form.G_FREE7.type == "text"){
	            if(IsLength(form.G_FREE7.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目７')), '組織自由項目７');?>")){
	              return errProc(form.G_FREE7);
	            }
	          }
	        }
	        if(form.G_FREE8 == true){
	          if(form.G_FREE8.type == "text"){
	            if(IsLength(form.G_FREE8.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目８')), '組織自由項目８');?>")){
	              return errProc(form.G_FREE8);
	            }
	          }
	        }
	        if(form.G_FREE9 == true){
	          if(form.G_FREE9.type == "text"){
	            if(IsLength(form.G_FREE9.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目９')), '組織自由項目９');?>")){
	              return errProc(form.G_FREE9);
	            }
	          }
	        }
	        if(form.G_FREE10 == true){
	          if(form.G_FREE10.type == "text"){
	            if(IsLength(form.G_FREE10.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１０')), '組織自由項目１０');?>")){
	              return errProc(form.G_FREE10);
	            }
	          }
	        }
	        if(form.G_FREE11 == true){
	          if(form.G_FREE11.type == "text"){
	            if(IsLength(form.G_FREE11.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１１')), '組織自由項目１１');?>")){
	              return errProc(form.G_FREE11);
	            }
	          }
	        }
	        if(form.G_FREE12 == true){
	          if(form.G_FREE12.type == "text"){
	            if(IsLength(form.G_FREE12.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１２')), '組織自由項目１２');?>")){
	              return errProc(form.G_FREE12);
	            }
	          }
	        }
	        if(form.G_FREE13 == true){
	          if(form.G_FREE13.type == "text"){
	            if(IsLength(form.G_FREE13.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１３')), '組織自由項目１３');?>")){
	              return errProc(form.G_FREE13);
	            }
	          }
	        }
	        if(form.G_FREE14 == true){
	          if(form.G_FREE14.type == "text"){
	            if(IsLength(form.G_FREE14.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１４')), '組織自由項目１４');?>")){
	              return errProc(form.G_FREE14);
	            }
	          }
	        }
	        if(form.G_FREE15 == true){
	          if(form.G_FREE15.type == "text"){
	            if(IsLength(form.G_FREE15.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１５')), '組織自由項目１５');?>")){
	              return errProc(form.G_FREE15);
	            }
	          }
	        }

	        if(form.G_FREE16 == true){
	          if(form.G_FREE16.type == "text"){
	            if(IsLength(form.G_FREE16.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１６')), '組織自由項目１６');?>")){
	              return errProc(form.G_FREE16);
	            }
	          }
	        }
	        if(form.G_FREE17 == true){
	          if(form.G_FREE17.type == "text"){
	            if(IsLength(form.G_FREE17.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１７')), '組織自由項目１７');?>")){
	              return errProc(form.G_FREE17);
	            }
	          }
	        }
	        if(form.G_FREE18 == true){
	          if(form.G_FREE18.type == "text"){
	            if(IsLength(form.G_FREE18.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１８')), '組織自由項目１８');?>")){
	              return errProc(form.G_FREE18);
	            }
	          }
	        }
	        if(form.G_FREE19 == true){
	          if(form.G_FREE19.type == "text"){
	            if(IsLength(form.G_FREE19.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目１９')), '組織自由項目１９');?>")){
	              return errProc(form.G_FREE19);
	            }
	          }
	        }
	        if(form.G_FREE20 == true){
	          if(form.G_FREE20.type == "text"){
	            if(IsLength(form.G_FREE20.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２０')), '組織自由項目２０');?>")){
	              return errProc(form.G_FREE20);
	            }
	          }
	        }
	        if(form.G_FREE21 == true){
	          if(form.G_FREE21.type == "text"){
	            if(IsLength(form.G_FREE21.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２１')), '組織自由項目２１');?>")){
	              return errProc(form.G_FREE21);
	            }
	          }
	        }
	        if(form.G_FREE22 == true){
	          if(form.G_FREE22.type == "text"){
	            if(IsLength(form.G_FREE22.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２２')), '組織自由項目２２');?>")){
	              return errProc(form.G_FREE22);
	            }
	          }
	        }
	        if(form.G_FREE23 == true){
	          if(form.G_FREE23.type == "text"){
	            if(IsLength(form.G_FREE23.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２３')), '組織自由項目２３');?>")){
	              return errProc(form.G_FREE23);
	            }
	          }
	        }
	        if(form.G_FREE24 == true){
	          if(form.G_FREE24.type == "text"){
	            if(IsLength(form.G_FREE24.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２４')), '組織自由項目２４');?>")){
	              return errProc(form.G_FREE24);
	            }
	          }
	        }
	        if(form.G_FREE25 == true){
	          if(form.G_FREE25.type == "text"){
	            if(IsLength(form.G_FREE25.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２５')), '組織自由項目２５');?>")){
	              return errProc(form.G_FREE25);
	            }
	          }
	        }
	        if(form.G_FREE26 == true){
	          if(form.G_FREE26.type == "text"){
	            if(IsLength(form.G_FREE26.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２６')), '組織自由項目２６');?>")){
	              return errProc(form.G_FREE26);
	            }
	          }
	        }
	        if(form.G_FREE27 == true){
	          if(form.G_FREE27.type == "text"){
	            if(IsLength(form.G_FREE27.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２７')), '組織自由項目２７');?>")){
	              return errProc(form.G_FREE27);
	            }
	          }
	        }
	        if(form.G_FREE28 == true){
	          if(form.G_FREE28.type == "text"){
	            if(IsLength(form.G_FREE28.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２８')), '組織自由項目２８');?>")){
	              return errProc(form.G_FREE28);
	            }
	          }
	        }
	        if(form.G_FREE29 == true){
	          if(form.G_FREE29.type == "text"){
	            if(IsLength(form.G_FREE29.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目２９')), '組織自由項目２９');?>")){
	              return errProc(form.G_FREE29);
	            }
	          }
	        }
	        if(form.G_FREE30 == true){
	          if(form.G_FREE30.type == "text"){
	            if(IsLength(form.G_FREE30.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織自由項目３０')), '組織自由項目３０');?>")){
	              return errProc(form.G_FREE30);
	            }
	          }
	        }



	        if(form.G_ADD_MARKETING16 != undefined){
	          if(form.G_ADD_MARKETING16.type != "hidden"){
	            if(IsLength(form.G_ADD_MARKETING16.value, 0, 50, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('マーケティング自由項目１６')), 'マーケティング自由項目１６');?>")){
	              return errProc(form.G_ADD_MARKETING16);
	            }
	          }
	        }
	        if(form.G_ADD_MARKETING17 != undefined){
	          if(form.G_ADD_MARKETING17.type != "hidden"){
	            if(IsLength(form.G_ADD_MARKETING17.value, 0, 50, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('マーケティング自由項目１７')), 'マーケティング自由項目１７');?>")){
	              return errProc(form.G_ADD_MARKETING17);
	            }
	          }
	        }
	        if(form.G_ADD_MARKETING18 != undefined){
	          if(form.G_ADD_MARKETING18.type != "hidden"){
	            if(IsLength(form.G_ADD_MARKETING18.value, 0, 50, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('マーケティング自由項目１８')), 'マーケティング自由項目１８');?>")){
	              return errProc(form.G_ADD_MARKETING18);
	            }
	          }
	        }
	        if(form.G_ADD_MARKETING19 != undefined){
	          if(form.G_ADD_MARKETING19.type != "hidden"){
	            if(IsLength(form.G_ADD_MARKETING19.value, 0, 50, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('マーケティング自由項目１９')), 'マーケティング自由項目１９');?>")){
	              return errProc(form.G_ADD_MARKETING19);
	            }
	          }
	        }
	        if(form.G_ADD_MARKETING20 != undefined){
	          if(form.G_ADD_MARKETING20.type != "hidden"){
	            if(IsLength(form.G_ADD_MARKETING20.value, 0, 50, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('マーケティング自由項目２０')), 'マーケティング自由項目２０');?>")){
	              return errProc(form.G_ADD_MARKETING20);
	            }
	          }
	        }
	        var groupRegCheck = true;

	        if(form.NoneRMf != undefined){
	          if(form.NoneRMf.value == "1"){
	            groupRegCheck = false;
	          }
	        }
	        if(groupRegCheck == true){
	          if(form.G_G_ID.type != "hidden"){
	          }
	        }
	        return true;
	    }
		function pdCheckInputData(bChange){
	        var form = document.mainForm;
	        var messageflg = 1;
	        var m_card_open;
	        var m_open;
	        if(form.P_O_NAME != undefined){
	          var m_open = form.P_O_NAME.value;
	        }
	        if(form.P_CARD_OPEN == false || form.P_CARD_OPEN.type == "hidden"){
	          messageflg = 0;
	        }else{
	          m_card_open = form.P_CARD_OPEN.value;
	        }
	        var change_flg="0";
	        if(bChange == true){
	          change_flg="1";
	        }

	        if(bChange == false){
	          if(IsNull(form.P_P_ID.value, "個人ID")){
	            return errProc(form.P_P_ID);
	          }
	          if(IsLength(form.P_P_ID.value, 4, 30, "個人ID")){
	            return errProc(form.P_P_ID);
	          }
	          if(IsNarrowPlus(form.P_P_ID.value, "個人ID")){
	            return errProc(form.P_P_ID);
	          }
	        }

	        if(form.P_PASSWORD != undefined && form.P_PASSWORD.type != "hidden"){
	          if(IsNull(form.P_PASSWORD.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('パスワード')), 'パスワード');?>")){
	            return errProc(form.P_PASSWORD);
	          }
	          if(IsLength(form.P_PASSWORD.value, 4, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('パスワード')), 'パスワード');?>")){
	            return errProc(form.P_PASSWORD);
	          }
	          if(IsNarrowPassword(form.P_PASSWORD.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('パスワード')), 'パスワード');?>")){
	            return errProc(form.P_PASSWORD);
	          }
	        }
	        if(form.P_PASSWORD != undefined && form.P_PASSWORD2 != undefined && form.P_PASSWORD.type != "hidden" && form.P_PASSWORD2.type != "hidden"){
	          if(form.P_PASSWORD.value != form.P_PASSWORD2.value ){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('パスワード')), 'パスワード'); ?>の内容と確認入力の内容が一致しません。\n<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('パスワードをもう一度確認')), 'パスワードをもう一度確認'); ?>して下さい");
	            form.P_PASSWORD2.value = "";
	            form.P_PASSWORD2.focus();
	            return false;
	          }
	        }

	        if(form.P_C_NAME != undefined && form.P_C_NAME.type != "hidden"){
	          if(IsNull(form.P_C_NAME.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('氏名')), '氏名');?>")){
	            return errProc(form.P_C_NAME);
	          }
	          if(IsLengthB(form.P_C_NAME.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('氏名')), '氏名');?>")){
	            return errProc(form.P_C_NAME);
	          }
	        }

	        if(form.P_C_NAME_EN != undefined && form.P_C_NAME_EN.type != "hidden"){
	          if(IsLengthB(form.P_C_NAME_EN.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('氏名　英語表記')), '氏名　英語表記');?>")){
	            return errProc(form.P_C_NAME_EN);
	          }
	        }

	        if(form.P_C_ADR_EN != undefined && form.P_C_ADR_EN.type != "hidden"){
	          if(IsLengthB(form.P_C_ADR_EN.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人住所　英語表記')), '個人住所　英語表記');?>")){
	            return errProc(form.P_C_ADR_EN);
	          }
	        }

	        if(form.P_C_KANA != undefined && form.P_C_KANA.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_KANA.value){
	            messageflg = 0;
	          }
	          if(IsNull(form.P_C_KANA.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人フリガナ')), '個人フリガナ');?>")){
	            return errProc(form.P_C_KANA);
	          }
	          if(IsLengthB(form.P_C_KANA.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人フリガナ')), '個人フリガナ');?>")){
	            return errProc(form.P_C_KANA);
	          }
	        }

	        if(form.P_C_SEX != undefined && form.P_C_SEX.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_SEX.value){
	            messageflg = 0;
	          }
	        }

	        if(form.P_C_URL != undefined && form.P_C_URL.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_URL.value){
	            messageflg = 0;
	          }
	          if(IsLength(form.P_C_URL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人URL')), '個人URL');?>")){
	            return errProc(form.P_C_URL);
	          }
	          if(IsNarrowPlus(form.P_C_URL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人URL')), '個人URL');?>")){
	            return errProc(form.P_C_URL);
	          }
	        }

	        if(form.P_C_EMAIL != undefined && form.P_C_EMAIL.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_EMAIL.value){
	            messageflg = 0;
	          }
	          if(IsLength(form.P_C_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人E-MAIL')), '個人E-MAIL');?>")){
	            return errProc(form.P_C_EMAIL);
	          }
	        }
	        if(form.P_C_EMAIL != undefined){
	          if(IsNarrowPlus3(form.P_C_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人E-MAIL')), '個人E-MAIL'); ?>")){
	            return errProc(form.P_C_EMAIL);
	          }
	          if(isMail(form.P_C_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人E-MAIL')), '個人E-MAIL'); ?>")){
	            return errProc(form.P_C_EMAIL);
	          }
	        }

	        if(form.P_C_CC_EMAIL != undefined){
	          if(form.P_C_CC_EMAIL.type != "hidden"){
	            if(messageflg == 1 && m_open != form.P_O_C_CC_EMAIL.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_CC_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人追加送信先E-MAIL')), '個人追加送信先E-MAIL');?>")){
	              return errProc(form.P_C_CC_EMAIL);
	            }
	          }
	          if(IsNarrowPlus3(form.P_C_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人追加送信先E-MAIL')), '個人追加送信先E-MAIL');?>")){
	            return errProc(form.P_C_CC_EMAIL);
	          }
	          if(isMail(form.P_C_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人追加送信先E-MAIL')), '個人追加送信先E-MAIL');?>")){
	            return errProc(form.P_C_CC_EMAIL);
	          }
	        }
	        
	        if(form.P_C_TEL != undefined && form.P_C_TEL.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_TEL.value){
	            messageflg = 0;
	          }
	        }
	        if(form.P_C_TEL_1 != undefined && form.P_C_TEL_2 != undefined && form.P_C_TEL_3 != undefined){
	          if(form.P_C_TEL_1.value != "" || form.P_C_TEL_2.value != "" || form.P_C_TEL_3.value != ""){
	            if(form.P_C_TEL_1.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_TEL_1);
	            }
	            if(form.P_C_TEL_2.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_TEL_2);
	            }
	            if(form.P_C_TEL_3.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_TEL_3);
	            }
	            if(IsNarrowTelNum(form.P_C_TEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>")){
	              return errProc(form.P_C_TEL_1);
	            }
	            if(IsNarrowTelNum(form.P_C_TEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>")){
	              return errProc(form.P_C_TEL_2);
	            }
	            if(IsNarrowTelNum(form.P_C_TEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>")){
	              return errProc(form.P_C_TEL_3);
	            }
	            form.P_C_TEL.value = form.P_C_TEL_1.value + "-" + form.P_C_TEL_2.value + "-" + form.P_C_TEL_3.value;
	            if(form.P_C_TEL.type != "hidden"){
	              if(IsLength(form.P_C_TEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人TEL')), '個人TEL');?>")){
	                return errProc(form.P_C_TEL_1);
	              }
	            }
	          } else {
	            form.P_C_TEL.value = "";
	          }
	        }
	        

	        if(form.P_C_FAX != undefined && form.P_C_FAX.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_FAX.value){
	            messageflg = 0;
	          }
	        }
	        if(form.P_C_FAX_1 != undefined && form.P_C_FAX_2 != undefined && form.P_C_FAX_3 != undefined){
	          if(form.P_C_FAX_1.value != "" || form.P_C_FAX_2.value != "" || form.P_C_FAX_3.value != ""){
	            if(form.P_C_FAX_1.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_FAX_1);
	            }
	            if(form.P_C_FAX_2.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_FAX_2);
	            }
	            if(form.P_C_FAX_3.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_FAX_3);
	            }
	            if(IsNarrowTelNum(form.P_C_FAX_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>")){
	              return errProc(form.P_C_FAX_1);
	            }
	            if(IsNarrowTelNum(form.P_C_FAX_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>")){
	              return errProc(form.P_C_FAX_2);
	            }
	            if(IsNarrowTelNum(form.P_C_FAX_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>")){
	              return errProc(form.P_C_FAX_3);
	            }
	            form.P_C_FAX.value = form.P_C_FAX_1.value + "-" + form.P_C_FAX_2.value + "-" + form.P_C_FAX_3.value;
	            if(form.P_C_FAX.type != "hidden"){
	              if(IsLength(form.P_C_FAX.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人FAX')), '個人FAX');?>")){
	                form.P_C_FAX_1.value = form.P_C_FAX_2.value = form.P_C_FAX_3.value = ""
	                form.P_C_FAX_1.focus();
	                return false;
	              }
	            }
	          } else {
	            form.P_C_FAX.value = "";
	          }
	        }
	        

	        if(form.P_C_PTEL != undefined && form.P_C_PTEL.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_PTEL.value){
	            messageflg = 0;
	          }
	        }
	        if(form.P_C_PTEL_1 != undefined && form.P_C_PTEL_2 != undefined && form.P_C_PTEL_3 != undefined){
	          if(form.P_C_PTEL_1.value != "" || form.P_C_PTEL_2.value != "" || form.P_C_PTEL_3.value != ""){
	            if(form.P_C_PTEL_1.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_PTEL_1);
	            }
	            if(form.P_C_PTEL_2.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_PTEL_2);
	            }
	            if(form.P_C_PTEL_3.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>\nを登録する場合は\n全ての欄を入力して下さい")
	              return errProc(form.P_C_PTEL_3);
	            }
	            if(IsNarrowTelNum(form.P_C_PTEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>")){
	              return errProc(form.P_C_PTEL_1);
	            }
	            if(IsNarrowTelNum(form.P_C_PTEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>")){
	              return errProc(form.P_C_PTEL_2);
	            }
	            if(IsNarrowTelNum(form.P_C_PTEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>")){
	              return errProc(form.P_C_PTEL_3);
	            }
	            form.P_C_PTEL.value = form.P_C_PTEL_1.value + "-" + form.P_C_PTEL_2.value + "-" + form.P_C_PTEL_3.value;
	            if(form.P_C_PTEL.type != "hidden"){
	              if(IsLength(form.P_C_PTEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯')), '携帯');?>")){
	                form.P_C_PTEL_1.value = form.P_C_PTEL_2.value = form.P_C_PTEL_3.value = ""
	                form.P_C_PTEL_1.focus();
	                return false;
	              }
	            }
	          } else {
	            form.P_C_PTEL.value = "";
	          }
	        }
	        

	        if(form.P_C_PMAIL != undefined && form.P_C_PMAIL.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_PMAIL.value){
	            messageflg = 0;
	          }
	          if(IsLength(form.P_C_PMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メール')), '携帯メール');?>")){
	            return errProc(form.P_C_PMAIL);
	          }
	        }
	        if(form.P_C_PMAIL != undefined){
	          if(IsNarrowPlus3(form.P_C_PMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メール')), '携帯メール');?>")){
	            return errProc(form.P_C_PMAIL);
	          }
	          if(isMail(form.P_C_PMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メール')), '携帯メール');?>")){
	            return errProc(form.P_C_PMAIL);
	          }
	        }

	        if(form.P_C_PMAIL2 != undefined && form.P_C_PMAIL2.type != "hidden"){
	          if(form.P_C_PMAIL2.type != "hidden"){
	            if(IsLength(form.P_C_PMAIL2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メール再入力')), '携帯メール再入力');?>")){
	              return errProc(form.P_C_PMAIL2);
	            }
	          }
	          if(IsNarrowPlus3(form.P_C_PMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メール再入力')), '携帯メール再入力');?>")){
	            return errProc(form.P_C_PMAIL2);
	          }
	          if(isMail(form.P_C_PMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メール再入力')), '携帯メール再入力');?>")){
	            return errProc(form.P_C_PMAIL2);
	          }
	          if(form.P_C_PMAIL.value != form.P_C_PMAIL2.value){
	            alert("携帯メールの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
	            form.P_C_PMAIL2.value = "";
	            form.P_C_PMAIL2.focus();
	            return false;
	          }
	        }

	        if(form.P_C_POST != undefined && form.P_C_POST.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_POST.value){
	            messageflg = 0;
	          }
	        }
	        if(form.P_C_POST_u != undefined || form.P_C_POST_l != undefined){
	          if(form.P_C_POST_u.value != "" || form.P_C_POST_l.value != ""){
	            if(form.P_C_POST_u.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人〒")), "個人〒"); ?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.P_C_POST_u);
	            }
	            if(IsLength(form.P_C_POST_u.value, 3, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人〒")), "個人〒"); ?>(上３桁)")){
	              return errProc(form.P_C_POST_u);
	            }
	            if(IsNarrowNum(form.P_C_POST_u.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人〒")), "個人〒"); ?>")){
	              return errProc(form.P_C_POST_u);
	            }
	            if(form.P_C_POST_l.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人〒")), "個人〒"); ?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.P_C_POST_l);
	            }
	            if(IsLength(form.P_C_POST_l.value, 4, 4, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人〒")), "個人〒"); ?>(下４桁)")){
	              return errProc(form.P_C_POST_l);
	            }
	            if(IsNarrowNum(form.P_C_POST_l.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人〒")), "個人〒"); ?>")){
	              return errProc(form.P_C_POST_l);
	            }
	            form.P_C_POST.value = form.P_C_POST_u.value + "-" + form.P_C_POST_l.value;
	          } else {
	            form.P_C_POST.value = "";
	          }
	        }
	        
	        if(form.P_C_STA != undefined && form.P_C_STA.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_STA.value){
	            messageflg = 0;
	          }
	        }

	        if(form.P_C_ADR != undefined){
	          if(form.P_C_ADR.type != "hidden"){
	            if(messageflg == 1 && m_open != form.P_O_ADDRESS.value){
	              messageflg = 0;
	            }
	          }
	          if(IsLengthB(form.P_C_ADR.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人住所１')), '個人住所１');?>")){
	            return errProc(form.P_C_ADR);
	          }

	          if(form.P_C_ADR.type != "hidden"){
	            if(messageflg == 1 && m_open != form.P_O_ADDRESS.value){
	              messageflg = 0;
	            }
	            if(IsLengthB(form.P_C_ADR2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('建物ビル名')), '建物ビル名');?>")){
	              return errProc(form.P_C_ADR2);
	            }
	            if(IsLengthB(form.P_C_ADR3.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('建物ビル名')), '建物ビル名');?>")){
	              return errProc(form.P_C_ADR3);
	            }
	          }
	        }

	        if(form.P_C_IMG != undefined && form.P_C_IMG.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_IMG.value){
	            messageflg = 0;
	          }
	        }

	        if(form.P_C_IMG2 == true){
	          if(form.P_C_IMG2.type != "hidden"){
	            if(messageflg == 1 && m_open != form.P_O_IMG2.value){
	              messageflg = 0;
	            }
	          }
	        }

	        if(form.P_C_IMG3 == true){
	          if(form.P_C_IMG3.type != "hidden"){
	            if(messageflg == 1 && m_open != form.P_O_IMG3.value){
	              messageflg = 0;
	            }
	          }
	        }

	        if(form.P_C_APPEAL.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_APPEAL.value){
	            messageflg = 0;
	          }
	          if(IsLength(form.P_C_APPEAL.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人アピール')), '個人アピール');?>")){
	            return errProc(form.P_C_APPEAL);
	          }
	        }

	        if('dmshibuya' != 'jeca2'){
	          if('dmshibuya' == ''){
	          }else{
	            if(form.P_C_FREE1 == true || form.P_C_FREE1 == "[object HTMLTextAreaElement]"){
	              if(form.P_C_FREE1.type == "text" || form.P_C_FREE1.type == "textarea"){
	                if(messageflg == 1 && m_open != form.P_O_BIKOU1.value){
	                  messageflg = 0;
	                }
	                if(IsLength(form.P_C_FREE1.value, 0, 2000, "当会を何で知りましたか？")){
	                  return errProc(form.P_C_FREE1);
	                }
	              }
	            }
	          }
	        }
	        if(form.P_C_FREE2 == true || form.P_C_FREE2 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE2.type == "text" || form.P_C_FREE2.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU2.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE2.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２')), '個人自由項目２');?>")){
	              return errProc(form.P_C_FREE2);
	            }
	          }
	        }
	        if(form.P_C_FREE3 == true || form.P_C_FREE3 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE3.type == "text" || form.P_C_FREE3.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU3.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE3.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目３')), '個人自由項目３');?>")){
	              return errProc(form.P_C_FREE3);
	            }
	          }
	        }
	        if(form.P_C_FREE4 == true || form.P_C_FREE4 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE4.type == "text" || form.P_C_FREE4.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU4.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE4.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目４')), '個人自由項目４');?>")){
	              return errProc(form.P_C_FREE4);
	            }
	          }
	        }
	        if(form.P_C_FREE5 == true || form.P_C_FREE5 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE5.type == "text" || form.P_C_FREE5.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU5.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE5.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目５')), '個人自由項目５');?>")){
	              return errProc(form.P_C_FREE5);
	            }
	          }
	        }
	        if(form.P_C_FREE6 == true || form.P_C_FREE6 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE6.type == "text" || form.P_C_FREE6.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU6.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE6.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目６')), '個人自由項目６');?>")){
	              return errProc(form.P_C_FREE6);
	            }
	          }
	        }
	        if(form.P_C_FREE7 == true || form.P_C_FREE7 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE7.type == "text" || form.P_C_FREE7.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU7.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE7.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目７')), '個人自由項目７');?>")){
	              return errProc(form.P_C_FREE7);
	            }
	          }
	        }
	        if(form.P_C_FREE8 == true || form.P_C_FREE8 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE8.type == "text" || form.P_C_FREE8.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU8.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE8.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目８')), '個人自由項目８');?>")){
	              return errProc(form.P_C_FREE8);
	            }
	          }
	        }
	        if(form.P_C_FREE9 == true || form.P_C_FREE9 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE9.type == "text" || form.P_C_FREE9.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU9.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE9.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目９')), '個人自由項目９');?>")){
	              return errProc(form.P_C_FREE9);
	            }
	          }
	        }
	        if(form.P_C_FREE10 == true || form.P_C_FREE10 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE10.type == "text" || form.P_C_FREE10.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU10.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE10.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１０')), '個人自由項目１０');?>")){
	              return errProc(form.P_C_FREE10);
	            }
	          }
	        }
	        if(form.P_C_FREE11 == true || form.P_C_FREE11 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE11.type == "text" || form.P_C_FREE11.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU11.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE11.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１１')), '個人自由項目１１');?>")){
	              return errProc(form.P_C_FREE11);
	            }
	          }
	        }
	        if(form.P_C_FREE12 == true || form.P_C_FREE12 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE12.type == "text" || form.P_C_FREE12.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU12.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE12.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１２')), '個人自由項目１２');?>")){
	              return errProc(form.P_C_FREE12);
	            }
	          }
	        }
	        if(form.P_C_FREE13 == true || form.P_C_FREE13 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE13.type == "text" || form.P_C_FREE13.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU13.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE13.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１３')), '個人自由項目１３');?>")){
	              return errProc(form.P_C_FREE13);
	            }
	          }
	        }
	        if(form.P_C_FREE14 == true || form.P_C_FREE14 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE14.type == "text" || form.P_C_FREE14.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU14.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE14.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１４')), '個人自由項目１４');?>")){
	              return errProc(form.P_C_FREE14);
	            }
	          }
	        }
	        if(form.P_C_FREE15 == true || form.P_C_FREE15 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE15.type == "text" || form.P_C_FREE15.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU15.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE15.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１５')), '個人自由項目１５');?>")){
	              return errProc(form.P_C_FREE15);
	            }
	          }
	        }
	        if(form.P_C_FREE16 == true || form.P_C_FREE16 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE16.type == "text" || form.P_C_FREE16.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU16.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE16.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１６')), '個人自由項目１６');?>")){
	              return errProc(form.P_C_FREE16);
	            }
	          }
	        }
	        if(form.P_C_FREE17 == true || form.P_C_FREE17 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE17.type == "text" || form.P_C_FREE17.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU17.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE17.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１７')), '個人自由項目１７');?>")){
	              return errProc(form.P_C_FREE17);
	            }
	          }
	        }
	        if(form.P_C_FREE18 == true || form.P_C_FREE18 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE18.type == "text" || form.P_C_FREE18.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU18.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE18.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１８')), '個人自由項目１８');?>")){
	              return errProc(form.P_C_FREE18);
	            }
	          }
	        }
	        if(form.P_C_FREE19 == true || form.P_C_FREE19 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE19.type == "text" || form.P_C_FREE19.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU19.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE19.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目１９')), '個人自由項目１９');?>")){
	              return errProc(form.P_C_FREE19);
	            }
	          }
	        }
	        if(form.P_C_FREE20 == true || form.P_C_FREE20 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE20.type == "text" || form.P_C_FREE20.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU20.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE20.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２０')), '個人自由項目２０');?>")){
	              return errProc(form.P_C_FREE20);
	            }
	          }
	        }
	        if(form.P_C_FREE21 == true || form.P_C_FREE21 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE21.type == "text" || form.P_C_FREE21.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU21.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE21.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２１')), '個人自由項目２１');?>")){
	              return errProc(form.P_C_FREE21);
	            }
	          }
	        }
	        if(form.P_C_FREE22 == true || form.P_C_FREE22 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE22.type == "text" || form.P_C_FREE22.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU22.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE22.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２２')), '個人自由項目２２');?>")){
	              return errProc(form.P_C_FREE22);
	            }
	          }
	        }
	        if(form.P_C_FREE23 == true || form.P_C_FREE23 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE23.type == "text" || form.P_C_FREE23.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU23.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE23.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２３')), '個人自由項目２３');?>")){
	              return errProc(form.P_C_FREE23);
	            }
	          }
	        }
	        if(form.P_C_FREE24 == true || form.P_C_FREE24 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE24.type == "text" || form.P_C_FREE24.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU24.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE24.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２４')), '個人自由項目２４');?>")){
	              return errProc(form.P_C_FREE24);
	            }
	          }
	        }
	        if(form.P_C_FREE25 == true || form.P_C_FREE25 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE25.type == "text" || form.P_C_FREE25.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU25.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE25.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２５')), '個人自由項目２５');?>")){
	              return errProc(form.P_C_FREE25);
	            }
	          }
	        }
	        if(form.P_C_FREE26 == true || form.P_C_FREE26 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE26.type == "text" || form.P_C_FREE26.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU26.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE26.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２６')), '個人自由項目２６');?>")){
	              return errProc(form.P_C_FREE26);
	            }
	          }
	        }
	        if(form.P_C_FREE27 == true || form.P_C_FREE27 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE27.type == "text" || form.P_C_FREE27.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU27.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE27.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２７')), '個人自由項目２７');?>")){
	              return errProc(form.P_C_FREE27);
	            }
	          }
	        }
	        if(form.P_C_FREE28 == true || form.P_C_FREE28 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE28.type == "text" || form.P_C_FREE28.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU28.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE28.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２８')), '個人自由項目２８');?>")){
	              return errProc(form.P_C_FREE28);
	            }
	          }
	        }
	        if(form.P_C_FREE29 == true || form.P_C_FREE29 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE29.type == "text" || form.P_C_FREE29.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU29.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE29.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目２９')), '個人自由項目２９');?>")){
	              return errProc(form.P_C_FREE29);
	            }
	          }
	        }
	        if(form.P_C_FREE30 == true || form.P_C_FREE30 == "[object HTMLTextAreaElement]"){
	          if(form.P_C_FREE30.type == "text" || form.P_C_FREE30.type == "textarea"){
	            if(messageflg == 1 && m_open != form.P_O_BIKOU30.value){
	              messageflg = 0;
	            }
	            if(IsLength(form.P_C_FREE30.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('個人自由項目３０')), '個人自由項目３０');?>")){
	              return errProc(form.P_C_FREE30);
	            }
	          }
	        }

	        if(form.P_G_ID != undefined){
	          if(form.P_G_ID.type != "hidden"){
	            if(messageflg == 1 && m_open != form.P_O_G_ID.value){
	              messageflg = 0;
	            }
	          }
	        }

	        if(form.S_AFFILIATION_NAME.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_AFFILIATION.value){
	            messageflg = 0;
	          }
	        }

	        if(form.S_OFFICIAL_POSITION.type != "hidden"){
	          if(messageflg == 1 && m_open != form.P_O_OFFICIAL.value){
	            messageflg = 0;
	          }
	        }

	        if(form.P_HANDLE_NM != undefined){
	          if(form.P_HANDLE_NM.type != "hidden"){
	            if(IsLengthB(form.P_HANDLE_NM.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会議室ニックネーム')), '会議室ニックネーム');?>")){
	              return errProc(form.P_HANDLE_NM);
	            }
	          }
	        }

	        if(form.P_MEETING_NM_MK != undefined){
	          if(form.P_MEETING_NM_MK.type != "hidden"){
	            if(IsLengthB(form.P_MEETING_NM_MK.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会議室公開ネーム表示マーク')), '会議室公開ネーム表示マーク');?>")){
	              return errProc(form.P_MEETING_NM_MK);
	            }
	          }
	        }


	        if(form.P_P_URL.type != "hidden"){
	          if(IsLength(form.P_P_URL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('URL')), 'URL');?>")){
	            return errProc(form.P_P_URL);
	          }
	          if(IsNarrowPlus(form.P_P_URL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('URL')), 'URL');?>")){
	            return errProc(form.P_P_URL);
	          }
	        }

	        if(form.P_P_EMAIL.type != "hidden"){
	          if(IsLength(form.P_P_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('E-MAIL')), 'E-MAIL');?>")){
	            return errProc(form.P_P_EMAIL);
	          }
	          if(IsNarrowPlus3(form.P_P_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('E-MAIL')), 'E-MAIL');?>")){
	            return errProc(form.P_P_EMAIL);
	          }
	          if(isMail(form.P_P_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('E-MAIL')), 'E-MAIL');?>")){
	            return errProc(form.P_P_EMAIL);
	          }
	        }

	        if(form.P_P_EMAIL2 != undefined){
	          if(form.P_P_EMAIL2.type != "hidden"){
	            if(IsLength(form.P_P_EMAIL2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('プライベートE-MAIL再入力')), 'プライベートE-MAIL再入力');?>")){
	              return errProc(form.P_P_EMAIL2);
	            }
	            
	            if(IsNarrowPlus3(form.P_P_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('プライベートE-MAIL再入力')), 'プライベートE-MAIL再入力');?>")){
	              return errProc(form.P_P_EMAIL2);
	            }
	            if(isMail(form.P_P_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('プライベートE-MAIL再入力')), 'プライベートE-MAIL再入力');?>")){
	              return errProc(form.P_P_EMAIL2);
	            }
	            if(form.P_P_EMAIL.value != form.P_P_EMAIL2.value){
	              alert("プライベートE-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
	              form.P_P_EMAIL2.value = "";
	              form.P_P_EMAIL2.focus();
	              return false;
	            }
	          }
	        }

	        if(form.P_P_CC_EMAIL != undefined){
	          if(form.P_P_CC_EMAIL.type != "hidden"){
	            if(IsLength(form.P_P_CC_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('追加送信先E-MAIL')), '追加送信先E-MAIL');?>")){
	              return errProc(form.P_P_CC_EMAIL);
	            }
	            if(IsNarrowPlus3(form.P_P_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('追加送信先E-MAIL')), '追加送信先E-MAIL');?>")){
	              return errProc(form.P_P_CC_EMAIL);
	            }
	            if(isMail(form.P_P_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('追加送信先E-MAIL')), '追加送信先E-MAIL');?>")){
	              return errProc(form.P_P_CC_EMAIL);
	            }
	          }
	        }

	        if(form.P_P_TEL_1.value != "" || form.P_P_TEL_2.value != "" || form.P_P_TEL_3.value != ""){
	          if(form.P_P_TEL_1.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_TEL_1);
	          }
	          if(form.P_P_TEL_2.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_TEL_2);
	          }
	          if(form.P_P_TEL_3.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_TEL_3);
	          }
	          if(IsNarrowTelNum(form.P_P_TEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>")){
	            return errProc(form.P_P_TEL_1);
	          }
	          if(IsNarrowTelNum(form.P_P_TEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>")){
	            return errProc(form.P_P_TEL_2);
	          }
	          if(IsNarrowTelNum(form.P_P_TEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>")){
	            return errProc(form.P_P_TEL_3);
	          }
	          form.P_P_TEL.value = form.P_P_TEL_1.value + "-" + form.P_P_TEL_2.value + "-" + form.P_P_TEL_3.value;

	          if(form.P_P_TEL.type != "hidden"){
	            if(IsLength(form.P_P_TEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('電話番号')), '電話番号');?>")){
	              return errProc(form.P_P_TEL_1);
	            }
	          }
	        } else {
	          form.P_P_TEL.value = "";
	        }


	        if(form.P_P_FAX_1.value != "" || form.P_P_FAX_2.value != "" || form.P_P_FAX_3.value != ""){
	          if(form.P_P_FAX_1.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_FAX_1);
	          }
	          if(form.P_P_FAX_2.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_FAX_2);
	          }
	          if(form.P_P_FAX_3.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_FAX_3);
	          }
	          if(IsNarrowTelNum(form.P_P_FAX_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>")){
	            return errProc(form.P_P_FAX_1);
	          }
	          if(IsNarrowTelNum(form.P_P_FAX_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>")){
	            return errProc(form.P_P_FAX_2);
	          }
	          if(IsNarrowTelNum(form.P_P_FAX_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>")){
	            return errProc(form.P_P_FAX_3);
	          }
	          form.P_P_FAX.value = form.P_P_FAX_1.value + "-" + form.P_P_FAX_2.value + "-" + form.P_P_FAX_3.value;

	          if(form.P_P_FAX.type != "hidden"){
	            if(IsLength(form.P_P_FAX.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('FAX番号')), 'FAX番号');?>")){
	              form.P_P_FAX_1.value = form.P_P_FAX_2.value = form.P_P_FAX_3.value = ""
	              form.P_P_FAX_1.focus();
	              return false;
	            }
	          }
	        } else {
	          form.P_P_FAX.value = "";
	        }


	        if(form.P_P_PTEL_1.value != "" || form.P_P_PTEL_2.value != "" || form.P_P_PTEL_3.value != ""){
	          if(form.P_P_PTEL_1.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_PTEL_1);
	          }
	          if(form.P_P_PTEL_2.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_PTEL_2);
	          }
	          if(form.P_P_PTEL_3.value == ""){
	            alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	            return errProc(form.P_P_PTEL_3);
	          }
	          if(IsNarrowTelNum(form.P_P_PTEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>")){
	            return errProc(form.P_P_PTEL_1);
	          }
	          if(IsNarrowTelNum(form.P_P_PTEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>")){
	            return errProc(form.P_P_PTEL_2);
	          }
	          if(IsNarrowTelNum(form.P_P_PTEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>")){
	            return errProc(form.P_P_PTEL_3);
	          }
	          form.P_P_PTEL.value = form.P_P_PTEL_1.value + "-" + form.P_P_PTEL_2.value + "-" + form.P_P_PTEL_3.value;

	          if(form.P_P_PTEL.type != "hidden"){
	            if(IsLength(form.P_P_PTEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯番号')), '携帯番号');?>")){
	              form.P_P_PTEL_1.value = form.P_P_PTEL_2.value = form.P_P_PTEL_3.value = ""
	              form.P_P_PTEL_1.focus();
	              return false;
	            }
	          }
	        } else {
	          form.P_P_PTEL.value = "";
	        }


	        if(form.P_P_PMAIL.type != "hidden"){
	          if(IsLength(form.P_P_PMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メールアドレス')), '携帯メールアドレス');?>")){
	            return errProc(form.P_P_PMAIL);
	          }
	          if(IsNarrowPlus3(form.P_P_PMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メールアドレス')), '携帯メールアドレス');?>")){
	            return errProc(form.P_P_PMAIL);
	          }
	          if(isMail(form.P_P_PMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メールアドレス')), '携帯メールアドレス');?>")){
	            return errProc(form.P_P_PMAIL);
	          }
	        }

	        if(form.P_P_PMAIL2 != undefined){
	          if(form.P_P_PMAIL2.type != "hidden"){
	            if(IsLength(form.P_P_PMAIL2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メールアドレス再入力')), '携帯メールアドレス再入力');?>")){
	              return errProc(form.P_P_PMAIL2);
	            }
	          }
	          if(IsNarrowPlus3(form.P_P_PMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メールアドレス再入力')), '携帯メールアドレス再入力');?>")){
	            return errProc(form.P_P_PMAIL2);
	          }
	          if(isMail(form.P_P_PMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('携帯メールアドレス再入力')), '携帯メールアドレス再入力');?>")){
	            return errProc(form.P_P_PMAIL2);
	          }
	          if(form.P_P_PMAIL.value != form.P_P_PMAIL2.value){
	            alert("携帯メールアドレスの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
	            form.P_P_PMAIL2.value = "";
	            form.P_P_PMAIL2.focus();
	            return false;
	          }
	        }

	        if(form.P_P_POST_u.value != "" || form.P_P_POST_l.value != ""){
	          if(form.P_P_POST_u.type != "hidden"){
	            if(form.P_P_POST_u.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('郵便番号')), '郵便番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.P_P_POST_u);
	            }
	            if(IsLength(form.P_P_POST_u.value, 3, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('郵便番号')), '郵便番号');?>(上３桁)")){
	              return errProc(form.P_P_POST_u);
	            }
	            if(IsNarrowNum(form.P_P_POST_u.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('郵便番号')), '郵便番号');?>")){
	              return errProc(form.P_P_POST_u);
	            }
	          }
	          if(form.P_P_POST_l.type != "hidden"){
	            if(form.P_P_POST_l.value == ""){
	              alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('郵便番号')), '郵便番号');?>\nを登録する場合は\n全ての欄を入力して下さい");
	              return errProc(form.P_P_POST_l);
	            }
	            if(IsLength(form.P_P_POST_l.value, 4, 4, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('郵便番号')), '郵便番号');?>(下４桁)")){
	              return errProc(form.P_P_POST_l);
	            }
	            if(IsNarrowNum(form.P_P_POST_l.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('郵便番号')), '郵便番号');?>")){
	              return errProc(form.P_P_POST_l);
	            }
	            form.P_P_POST.value = form.P_P_POST_u.value + "-" + form.P_P_POST_l.value;
	          }
	        } else {
	          form.P_P_POST.value = "";
	        }


	        if(form.P_P_ADR != undefined && form.P_P_ADR.type != "hidden"){
	          if(IsLengthB(form.P_P_ADR.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('住所１')), '住所１');?>")){
	            return errProc(form.P_P_ADR);
	          }
	        }

	        if(form.P_P_ADR2 != undefined && form.P_P_ADR2.type != "hidden"){
	          if(IsLengthB(form.P_P_ADR2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('住所２')), '住所２');?>")){
	            return errProc(form.P_P_ADR2);
	          }
	        }

	        if(form.P_P_ADR3 != undefined && form.P_P_ADR3.type != "hidden"){
	          if(IsLengthB(form.P_P_ADR3.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('住所３')), '住所２');?>")){
	            return errProc(form.P_P_ADR3);
	          }
	        }

	        if(form.P_P_ADR_EN != undefined && form.P_P_ADR_EN.type != "hidden"){
	          if(IsLengthB(form.P_P_ADR_EN.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('住所　英語表記')), '住所　英語表記');?>")){
	            return errProc(form.P_P_ADR_EN);
	          }
	        }

	        if((form.P_P_BIRTH_YEAR.value != "") || (form.P_P_BIRTH_MONTH.value != "") && (form.P_P_BIRTH_DAY.value != "")){
	          if(IsDateImp(form.m_birthImperialP.value, form.P_P_BIRTH_YEAR, form.P_P_BIRTH_MONTH, form.P_P_BIRTH_DAY, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('生年月日')), '生年月日');?>") != 0){
	            return false;
	          }
	          form.P_P_BIRTH.value = MakeYMD(form.m_birthImperialP.value, form.P_P_BIRTH_YEAR.value, form.P_P_BIRTH_MONTH.value, form.P_P_BIRTH_DAY.value);
	        } else {
	          form.P_P_BIRTH.value = "";
	        }



	        if(form.P_P_GRADUATION_YEAR != undefined){
	          if(form.P_P_GRADUATION_YEAR.type != "hidden"){
	            if((form.P_P_GRADUATION_YEAR.value != "")){
	              if(IsLengthB(form.P_P_GRADUATION_YEAR.value, 0, 12, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('卒業年度（退職年度）')), '卒業年度（退職年度）');?>")){
	                form.P_P_GRADUATION_YEAR.select();
	                form.P_P_GRADUATION_YEAR.focus();
	                return false;
	              }
	            }
	          }
	        }

	        if(form.P_P_DEPARTMENT != undefined){
	          if(form.P_P_DEPARTMENT.type != "hidden"){
	            if(IsLengthB(form.P_P_DEPARTMENT.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('学部（所属）')), '学部（所属）');?>")){
	              return errProc(form.P_P_DEPARTMENT);
	            }
	          }
	        }

	        if(form.P_P_GRADUATION_POSITION != undefined){
	          if(form.P_P_GRADUATION_POSITION.type != "hidden"){
	            if(IsLengthB(form.P_P_GRADUATION_POSITION.value, 0, 100, "卒業時の学校/学部又は退職時の会社名/役職")){
	              return errProc(form.P_P_GRADUATION_POSITION);
	            }
	          }
	        }

	        if(form.P_P_COUNTRY != undefined){
	          if(form.P_P_COUNTRY.type != "hidden"){
	            if(IsLengthB(form.P_P_COUNTRY.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('国名')), '国名');?>")){
	              return errProc(form.P_P_COUNTRY);
	            }
	          }
	        }
	        if(messageflg != 0){
	          if(m_card_open != "0" && m_open == "0"){
	            var openName1;
	            var openName2;
	            var message = "";
	            switch(m_card_open){
	            case "0":
	              openName1 = "公開しない";
	              break;
	            case "1":
	              openName1 = "一般公開";
	              break;
	            default:
	              openName1 = "会員にのみ公開";
	              break;
	            }
	            switch(m_open){
	            case "0":
	              openName2 = "公開しない";
	              break;
	            case "1":
	              openName2 = "一般公開";
	              break;
	            default:
	              openName2 = "会員にのみ公開";
	              break;
	            }
	            message = "名刺情報公開設定：【" + openName1 + "】\n各名刺データ公開設定：【" + openName2 + "】\nとなっております。";
	            if(m_card_open == "0" || m_open == "0"){
	              message = message + "\n\nこのままですと名刺データは公開されませんがよろしいですか？"
	            }else{
	              message = message + "\n\nこのままですと名刺データは会員にのみ公開されますがよろしいですか？"
	            }
	            if(!confirm(message)){
	              return false;
	            }
	          }
	        }
	        return true;
	    }
		function sdCheckInputData(bMdReg){
	        var form = document.mainForm;

	        if(form.S_AFFILIATION_NAME != undefined && form.S_AFFILIATION_NAME.type != "hidden"){
	          if(IsLengthB(form.S_AFFILIATION_NAME.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('所属')), '所属');?>")){
	            return errProc(form.S_AFFILIATION_NAME);
	          }
	        }

	        if(form.G_REPRESENTATIVE_OP != undefined && form.G_REPRESENTATIVE_OP.type != "hidden"){
	          if(IsLengthB(form.G_REPRESENTATIVE_OP.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('役職')), '役職');?>")){
	            return errProc(form.G_REPRESENTATIVE_OP);
	          }
	        }

	        if(form.G_NAME_EN != undefined && form.G_NAME_EN.type != "hidden"){
	          if(IsLengthB(form.G_NAME_EN.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織名英語表記')), '組織名英語表記');?>")){
	            return errProc(form.G_NAME_EN);
	          }
	        }

	        if(form.G_USER_ID != undefined && form.G_USER_ID.type != "hidden" && form.G_USER_ID.required){
	          if(IsLengthB(form.G_USER_ID.value, 4, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('団体管理組織ＩＤ')), '団体管理組織ＩＤ');?>")){
	            return errProc(form.G_USER_ID);
	          }
	        }

	        if(form.G_ADR_EN != undefined && form.G_ADR_EN.type != "hidden"){
	          if(IsLengthB(form.G_ADR_EN.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('組織住所　英語表記')), '組織住所　英語表記');?>")){
	            return errProc(form.G_ADR_EN);
	          }
	        }

	        if(form.S_OFFICIAL_POSITION != undefined && form.S_OFFICIAL_POSITION.type != "hidden"){
	          if(IsLengthB(form.S_OFFICIAL_POSITION.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('役職')), '役職');?>")){
	            return errProc(form.S_OFFICIAL_POSITION);
	          }
	        }

	        if(bMdReg){
	          if(form.m_chg == "0"){
	            if(form.AFFILIATION_NAME2.type != "hidden"){
	              if(IsLengthB(form.AFFILIATION_NAME2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('所属')), '所属');?>")){
	                return errProc(form.AFFILIATION_NAME2);
	              }
	            }
	            if(form.OFFICIAL_POSITION2.type != "hidden"){
	              if(IsLengthB(form.OFFICIAL_POSITION2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('役職')), '役職');?>")){
	                return errProc(form.OFFICIAL_POSITION2);
	              }
	            }
	          }
	        }

	        if(form.S_X_COMMENT != undefined){
	          if(form.S_X_COMMENT.type != "hidden"){
	            if(IsLength(form.S_X_COMMENT.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('コメント')), 'コメント');?>")){
	              return errProc(form.S_X_COMMENT);
	            }
	          }
	        }
	        return true;
	    }


	    function mdCheckInputData(bMDReg){
	        var form = document.mainForm;

	        if(form.M_LG_ID != undefined){
	          if(form.M_LG_ID.type != "hidden"){
	            if(form.M_LG_ID.value == ""){
	              alert("下部組織を指定してください。");
	              return errProc(form.M_LG_ID);
	            }
	          }
	        }

	        if(form.M_ADMISSION_DATE_Y != undefined){
	          if(form.M_ADMISSION_DATE_Y.type != "hidden"){
	            if((form.M_ADMISSION_DATE_Y.value != "") || (form.M_ADMISSION_DATE_M.value != "") || (form.M_ADMISSION_DATE_D.value != "")){
	              if(IsDateImp(form.m_admImperialM.value, form.M_ADMISSION_DATE_Y, form.M_ADMISSION_DATE_M, form.M_ADMISSION_DATE_D, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('入会年月日')), '入会年月日');?>")){
	                return false;
	              }
	              form.M_ADMISSION_DATE.value = MakeYMD(form.m_admImperialM.value, form.M_ADMISSION_DATE_Y.value, form.M_ADMISSION_DATE_M.value, form.M_ADMISSION_DATE_D.value);
	            } else {
	              form.M_ADMISSION_DATE.value = "";
	            }
	          }
	        }

	        if(form.M_WITHDRAWAL_DATE_Y != undefined){
	          if(form.M_WITHDRAWAL_DATE_Y.type != "hidden"){
	            if((form.M_WITHDRAWAL_DATE_Y.value != "") || (form.M_WITHDRAWAL_DATE_M.value != "") || (form.M_WITHDRAWAL_DATE_D.value != "")){
	              if(IsDateImp(form.m_witImperialM.value, form.M_WITHDRAWAL_DATE_Y, form.M_WITHDRAWAL_DATE_M, form.M_WITHDRAWAL_DATE_D, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('退会年月日')), '退会年月日');?>")){
	                return false;
	              }
	              form.M_WITHDRAWAL_DATE.value = MakeYMD(form.m_witImperialM.value, form.M_WITHDRAWAL_DATE_Y.value, form.M_WITHDRAWAL_DATE_M.value, form.M_WITHDRAWAL_DATE_D.value);
	            } else {
	              form.M_WITHDRAWAL_DATE.value = "";
	            }
	          }
	        }

	        if(form.M_CHANGE_DATE_Y != undefined){
	          if(form.M_CHANGE_DATE_Y.type != "hidden"){
	            if((form.M_CHANGE_DATE_Y.value != "") || (form.M_CHANGE_DATE_M.value != "") || (form.M_CHANGE_DATE_D.value != "")){
	              if(IsDateImp(form.m_chaImperialM.value, form.M_CHANGE_DATE_Y, form.M_CHANGE_DATE_M, form.M_CHANGE_DATE_D, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('異動年月日')), '異動年月日');?>")){
	                return false;
	              }
	              form.M_CHANGE_DATE.value = MakeYMD(form.m_chaImperialM.value, form.M_CHANGE_DATE_Y.value, form.M_CHANGE_DATE_M.value, form.M_CHANGE_DATE_D.value);
	            } else {
	              form.M_CHANGE_DATE.value = "";
	            }
	          }
	        }

	        if(form.M_CHANGE_DATE_Y != undefined){
	          if(form.M_CHANGE_DATE_Y.type != "hidden"){
	            if(IsLength(form.M_CHANGE_REASON.value, 0, 50, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('移動理由')), '移動理由');?>") != 0){
	              return errProc(form.M_CHANGE_REASON);
	            }
	          }
	        }

	        if(form.M_MOVEOUT_DATE_Y != undefined){
	          if(form.M_MOVEOUT_DATE_Y.type != "hidden"){
	            if((form.M_MOVEOUT_DATE_Y.value != "") || (form.M_MOVEOUT_DATE_M.value != "") || (form.M_MOVEOUT_DATE_D.value != "")){
	              if(IsDateImp(form.m_chaImperialM.value, form.M_MOVEOUT_DATE_Y, form.M_MOVEOUT_DATE_M, form.M_MOVEOUT_DATE_D, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('転出年月日')), '転出年月日');?>")){
	                return false;
	              }
	              form.M_MOVEOUT_DATE.value = MakeYMD(form.m_chaImperialM.value, form.M_MOVEOUT_DATE_Y.value, form.M_MOVEOUT_DATE_M.value, form.M_MOVEOUT_DATE_D.value);
	            } else {
	              form.M_MOVEOUT_DATE.value = "";
	            }
	          }
	        }

	        if(form.M_MOVEIN_DATE_Y != undefined){
	          if(form.M_MOVEIN_DATE_Y.type != "hidden"){
	            if((form.M_MOVEIN_DATE_Y.value != "") || (form.M_MOVEIN_DATE_M.value != "") || (form.M_MOVEIN_DATE_D.value != "")){
	              if(IsDateImp(form.m_chaImperialM.value, form.M_MOVEIN_DATE_Y, form.M_MOVEIN_DATE_M, form.M_MOVEIN_DATE_D, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('転入年月日')), '転入年月日');?>")){
	                return false;
	              }
	              form.M_MOVEIN_DATE.value = MakeYMD(form.m_chaImperialM.value, form.M_MOVEIN_DATE_Y.value, form.M_MOVEIN_DATE_M.value, form.M_MOVEIN_DATE_D.value);
	            } else {
	              form.M_MOVEIN_DATE.value = "";
	            }
	          }
	        }

	        if(form.M_CLAIM_CLS != undefined && form.M_FEE_RANK != undefined){
	          if(form.M_CLAIM_CLS.type != "hidden" && form.M_FEE_RANK.type != "hidden"){
	            if(form.M_CLAIM_CLS.value != ""){
	              if(form.M_FEE_RANK.value == ""){
	                alert("会費ランクを入力して下さい。");
	                return errProc(form.M_FEE_RANK);
	              }
	            }
	          }
	        }

	        if(form.M_CLAIM_CLS != undefined && form.M_CLAIM_CYCLE != undefined){
	          if(form.M_CLAIM_CLS.type != "hidden" && form.M_CLAIM_CYCLE.type != "hidden"){
	            if(form.M_CLAIM_CLS.value != ""){
	              if(form.M_CLAIM_CYCLE.value == ""){
	                alert("請求サイクルを入力して下さい。");
	                return errProc(form.M_CLAIM_CYCLE);
	              }
	            }
	          }
	        }

	        if(form.M_FEE_MEMO != undefined){
	          if(form.M_FEE_MEMO.type != "hidden"){
	            if(IsLengthB(form.M_FEE_MEMO.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会費メモ')), '会費メモ');?>") != 0){
	              return errProc(form.M_FEE_MEMO);
	            }
	          }
	        }

	        if(form.M_MOVEOUT_NOTE != undefined){
	          if(form.M_MOVEOUT_NOTE.type != "hidden"){
	            if(IsLengthB(form.M_MOVEOUT_NOTE.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('転出コメント')), '転出コメント');?>") != 0){
	              return errProc(form.M_MOVEOUT_NOTE);
	            }
	          }
	        }
	        
	        if(form.M_MOVEIN_NOTE != undefined){
	          if(form.M_MOVEIN_NOTE.type != "hidden"){
	            if(IsLengthB(form.M_MOVEIN_NOTE.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('転入コメント')), '転入コメント');?>") != 0){
	              return errProc(form.M_MOVEIN_NOTE);
	            }
	          }
	        }

	        if(form.M_BANK_CD != undefined){
	          if(form.M_BANK_CD.type != "hidden"){
	            if(form.M_CLAIM_CLS != undefined){
	              if(form.M_CLAIM_CLS.type != "hidden"){
	                if(form.M_CLAIM_CLS.value == "0"){
	                  if(form.M_BANK_CD.value == ""){
	                    if(form.M_BANK_CD.disabled == true){
	                      alert("銀行コードが未登録です。\n請求先指定を変更、または\n指定先の銀行コードを入力してください。");
	                      return errProc(form.M_BILLING_ID);
	                    }else{
	                      alert("銀行コードを入力して下さい。");
	                      return errProc(form.M_BANK_CD);
	                    }
	                  }
	                }
	              }
	            }
	          }
	        }

	        if(form.M_BRANCH_CD != undefined){
	          if(form.M_BRANCH_CD.type != "hidden"){
	            if(form.M_CLAIM_CLS != undefined){
	              if(form.M_CLAIM_CLS.type != "hidden"){
	                if(form.M_CLAIM_CLS.value == "0"){
	                  if(form.M_BRANCH_CD.value == ""){
	                    if(form.M_BRANCH_CD.disabled == true){
	                      if(form.M_BANK_CD != undefined){
	                        //ゆうちょ以外
	                        if (form.M_BANK_CD.value != "9900"){
	                          alert("支店コードが未登録です。\n請求先指定を変更、または\n指定先の支店コードを入力してください。");
	                          return errProc(form.M_BILLING_ID);
	                        }
	                      }
	                    }else{
	                      if(form.M_BANK_CD != undefined){
	                        //ゆうちょ以外
	                        if (form.M_BANK_CD.value != "9900"){
	                          alert("支店コードを入力して下さい。");
	                          return errProc(form.M_BRANCH_CD);
	                        }
	                      }
	                    }
	                  }
	                }
	              }
	            }
	          }
	        }

	        if(form.M_ACCOUNT_NO != undefined){
	          if(form.M_ACCOUNT_NO.type != "hidden"){
	            if(form.M_CLAIM_CLS != undefined){
	              if(form.M_CLAIM_CLS.type != "hidden"){
	                if(form.M_CLAIM_CLS.value == "0"){
	                  if(form.M_BANK_CD.value != "9900"){
	                    if(IsNull(form.M_ACCOUNT_NO.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('口座番号')), '口座番号');?>")){
	                      return errProc(form.M_ACCOUNT_NO);
	                    }
	                  }
	                }
	              }
	            }
	            if(IsLength(form.M_ACCOUNT_NO.value, 0, 7, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('口座番号')), '口座番号');?>")){
	              return errProc(form.M_ACCOUNT_NO);
	            }
	            if(IsNarrowNum(form.M_ACCOUNT_NO.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('口座番号')), '口座番号');?>")){
	              return errProc(form.M_ACCOUNT_NO);
	            }
	          }
	        }

	        if(form.M_ACCOUNT_NM != undefined){
	          if(form.M_ACCOUNT_NM.type != "hidden"){
	            if(form.M_CLAIM_CLS != undefined){
	              if(form.M_CLAIM_CLS.type != "hidden"){
	                if(form.M_CLAIM_CLS.value == "0"){
	                  if(IsNull(form.M_ACCOUNT_NM.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('口座名義')), '口座名義');?>")){
	                    return errProc(form.M_ACCOUNT_NM);
	                  }
	                }
	              }
	            }
	            if(IsLength(form.M_ACCOUNT_NM.value, 0, 30, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('口座名義')), '口座名義');?>")){
	              return errProc(form.M_ACCOUNT_NM);
	            }
	            if(IsNarrowAccaunt(form.M_ACCOUNT_NM.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('口座名義')), '口座名義');?>")){
	              return errProc(form.M_ACCOUNT_NM);
	            }
	          }
	        }

	        if(form.M_CUST_NO != undefined){
	          if(form.M_CUST_NO.type != "hidden"){
	            if(IsLength(form.M_CUST_NO.value, 0, 12, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('顧客番号')), '顧客番号');?>")){
	              return errProc(form.M_CUST_NO);
	            }
	            if(IsNarrowNum(form.M_CUST_NO.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('顧客番号')), '顧客番号');?>")){
	              return errProc(form.M_CUST_NO);
	            }
	          }
	        }

	        if(form.M_SAVINGS_CD != undefined){
	          if(form.M_SAVINGS_CD.type != "hidden"){
	            if(form.M_CLAIM_CLS != undefined){
	              if(form.M_CLAIM_CLS.type != "hidden"){
	                if(form.M_CLAIM_CLS.value == "0"){
	                  if(form.M_BANK_CD.value == "9900"){
	                    if(IsNull(form.M_SAVINGS_CD.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金記号')), '貯金記号');?>")){
	                      return errProc(form.M_SAVINGS_CD);
	                    }
	                  }
	                }
	              }
	            }
	            if(IsLengthF(form.M_SAVINGS_CD.value, 5, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金記号')), '貯金記号');?>")){
	              return errProc(form.M_SAVINGS_CD);
	            }
	            if(IsLength(form.M_SAVINGS_CD.value, 0, 5, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金記号')), '貯金記号');?>")){
	              return errProc(form.M_SAVINGS_CD);
	            }
	            if(IsNarrowNum(form.M_SAVINGS_CD.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金記号')), '貯金記号');?>")){
	              return errProc(form.M_SAVINGS_CD);
	            }
	          }
	        }

	        if(form.M_SAVINGS_NO != undefined){
	          if(form.M_SAVINGS_NO.type != "hidden"){
	            if(form.M_CLAIM_CLS != undefined){
	              if(form.M_CLAIM_CLS.type != "hidden"){
	                if(form.M_CLAIM_CLS.value == "0"){
	                  if(form.M_BANK_CD.value == "9900"){
	                    if(IsNull(form.M_SAVINGS_NO.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金番号')), '貯金番号');?>")){
	                      return errProc(form.M_SAVINGS_NO);
	                    }
	                  }
	                }
	              }
	            }
	            if(IsLengthF(form.M_SAVINGS_NO.value, 8, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金番号')), '貯金番号');?>")){
	              return errProc(form.M_SAVINGS_NO);
	            }
	            if(IsLength(form.M_SAVINGS_NO.value, 0, 8, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金番号')), '貯金番号');?>")){
	              return errProc(form.M_SAVINGS_NO);
	            }
	            if(IsNarrowNum(form.M_SAVINGS_NO.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('貯金番号')), '貯金番号');?>")){
	              return errProc(form.M_SAVINGS_NO);
	            }
	          }
	        }

	        if(form.M_CONTACT_G_NAME != undefined){
	          if(form.M_CONTACT_G_NAME.type != "hidden"){
	            if(IsLengthB(form.M_CONTACT_G_NAME.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先組織名')), '連絡先組織名');?>")){
	              return errProc(form.M_CONTACT_G_NAME);
	            }
	          }
	        }

	        if(form.M_CONTACT_G_NAME_KN != undefined){
	          if(form.M_CONTACT_G_NAME_KN.type != "hidden"){
	            if(IsLengthB(form.M_CONTACT_G_NAME_KN.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先組織名フリガナ')), '連絡先組織名フリガナ');?>")){
	              return errProc(form.M_CONTACT_G_NAME_KN);
	            }
	          }
	        }

	        if(form.M_CONTACT_C_NAME != undefined){
	          if(form.M_CONTACT_C_NAME.type != "hidden"){
	            if(IsLengthB(form.M_CONTACT_C_NAME.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先名称')), '連絡先名称');?>")){
	              return errProc(form.M_CONTACT_C_NAME);
	            }
	          }
	        }

	        if(form.M_CONTACT_C_NAME_KN != undefined){
	          if(form.M_CONTACT_C_NAME_KN.type != "hidden"){
	            if(IsLengthB(form.M_CONTACT_C_NAME_KN.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先フリガナ')), '連絡先フリガナ');?>")){
	              return errProc(form.M_CONTACT_C_NAME_KN);
	            }
	          }
	        }

	        if(form.G_CAPITAL != undefined){
	          if(form.G_CAPITAL.type != "hidden"){
	            if(IsLength(form.G_CAPITAL.value, 0, 13, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("資本金")), "資本金"); ?>")){
	              return errProc(form.G_CAPITAL);
	            }
	          }
	        }

	        if(form.G_BRANCH_CD != undefined){
	          if(form.G_BRANCH_CD.type != "hidden"){
	            if(IsLength(form.G_BRANCH_CD.value, 0, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("組織支店コード")), "組織支店コード"); ?>")){
	              return errProc(form.G_BRANCH_CD);
	            }
	          }
	        }

	        if(form.P_BRANCH_CD != undefined){
	          if(form.P_BRANCH_CD.type != "hidden"){
	            if(IsLength(form.P_BRANCH_CD.value, 0, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人支店コード")), "個人支店コード"); ?>")){
	              return errProc(form.P_BRANCH_CD);
	            }
	          }
	        }

	        if(form.M_BRANCH_CD != undefined){
	          if(form.M_BRANCH_CD.type != "hidden"){
	            if(IsLength(form.M_BRANCH_CD.value, 0, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("新しい請求先　支店コード")), "新しい請求先　支店コード"); ?>")){
	              return errProc(form.M_BRANCH_CD);
	            }
	          }
	        }

	        if(form.G_ACCOUNT_NO != undefined){
	          if(form.G_ACCOUNT_NO.type != "hidden"){
	            if(IsLength(form.G_ACCOUNT_NO.value, 0, 7, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("組織口座番号")), "組織口座番号"); ?>")){
	              return errProc(form.G_ACCOUNT_NO);
	            }
	          }
	        }

	        if(form.P_ACCOUNT_NO != undefined){
	          if(form.P_ACCOUNT_NO.type != "hidden"){
	            if(IsLength(form.P_ACCOUNT_NO.value, 0, 7, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人口座番号")), "個人口座番号"); ?>")){
	              return errProc(form.P_ACCOUNT_NO);
	            }
	          }
	        }

	        if(form.M_ACCOUNT_NO != undefined){
	          if(form.M_ACCOUNT_NO.type != "hidden"){
	            if(IsLength(form.M_ACCOUNT_NO.value, 0, 7, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("新しい請求先　口座番号")), "新しい請求先　口座番号"); ?>")){
	              return errProc(form.M_ACCOUNT_NO);
	            }
	          }
	        }

	        if(form.G_ACCAUNT_NM != undefined){
	          if(form.G_ACCAUNT_NM.type != "hidden"){
	            if(IsLength(form.G_ACCAUNT_NM.value, 0, 30, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("組織口座名義")), "組織口座名義"); ?>")){
	              return errProc(form.G_ACCAUNT_NM);
	            }
	          }
	        }

	        if(form.P_ACCOUNT_NM != undefined){
	          if(form.P_ACCOUNT_NM.type != "hidden"){
	            if(IsLength(form.P_ACCOUNT_NM.value, 0, 30, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人口座名義")), "個人口座名義"); ?>")){
	              return errProc(form.P_ACCOUNT_NM);
	            }
	          }
	        }

	        if(form.M_ACCOUNT_NM != undefined){
	          if(form.M_ACCOUNT_NM.type != "hidden"){
	            if(IsLength(form.M_ACCOUNT_NM.value, 0, 30, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("新しい請求先　口座名義")), "新しい請求先　口座名義"); ?>")){
	              return errProc(form.M_ACCOUNT_NM);
	            }
	          }
	        }

	        if(form.G_CUST_NO != undefined){
	          if(form.G_CUST_NO.type != "hidden"){
	            if(IsLength(form.G_CUST_NO.value, 0, 12, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("組織顧客番号")), "組織顧客番号"); ?>")){
	              return errProc(form.G_CUST_NO);
	            }
	          }
	        }

	        if(form.P_CUST_NO != undefined){
	          if(form.P_CUST_NO.type != "hidden"){
	            if(IsLength(form.P_CUST_NO.value, 0, 12, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人顧客番号")), "個人顧客番号"); ?>")){
	              return errProc(form.P_CUST_NO);
	            }
	          }
	        }

	        if(form.M_CUST_NO != undefined){
	          if(form.M_CUST_NO.type != "hidden"){
	            if(IsLength(form.M_CUST_NO.value, 0, 12, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("新しい請求先　顧客番号")), "新しい請求先　顧客番号"); ?>")){
	              return errProc(form.M_CUST_NO);
	            }
	          }
	        }

	        if(form.G_SAVINGS_CD != undefined){
	          if(form.G_SAVINGS_CD.type != "hidden"){
	            if(IsLength(form.G_SAVINGS_CD.value, 0, 5, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("貯金記号")), "貯金記号"); ?>")){
	              return errProc(form.G_SAVINGS_CD);
	            }
	          }
	        }

	        if(form.P_SAVINGS_CD != undefined){
	          if(form.P_SAVINGS_CD.type != "hidden"){
	            if(IsLength(form.P_SAVINGS_CD.value, 0, 5, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人貯金記号")), "個人貯金記号"); ?>")){
	              return errProc(form.P_SAVINGS_CD);
	            }
	          }
	        }

	        if(form.M_SAVINGS_CD != undefined){
	          if(form.M_SAVINGS_CD.type != "hidden"){
	            if(IsLength(form.M_SAVINGS_CD.value, 0, 5, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("貯金記号")), "貯金記号"); ?>")){
	              return errProc(form.M_SAVINGS_CD);
	            }
	          }
	        }

	        if(form.G_SAVINGS_NO != undefined){
	          if(form.G_SAVINGS_NO.type != "hidden"){
	            if(IsLength(form.G_SAVINGS_NO.value, 0, 8, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("貯金番号")), "貯金番号"); ?>")){
	              return errProc(form.G_SAVINGS_NO);
	            }
	          }
	        }

	        if(form.P_SAVINGS_NO != undefined){
	          if(form.P_SAVINGS_NO.type != "hidden"){
	            if(IsLength(form.P_SAVINGS_NO.value, 0, 8, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("個人貯金番号")), "個人貯金番号"); ?>")){
	              return errProc(form.P_SAVINGS_NO);
	            }
	          }
	        }

	        if(form.M_SAVINGS_NO != undefined){
	          if(form.M_SAVINGS_NO.type != "hidden"){
	            if(IsLength(form.M_SAVINGS_NO.value, 0, 8, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle("貯金番号")), "貯金番号"); ?>")){
	              return errProc(form.M_SAVINGS_NO);
	            }
	          }
	        }

	        if(form.M_CONTACT_EMAIL != undefined){
	          if(form.M_CONTACT_EMAIL.type != "hidden"){
	            if(IsLength(form.M_CONTACT_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先E-MAIL')), '連絡先E-MAIL');?>")){
	              return errProc(form.M_CONTACT_EMAIL);
	            }
	            if(IsNarrowPlus3(form.M_CONTACT_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先E-MAIL')), '連絡先E-MAIL');?>")){
	              return errProc(form.M_CONTACT_EMAIL);
	            }
	            if(isMail(form.M_CONTACT_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先E-MAIL')), '連絡先E-MAIL');?>連絡先E-MAIL")){
	              return errProc(form.M_CONTACT_EMAIL);
	            }
	          }
	        }

	        if(form.M_CONTACT_EMAIL2 != undefined){
	          if(form.M_CONTACT_EMAIL2.type != "hidden"){
	            if(IsLength(form.M_CONTACT_EMAIL2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先E-MAIL再入力')), '連絡先E-MAIL再入力');?>")){
	              return errProc(form.M_CONTACT_EMAIL2);
	            }
	            if(IsNarrowPlus3(form.M_CONTACT_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先E-MAIL再入力')), '連絡先E-MAIL再入力');?>")){
	              return errProc(form.M_CONTACT_EMAIL2);
	            }
	            if(isMail(form.M_CONTACT_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先E-MAIL再入力')), '連絡先E-MAIL再入力');?>")){
	              return errProc(form.M_CONTACT_EMAIL2);
	            }
	            if(form.M_CONTACT_EMAIL.value != form.M_CONTACT_EMAIL2.value){
	              alert("連絡先E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
	              form.M_CONTACT_EMAIL2.value = "";
	              form.M_CONTACT_EMAIL2.focus();
	              return false;
	            }
	          }
	        }

	        if(form.M_CONTACT_CC_EMAIL != undefined){
	          if(form.M_CONTACT_CC_EMAIL.type != "hidden"){
	            if(IsLength(form.M_CONTACT_CC_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先追加送信先E-MAIL')), '連絡先追加送信先E-MAIL');?>")){
	              return errProc(form.M_CONTACT_CC_EMAIL);
	            }
	            if(IsNarrowPlus3(form.M_CONTACT_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先追加送信先E-MAIL')), '連絡先追加送信先E-MAIL');?>")){
	              return errProc(form.M_CONTACT_CC_EMAIL);
	            }
	            if(isMail(form.M_CONTACT_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先追加送信先E-MAIL')), '連絡先追加送信先E-MAIL');?>")){
	              return errProc(form.M_CONTACT_CC_EMAIL);
	            }
	          }
	        }

	        if(form.M_CONTACT_ID != undefined && form.M_CO_C_TEL_1 != undefined){
	          if(form.M_CO_C_TEL_1.type != "hidden"){
	            if(form.M_CO_C_TEL_1.value != "" || form.M_CO_C_TEL_2.value != "" || form.M_CO_C_TEL_3.value != ""){
	              if(form.M_CO_C_TEL_1.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_TEL_1);
	              }
	              if(form.M_CO_C_TEL_2.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_TEL_2);
	              }
	              if(form.M_CO_C_TEL_3.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_TEL_3);
	              }
	              if(IsNarrowTelNum(form.M_CO_C_TEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>")){
	                return errProc(form.M_CO_C_TEL_1);
	              }
	              if(IsNarrowTelNum(form.M_CO_C_TEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>")){
	                return errProc(form.M_CO_C_TEL_2);
	              }
	              if(IsNarrowTelNum(form.M_CO_C_TEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>")){
	                return errProc(form.M_CO_C_TEL_3);
	              }
	              form.M_CONTACT_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
	              if(IsLength(form.M_CONTACT_TEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先TEL')), '連絡先TEL');?>")){
	                form.M_CO_C_TEL_1.focus();
	                return false;
	              }
	            } else {
	              form.M_CONTACT_TEL.value = "";
	            }
	          }
	        }

	        if(form.M_CO_C_FAX_1 != undefined){
	          if(form.M_CO_C_FAX_1.type != "hidden"){
	            if(form.M_CO_C_FAX_1.value != "" || form.M_CO_C_FAX_2.value != "" || form.M_CO_C_FAX_3.value != ""){
	              if(form.M_CO_C_FAX_1.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_FAX_1);
	              }
	              if(form.M_CO_C_FAX_2.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_FAX_2);
	              }
	              if(form.M_CO_C_FAX_3.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_FAX_3);
	              }
	              if(IsNarrowTelNum(form.M_CO_C_FAX_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>")){
	                return errProc(form.M_CO_C_FAX_1);
	              }
	              if(IsNarrowTelNum(form.M_CO_C_FAX_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>")){
	                return errProc(form.M_CO_C_FAX_2);
	              }
	              if(IsNarrowTelNum(form.M_CO_C_FAX_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>")){
	                return errProc(form.M_CO_C_FAX_3);
	              }
	              form.M_CONTACT_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
	              if(IsLength(form.M_CONTACT_FAX.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先FAX')), '連絡先FAX');?>")){
	                form.M_CO_C_FAX_1.value = form.M_CO_C_FAX_2.value = form.M_CO_C_FAX_3.value = ""
	                form.M_CO_C_FAX_1.focus();
	                return false;
	              }
	            } else {
	              form.M_CONTACT_FAX.value = "";
	            }
	          }
	        }

	        if(form.M_CONTACT_ID != undefined && form.M_CO_C_POST_u != undefined){
	          if(form.M_CO_C_POST_u.type != "hidden"){
	            if(form.M_CO_C_POST_u.value != "" || form.M_CO_C_POST_l.value != ""){
	              if(form.M_CO_C_POST_u.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先〒')), '連絡先〒');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_POST_u);
	              }
	              if(IsLength(form.M_CO_C_POST_u.value, 3, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先〒')), '連絡先〒');?>(上３桁)")){
	                return errProc(form.M_CO_C_POST_u);
	              }
	              if(IsNarrowNum(form.M_CO_C_POST_u.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先〒')), '連絡先〒');?>")){
	                return errProc(form.M_CO_C_POST_u);
	              }
	              if(form.M_CO_C_POST_l.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先〒')), '連絡先〒');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CO_C_POST_l);
	              }
	              if(IsLength(form.M_CO_C_POST_l.value, 4, 4, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先〒')), '連絡先〒');?>(下４桁)")){
	                return errProc(form.M_CO_C_POST_l);
	              }
	              if(IsNarrowNum(form.M_CO_C_POST_l.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先〒')), '連絡先〒');?>")){
	                return errProc(form.M_CO_C_POST_l);
	              }
	              form.M_CONTACT_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
	            } else {
	              form.M_CONTACT_POST.value = "";
	            }
	          }
	        }

	        if(form.M_CONTACT_ADR != undefined){
	          if(form.M_CONTACT_ADR.type != "hidden"){
	            if(IsLengthB(form.M_CONTACT_ADR.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先住所１')), '連絡先住所１');?>")){
	              return errProc(form.M_CONTACT_ADR);
	            }
	          }
	        }

	        if(form.M_CONTACT_ADR2 != undefined){
	          if(form.M_CONTACT_ADR2.type != "hidden"){
	            if(IsLengthB(form.M_CONTACT_ADR2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先住所２')), '連絡先住所２');?>")){
	              return errProc(form.M_CONTACT_ADR2);
	            }
	          }
	        }

	        if(form.M_BILLING_G_NAME != undefined){
	          if(form.M_BILLING_G_NAME.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_G_NAME.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先組織名')), '請求先組織名');?>")){
	              return errProc(form.M_BILLING_G_NAME);
	            }
	          }
	        }

	        if(form.M_BILLING_G_KANA != undefined){
	          if(form.M_BILLING_G_KANA.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_G_KANA.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先組織名フリガナ')), '請求先組織名フリガナ');?>")){
	              return errProc(form.M_BILLING_G_KANA);
	            }
	          }
	        }

	        if(form.M_BILLING_G_NAME_EN != undefined){
	          if(form.M_BILLING_G_NAME_EN.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_G_NAME_EN.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先組織名英語表記')), '連絡先組織名英語表記');?>")){
	              return errProc(form.M_BILLING_G_NAME_EN);
	            }
	          }
	        }

	        if(form.M_BILLING_C_NAME != undefined){
	          if(form.M_BILLING_C_NAME.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_C_NAME.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先名称')), '請求先名称');?>")){
	              return errProc(form.M_BILLING_C_NAME);
	            }
	          }
	        }

	        if(form.M_BILLING_C_NAME_KN != undefined){
	          if(form.M_BILLING_C_NAME_KN.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_C_NAME_KN.value, 0, 200, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先フリガナ')), '請求先フリガナ');?>")){
	              return errProc(form.M_BILLING_C_NAME_KN);
	            }
	          }
	        }

	        if(form.M_BILLING_EMAIL != undefined){
	          if(form.M_BILLING_EMAIL.type != "hidden"){
	            if(IsLength(form.M_BILLING_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先E-MAIL')), '請求先E-MAIL');?>")){
	              return errProc(form.M_BILLING_EMAIL);
	            }
	            if(IsNarrowPlus3(form.M_BILLING_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先E-MAIL')), '請求先E-MAIL');?>")){
	              return errProc(form.M_BILLING_EMAIL);
	            }
	            if(isMail(form.M_BILLING_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先E-MAIL')), '請求先E-MAIL');?>")){
	              return errProc(form.M_BILLING_EMAIL);
	            }
	          }
	        }

	        if(form.M_BILLING_EMAIL2 != undefined){
	          if(form.M_BILLING_EMAIL2.type != "hidden"){
	            if(IsLength(form.M_BILLING_EMAIL2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先E-MAIL再入力')), '請求先E-MAIL再入力');?>")){
	              return errProc(form.M_BILLING_EMAIL2);
	            }
	          
	            if(IsNarrowPlus3(form.M_BILLING_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先E-MAIL再入力')), '請求先E-MAIL再入力');?>")){
	              return errProc(form.M_BILLING_EMAIL2);
	            }
	            if(isMail(form.M_BILLING_EMAIL2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先E-MAIL再入力')), '請求先E-MAIL再入力');?>")){
	              return errProc(form.M_BILLING_EMAIL2);
	            }
	            if(form.M_BILLING_EMAIL.value != form.M_BILLING_EMAIL2.value){
	              alert("請求先E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
	              form.M_BILLING_EMAIL2.value = "";
	              form.M_BILLING_EMAIL2.focus();
	              return false;
	            }
	          }
	        }

	        if(form.M_BILLING_CC_EMAIL != undefined){
	          if(form.M_BILLING_CC_EMAIL.type != "hidden"){
	            if(IsLength(form.M_BILLING_CC_EMAIL.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先追加送信先E-MAIL')), '請求先追加送信先E-MAIL');?>")){
	              return errProc(form.M_BILLING_CC_EMAIL);
	            }
	            if(IsNarrowPlus3(form.M_BILLING_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先追加送信先E-MAIL')), '請求先追加送信先E-MAIL');?>")){
	              return errProc(form.M_BILLING_CC_EMAIL);
	            }
	            if(isMail(form.M_BILLING_CC_EMAIL.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先追加送信先E-MAIL')), '請求先追加送信先E-MAIL');?>")){
	              return errProc(form.M_BILLING_CC_EMAIL);
	            }
	          }
	        }

	        if(form.M_BILLING_ID != undefined && form.M_CL_C_TEL_1 != undefined){
	          if(form.M_CL_C_TEL_1.type != "hidden"){
	            if(form.M_BILLING_ID.value == "2"){
	              if(form.M_CL_C_TEL_1.value != "" || form.M_CL_C_TEL_2.value != "" || form.M_CL_C_TEL_3.value != ""){
	                if(form.M_CL_C_TEL_1.value == ""){
	                  alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                  return errProc(form.M_CL_C_TEL_1);
	                }
	                if(form.M_CL_C_TEL_2.value == ""){
	                  alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                  return errProc(form.M_CL_C_TEL_2);
	                }
	                if(form.M_CL_C_TEL_3.value == ""){
	                  alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                  return errProc(form.M_CL_C_TEL_3);
	                }
	                if(IsNarrowTelNum(form.M_CL_C_TEL_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>")){
	                  return errProc(form.M_CL_C_TEL_1);
	                }
	                if(IsNarrowTelNum(form.M_CL_C_TEL_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>")){
	                  return errProc(form.M_CL_C_TEL_2);
	                }
	                if(IsNarrowTelNum(form.M_CL_C_TEL_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>")){
	                  return errProc(form.M_CL_C_TEL_3);
	                }
	                form.M_BILLING_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
	                if(IsLength(form.M_BILLING_TEL.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先TEL')), '請求先TEL');?>")){
	                  form.M_CL_C_TEL_1.focus();
	                  return false;
	                }
	              } else {
	                form.M_BILLING_TEL.value = "";
	              }
	            }
	          }
	        }

	        if(form.M_CL_C_FAX_1 != undefined){
	          if(form.M_CL_C_FAX_1.type != "hidden"){
	            if(form.M_CL_C_FAX_1.value != "" || form.M_CL_C_FAX_2.value != "" || form.M_CL_C_FAX_3.value != ""){
	              if(form.M_CL_C_FAX_1.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CL_C_FAX_1);
	              }
	              if(form.M_CL_C_FAX_2.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CL_C_FAX_2);
	              }
	              if(form.M_CL_C_FAX_3.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CL_C_FAX_3);
	              }
	              if(IsNarrowTelNum(form.M_CL_C_FAX_1.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>")){
	                return errProc(form.M_CL_C_FAX_1);
	              }
	              if(IsNarrowTelNum(form.M_CL_C_FAX_2.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>")){
	                return errProc(form.M_CL_C_FAX_2);
	              }
	              if(IsNarrowTelNum(form.M_CL_C_FAX_3.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>")){
	                return errProc(form.M_CL_C_FAX_3);
	              }
	              form.M_BILLING_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
	              if(IsLength(form.M_BILLING_FAX.value, 0, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先FAX')), '請求先FAX');?>")){
	                form.M_CL_C_FAX_1.value = form.M_CL_C_FAX_2.value = form.M_CL_C_FAX_3.value = "";
	                form.M_CL_C_FAX_1.focus();
	                return false;
	              }
	            } else {
	              form.M_BILLING_FAX.value = "";
	            }
	          }
	        }

	        if(form.M_BILLING_ID != undefined && form.M_CL_C_POST_u != undefined){
	          if(form.M_CL_C_POST_u.type != "hidden"){
	            if(form.M_CL_C_POST_u.value != "" || form.M_CL_C_POST_l.value != ""){
	              if(form.M_CL_C_POST_u.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先〒')), '請求先〒');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CL_C_POST_u);
	              }
	              if(IsLength(form.M_CL_C_POST_u.value, 3, 3, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先〒')), '請求先〒');?>(上３桁)")){
	                return errProc(form.M_CL_C_POST_u);
	              }
	              if(IsNarrowNum(form.M_CL_C_POST_u.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先〒')), '請求先〒');?>")){
	                return errProc(form.M_CL_C_POST_u);
	              }
	              if(form.M_CL_C_POST_l.value == ""){
	                alert("<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先〒')), '請求先〒');?>\nを登録する場合は\n全ての欄を入力して下さい");
	                return errProc(form.M_CL_C_POST_l);
	              }
	              if(IsLength(form.M_CL_C_POST_l.value, 4, 4, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先〒')), '請求先〒');?>(下４桁)")){
	                return errProc(form.M_CL_C_POST_l);
	              }
	              if(IsNarrowNum(form.M_CL_C_POST_l.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先〒')), '請求先〒');?>")){
	                return errProc(form.M_CL_C_POST_l);
	              }
	              form.M_BILLING_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
	            } else {
	              form.M_BILLING_POST.value = "";
	            }
	          }
	        }

	        if(form.M_BILLING_ADR != undefined){
	          if(form.M_BILLING_ADR.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_ADR.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先住所１')), '請求先住所１');?>")){
	              return errProc(form.M_BILLING_ADR);
	            }
	          }
	        }

	        if(form.M_BILLING_ADR2 != undefined){
	          if(form.M_BILLING_ADR2.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_ADR2.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先住所２')), '請求先住所２');?>")){
	              return errProc(form.M_BILLING_ADR2);
	            }
	          }
	        }

	        if(form.M_BILLING_ADR3 != undefined){
	          if(form.M_BILLING_ADR3.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_ADR3.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先住所３')), '請求先住所３');?>")){
	              return errProc(form.M_BILLING_ADR3);
	            }
	          }
	        }

	        if(form.M_BILLING_ADR_EN != undefined){
	          if(form.M_BILLING_ADR_EN.type != "hidden"){
	            if(IsLengthB(form.M_BILLING_ADR_EN.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('請求先住所　英語表記')), '請求先住所　英語表記');?>")){
	              return errProc(form.M_BILLING_ADR_EN);
	            }
	          }
	        }

	        if(form.M_RECOMMEND_P_ID != undefined){
	          if(form.M_RECOMMEND_P_ID.type != "hidden"){
	            if(IsLength(form.M_RECOMMEND_P_ID.value, 4, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('推薦者個人ID')), '推薦者個人ID');?>")){
	              return errProc(form.M_RECOMMEND_P_ID);
	            }
	            if(IsNarrowPlus(form.M_RECOMMEND_P_ID.value, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('推薦者個人ID')), '推薦者個人ID');?>")){
	              return errProc(form.M_RECOMMEND_P_ID);
	            }
	          }
	        }

	        if(form.M_USER_ID != undefined){
	          if(form.M_USER_ID.type != "hidden"){
	            if(IsLength(form.M_USER_ID.value, 4, 20, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('推薦者個人ID')), '推薦者個人ID');?>")){
	              return errProc(form.M_USER_ID);
	            }
	          }
	        }

	        if(form.M_CONTACT_G_NAME_EN != undefined){
	          if(form.M_CONTACT_G_NAME_EN.type != "hidden"){
	            if(IsLength(form.M_CONTACT_G_NAME_EN.value, 0, 1000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先組織名英語表記')), '連絡先組織名英語表記');?>")){
	              return errProc(form.M_CONTACT_G_NAME_EN);
	            }
	          }
	        }

	        if(form.M_CONTACT_ADR3 != undefined){
	          if(form.M_CONTACT_ADR3.type != "hidden"){
	            if(IsLength(form.M_CONTACT_ADR3.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先住所３')), '連絡先住所３');?>")){
	              return errProc(form.M_CONTACT_ADR3);
	            }
	          }
	        }

	        if(form.M_CONTACT_ADR_EN != undefined){
	          if(form.M_CONTACT_ADR_EN.type != "hidden"){
	            if(IsLength(form.M_CONTACT_ADR_EN.value, 0, 100, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('連絡先住所　英語表記')), '連絡先住所　英語表記');?>")){
	              return errProc(form.M_CONTACT_ADR_EN);
	            }
	          }
	        }
	        

	        if(form.M_X_COMMENT != undefined){
	          if(form.M_X_COMMENT.type != "hidden"){
	            if(IsLength(form.M_X_COMMENT.value, 0, 500, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('コメント')), 'コメント');?>")){
	              return errProc(form.M_X_COMMENT);
	            }
	          }
	        }

	        if(form.M_FAX_TIMEZONE != undefined){
	          var fax_timezone;
	          if(form.M_FAX_TIMEZONE.type != "hidden"){
	            for(var i = 0; i < form.M_FAX_TIMEZONE.length; i++){
	              if(form.M_FAX_TIMEZONE[i].checked == true){
	                fax_timezone = form.M_FAX_TIMEZONE[i].value
	              }
	            }
	            if(fax_timezone=="4"){
	              if(form.FAX_TIME_FROM_H.value==""||form.FAX_TIME_FROM_N.value==""||form.FAX_TIME_TO_H.value==""||form.FAX_TIME_TO_N.value==""){
	                alert("FAX送信時間は必ず入力して下さい。");
	                form.FAX_TIME_FROM_H.focus();
	                return false;
	              }
	              form.FAX_TIME_FROM.value=form.FAX_TIME_FROM_H.value + "" + form.FAX_TIME_FROM_N.value;
	              form.FAX_TIME_TO.value=form.FAX_TIME_TO_H.value + "" + form.FAX_TIME_TO_N.value;
	            }else{
	              form.FAX_TIME_FROM_H[0].selected;
	              form.FAX_TIME_FROM_N[0].selected;
	              form.FAX_TIME_TO_H[0].selected;
	              form.FAX_TIME_TO_N[0].selected;
	              form.FAX_TIME_FROM.value="";
	              form.FAX_TIME_TO.value="";
	            }
	          }
	        }

	        if(form.M_FREE1 != undefined){
	          if(form.M_FREE1.type == "text"){
	            if(IsLength(form.M_FREE1.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('予備項目０１')), '予備項目０１');?>")){
	              return errProc(form.M_FREE1);
	            }
	          }
	        }
	        if(form.M_FREE2 != undefined){
	          if(form.M_FREE2.type == "text"){
	            if(IsLength(form.M_FREE2.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２')), '会員自由項目２');?>")){
	              return errProc(form.M_FREE2);
	            }
	          }
	        }
	        if(form.M_FREE3 != undefined){
	          if(form.M_FREE3.type == "text"){
	            if(IsLength(form.M_FREE3.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('予備項目０２')), '予備項目０２');?>")){
	              return errProc(form.M_FREE3);
	            }
	          }
	        }
	        if(form.M_FREE4 != undefined){
	          if(form.M_FREE4.type == "text"){
	            if(IsLength(form.M_FREE4.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４')), '会員自由項目４');?>")){
	              return errProc(form.M_FREE4);
	            }
	          }
	        }
	        if(form.M_FREE5 != undefined){
	          if(form.M_FREE5.type == "text"){
	            if(IsLength(form.M_FREE5.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目５')), '会員自由項目５');?>")){
	              return errProc(form.M_FREE5);
	            }
	          }
	        }
	        if(form.M_FREE6 != undefined){
	          if(form.M_FREE6.type == "text"){
	            if(IsLength(form.M_FREE6.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目６')), '会員自由項目６');?>")){
	              return errProc(form.M_FREE6);
	            }
	          }
	        }
	        if(form.M_FREE7 != undefined){
	          if(form.M_FREE7.type == "text"){
	            if(IsLength(form.M_FREE7.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目７')), '会員自由項目７');?>")){
	              return errProc(form.M_FREE7);
	            }
	          }
	        }
	        if(form.M_FREE8 != undefined){
	          if(form.M_FREE8.type == "text"){
	            if(IsLength(form.M_FREE8.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目８')), '会員自由項目８');?>")){
	              return errProc(form.M_FREE8);
	            }
	          }
	        }
	        if(form.M_FREE9 != undefined){
	          if(form.M_FREE9.type == "text"){
	            if(IsLength(form.M_FREE9.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目９')), '会員自由項目９');?>")){
	              return errProc(form.M_FREE9);
	            }
	          }
	        }
	        if(form.M_FREE10 != undefined){
	          if(form.M_FREE10.type == "text"){
	            if(IsLength(form.M_FREE10.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１０')), '会員自由項目１０');?>")){
	              return errProc(form.M_FREE10);
	            }
	          }
	        }
	        if(form.M_FREE11 != undefined){
	          if(form.M_FREE11.type == "text"){
	            if(IsLength(form.M_FREE11.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１１')), '会員自由項目１１');?>")){
	              return errProc(form.M_FREE11);
	            }
	          }
	        }
	        if(form.M_FREE12 != undefined){
	          if(form.M_FREE12.type == "text"){
	            if(IsLength(form.M_FREE12.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１２')), '会員自由項目１２');?>")){
	              return errProc(form.M_FREE12);
	            }
	          }
	        }
	        if(form.M_FREE13 != undefined){
	          if(form.M_FREE13.type == "text"){
	            if(IsLength(form.M_FREE13.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１３')), '会員自由項目１３');?>")){
	              return errProc(form.M_FREE13);
	            }
	          }
	        }
	        if(form.M_FREE14 != undefined){
	          if(form.M_FREE14.type == "text"){
	            if(IsLength(form.M_FREE14.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１４')), '会員自由項目１４');?>")){
	              return errProc(form.M_FREE14);
	            }
	          }
	        }
	        if(form.M_FREE15 != undefined){
	          if(form.M_FREE15.type == "text"){
	            if(IsLength(form.M_FREE15.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１５')), '会員自由項目１５');?>")){
	              return errProc(form.M_FREE15);
	            }
	          }
	        }
	        if(form.M_FREE16 != undefined){
	          if(form.M_FREE16.type == "text"){
	            if(IsLength(form.M_FREE16.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１６')), '会員自由項目１６');?>")){
	              return errProc(form.M_FREE16);
	            }
	          }
	        }
	        if(form.M_FREE17 != undefined){
	          if(form.M_FREE17.type == "text"){
	            if(IsLength(form.M_FREE17.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１７')), '会員自由項目１７');?>")){
	              return errProc(form.M_FREE17);
	            }
	          }
	        }
	        if(form.M_FREE18 != undefined){
	          if(form.M_FREE18.type == "text"){
	            if(IsLength(form.M_FREE18.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１８')), '会員自由項目１８');?>")){
	              return errProc(form.M_FREE18);
	            }
	          }
	        }
	        if(form.M_FREE19 != undefined){
	          if(form.M_FREE19.type == "text"){
	            if(IsLength(form.M_FREE19.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目１９')), '会員自由項目１９');?>")){
	              return errProc(form.M_FREE19);
	            }
	          }
	        }
	        if(form.M_FREE20 != undefined){
	          if(form.M_FREE20.type == "text"){
	            if(IsLength(form.M_FREE20.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２０')), '会員自由項目２０');?>")){
	              return errProc(form.M_FREE20);
	            }
	          }
	        }
	        if(form.M_FREE21 != undefined){
	          if(form.M_FREE21.type == "text"){
	            if(IsLength(form.M_FREE21.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２１')), '会員自由項目２１');?>")){
	              return errProc(form.M_FREE21);
	            }
	          }
	        }
	        if(form.M_FREE22 != undefined){
	          if(form.M_FREE22.type == "text"){
	            if(IsLength(form.M_FREE22.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２２')), '会員自由項目２２');?>")){
	              return errProc(form.M_FREE22);
	            }
	          }
	        }
	        if(form.M_FREE23 != undefined){
	          if(form.M_FREE23.type == "text"){
	            if(IsLength(form.M_FREE23.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２３')), '会員自由項目２３');?>")){
	              return errProc(form.M_FREE23);
	            }
	          }
	        }
	        if(form.M_FREE24 != undefined){
	          if(form.M_FREE24.type == "text"){
	            if(IsLength(form.M_FREE24.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２４')), '会員自由項目２４');?>")){
	              return errProc(form.M_FREE24);
	            }
	          }
	        }
	        if(form.M_FREE25 != undefined){
	          if(form.M_FREE25.type == "text"){
	            if(IsLength(form.M_FREE25.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２５')), '会員自由項目２５');?>")){
	              return errProc(form.M_FREE25);
	            }
	          }
	        }
	        if(form.M_FREE26 != undefined){
	          if(form.M_FREE26.type == "text"){
	            if(IsLength(form.M_FREE26.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２６')), '会員自由項目２６');?>")){
	              return errProc(form.M_FREE26);
	            }
	          }
	        }
	        if(form.M_FREE27 != undefined){
	          if(form.M_FREE27.type == "text"){
	            if(IsLength(form.M_FREE27.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２７')), '会員自由項目２７');?>")){
	              return errProc(form.M_FREE27);
	            }
	          }
	        }
	        if(form.M_FREE28 != undefined){
	          if(form.M_FREE28.type == "text"){
	            if(IsLength(form.M_FREE28.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２８')), '会員自由項目２８');?>")){
	              return errProc(form.M_FREE28);
	            }
	          }
	        }
	        if(form.M_FREE29 != undefined){
	          if(form.M_FREE29.type == "text"){
	            if(IsLength(form.M_FREE29.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目２９')), '会員自由項目２９');?>")){
	              return errProc(form.M_FREE29);
	            }
	          }
	        }
	        if(form.M_FREE30 != undefined){
	          if(form.M_FREE30.type == "text"){
	            if(IsLength(form.M_FREE30.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３０')), '会員自由項目３０');?>")){
	              return errProc(form.M_FREE30);
	            }
	          }
	        }
	        if(form.M_FREE31 != undefined){
	          if(form.M_FREE31.type == "text"){
	            if(IsLength(form.M_FREE31.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３１')), '会員自由項目３１');?>")){
	              return errProc(form.M_FREE31);
	            }
	          }
	        }
	        if(form.M_FREE32 != undefined){
	          if(form.M_FREE32.type == "text"){
	            if(IsLength(form.M_FREE32.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３２')), '会員自由項目３２');?>")){
	              return errProc(form.M_FREE32);
	            }
	          }
	        }
	        if(form.M_FREE33 != undefined){
	          if(form.M_FREE33.type == "text"){
	            if(IsLength(form.M_FREE33.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３３')), '会員自由項目３３');?>")){
	              return errProc(form.M_FREE33);
	            }
	          }
	        }
	        if(form.M_FREE34 != undefined){
	          if(form.M_FREE34.type == "text"){
	            if(IsLength(form.M_FREE34.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３４')), '会員自由項目３４');?>")){
	              return errProc(form.M_FREE34);
	            }
	          }
	        }
	        if(form.M_FREE35 != undefined){
	          if(form.M_FREE35.type == "text"){
	            if(IsLength(form.M_FREE35.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３５')), '会員自由項目３５');?>")){
	              return errProc(form.M_FREE35);
	            }
	          }
	        }
	        if(form.M_FREE36 != undefined){
	          if(form.M_FREE36.type == "text"){
	            if(IsLength(form.M_FREE36.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３６')), '会員自由項目３６');?>")){
	              return errProc(form.M_FREE36);
	            }
	          }
	        }
	        if(form.M_FREE37 != undefined){
	          if(form.M_FREE37.type == "text"){
	            if(IsLength(form.M_FREE37.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３７')), '会員自由項目３７');?>")){
	              return errProc(form.M_FREE37);
	            }
	          }
	        }
	        if(form.M_FREE38 != undefined){
	          if(form.M_FREE38.type == "text"){
	            if(IsLength(form.M_FREE38.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３８')), '会員自由項目３８');?>")){
	              return errProc(form.M_FREE38);
	            }
	          }
	        }
	        if(form.M_FREE39 != undefined){
	          if(form.M_FREE39.type == "text"){
	            if(IsLength(form.M_FREE39.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目３９')), '会員自由項目３９');?>")){
	            return errProc(form.M_FREE39);
	            }
	          }
	        }
	        if(form.M_FREE40 != undefined){
	          if(form.M_FREE40.type == "text"){
	            if(IsLength(form.M_FREE40.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４０')), '会員自由項目４０');?>")){
	              return errProc(form.M_FREE40);
	            }
	          }
	        }
	        if(form.M_FREE41 != undefined){
	          if(form.M_FREE41.type == "text"){
	            if(IsLength(form.M_FREE41.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４１')), '会員自由項目４１');?>")){
	              return errProc(form.M_FREE41);
	            }
	          }
	        }
	        if(form.M_FREE42 != undefined){
	          if(form.M_FREE42.type == "text"){
	            if(IsLength(form.M_FREE42.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４２')), '会員自由項目４２');?>")){
	              return errProc(form.M_FREE42);
	            }
	          }
	        }
	        if(form.M_FREE43 != undefined){
	          if(form.M_FREE43.type == "text"){
	            if(IsLength(form.M_FREE43.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４３')), '会員自由項目４３');?>")){
	              return errProc(form.M_FREE43);
	            }
	          }
	        }
	        if(form.M_FREE44 != undefined){
	          if(form.M_FREE44.type == "text"){
	            if(IsLength(form.M_FREE44.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４４')), '会員自由項目４４');?>")){
	              return errProc(form.M_FREE44);
	            }
	          }
	        }
	        if(form.M_FREE45 != undefined){
	          if(form.M_FREE45.type == "text"){
	            if(IsLength(form.M_FREE45.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４５')), '会員自由項目４５');?>")){
	              return errProc(form.M_FREE45);
	            }
	          }
	        }
	        if(form.M_FREE46 != undefined){
	          if(form.M_FREE46.type == "text"){
	            if(IsLength(form.M_FREE46.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４６')), '会員自由項目４６');?>")){
	              return errProc(form.M_FREE46);
	            }
	          }
	        }
	        if(form.M_FREE47 != undefined){
	          if(form.M_FREE47.type == "text"){
	            if(IsLength(form.M_FREE47.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４７')), '会員自由項目４７');?>")){
	              return errProc(form.M_FREE47);
	            }
	          }
	        }
	        if(form.M_FREE48 != undefined){
	          if(form.M_FREE48.type == "text"){
	            if(IsLength(form.M_FREE48.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４８')), '会員自由項目４８');?>")){
	              return errProc(form.M_FREE48);
	            }
	          }
	        }
	        if(form.M_FREE49 != undefined){
	          if(form.M_FREE49.type == "text"){
	            if(IsLength(form.M_FREE49.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目４９')), '会員自由項目４９');?>")){
	              return errProc(form.M_FREE49);
	            }
	          }
	        }
	        if(form.M_FREE50 != undefined){
	          if(form.M_FREE50.type == "text"){
	            if(IsLength(form.M_FREE50.value, 0, 2000, "<?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitle('会員自由項目５０')), '会員自由項目５０');?>")){
	              return errProc(form.M_FREE50);
	            }
	          }
	        }
	        return true;
	    }
		function CheckInputData_searver(pdFlg, gdFlg, sdFlg, mdFlg, bChange, bMdReg){
	        var post_id = "<?php echo $args['id']; ?>";
	        var form = document.mainForm;
	        var change_flg="0";
	        if(form.P_C_EMAIL != undefined && form.P_C_EMAIL.value != ""){
	          $.ajax({
	            url: '<?php echo get_home_url(); ?>/wp-admin/admin-ajax.php',
	            type : "POST",
	            data: {
	              action: 'check_email',
	              C_EMAIL: form.P_C_EMAIL.value,
	              post_id: post_id
	            },
	            success: function(response) {
	              if(response.data.status == "Success"){
	                getDatatoJson();
	              }
	              else {
	                alert("入力された個人E-MAILは既に登録されています。\n別の個人E-MAILを入力してください。");
	              }
	            },
	            error: function() {}
	          });
	        }
	        else {
	          getDatatoJson();
	          // $("#mainForm").submit();
	        }
	        if(bChange == true){
	          change_flg="1";
	        }
	        ifmSetUrl = "./rs/commonRsSearch.asp?fncName=CheckInputData";

	        if(pdFlg == true){

	          if(bChange == false){
	            ifmSetUrl = ifmSetUrl + "&PPID=" + form.P_P_ID.value;
	          }
	          ifmSetUrl = ifmSetUrl + "&PCEmail=" + form.P_C_EMAIL.value;
	          ifmSetUrl = ifmSetUrl + "&PCPmail=" + form.P_C_PMAIL.value;
	          ifmSetUrl = ifmSetUrl + "&PPEmail=" + form.P_P_EMAIL.value;
	          ifmSetUrl = ifmSetUrl + "&PPPmail=" + form.P_P_PMAIL.value;
	          ifmSetUrl = ifmSetUrl + "&pid=" + form.P_P_ID.value;
	        }

	        if(gdFlg == true){
	          if(bChange == false){
	            if(form.G_G_ID.type != "hidden"){

	              ifmSetUrl = ifmSetUrl + "&GGID=" + form.G_G_ID.value;
	            }
	          }
	        }

	        if(sdFlg == true){
	        }

	        if(mdFlg == true){
	        }
	        //ifmSetUrl = ifmSetUrl + "&bChange=" + change_flg;
	        //ifmGetData.location.href = ifmSetUrl;
	    }
	    function getItemRequired(){
          var classSetting = $("#table_setting").attr("class");
          var regItem = '';
          var regValue = '';
          if(classSetting == 'input_table'){
            regItem = "RegItem";
            regValue = "RegValue";
          }else{
            regItem = "RegItem_noline";
            regValue = "RegValue_noline";
          }
          var tr_length = $('table.'+classSetting+' tr').length;
          var tr_element = $('table.'+classSetting+' tr');
          var arrElement = [];
          for(i=0; i<tr_length; i++){
            var label = $(tr_element[i]).find('td.'+regItem).children('span').text();
            if(label != ''){
              var title = $.trim($(tr_element[i]).find('td.'+regItem).text());
              if(title.indexOf("※") != -1){
                title = title.replace("※","");
                title = title.trim();
              }
              var type = '';
              var check_value = $(tr_element[i]).find('td.'+regValue+':first').children().first();
              if($(check_value).is('input')) {
                var item_name = $(tr_element[i]).find('td.'+regValue+':first input').attr('name');
                $(tr_element[i]).find('td.'+regValue+':first input').attr("required", true);
                type = 'input';
              }else if($(check_value).is('textarea')) {
                var item_name = $(tr_element[i]).find('td.'+regValue+':first textarea').attr('name');
                type = 'textarea';
              }else if($(check_value).is('select')) {
                var item_name = $(tr_element[i]).find('td.'+regValue+':first select').attr('name');
                type = 'select';
              }
              var object = {
                title : $.trim(title),
                item_name : $.trim(item_name),
                type : type
              };
              arrElement.push(object);
            }
          }
          return arrElement;
      	}
		function validateItem(){
	        var arrRequired = getItemRequired();
	        var mainForm = document.mainForm;
	        var check = 0;
	        $.each( arrRequired, function( key, item ) {
	          if(item.type == 'input'){
	            var input = $('input[name="'+item.item_name+'"]');
	          }else if(item.type == 'textarea'){
	            var input = $('textarea[name="'+item.item_name+'"]');
	          }else if(item.type == 'select'){
	            var input = $('select[name="'+item.item_name+'"]');
	          }
	          if(input != undefined){
	            var value = input.val();
	            if(IsNullRequired(value, item.title)){
	              input.focus();
	              check = 1;
	              return false;
	            }
	          }
	          check = 0;
	        });
	        return check;
	    }
    </script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/common.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/sedai_link.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/input_check.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/jquery-1.6.3.min.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist_confirm.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/autoKana.js"></script>
    <script src="https://unpkg.com/wanakana"></script>
    <script type="text/javascript">
         var isIE = /*@cc_on!@*/false || !!document.documentMode;
         if(isIE){
           $('.input_table_noline').css('table-layout', 'fixed');
           $('.input_table_noline .RegValue_noline:first').attr('colspan', '2');
         }
         function OnRelease(){
          var form = document.mainForm;
          form.release.value = "1";
          form.method = "post";
          form.action = "confirm.asp";
          form.submit();
         }
         function chkMustInput(){
            var i;
            if(mainForm.G_NAME != undefined){
              if(mainForm.G_NAME.length == undefined){
                if(mainForm.G_NAME.type != "hidden"){
                  if(mainForm.G_NAME.type != "select-one"){
                    if(IsNull(mainForm.G_NAME.value, "組織名")){
                      mainForm.G_NAME.select();
                      mainForm.G_NAME.focus();
                      return false;
                    }
                  }else{
                    if(IsNull(mainForm.G_NAME.value, "組織名")){
                      mainForm.G_NAME.focus();
                      return false;
                    }
                  }
                }else{
                  if(mainForm.G_NAME_1 != undefined){
                    if(mainForm.G_NAME_1.type != "hidden"){
                      if(IsNull(mainForm.G_NAME_1.value, "組織名")){
                        mainForm.G_NAME_1.select();
                        mainForm.G_NAME_1.focus();
                        return false;
                      }
                    }
                  }else{
                    if(mainForm.G_NAME_u != undefined){
                      if(mainForm.G_NAME_u.type != "hidden"){
                        if(IsNull(mainForm.G_NAME_u.value, "組織名")){
                          mainForm.G_NAME_u.select();
                          mainForm.G_NAME_u.focus();
                          return false;
                        }
                      }
                    }else{
                      if(mainForm.G_NAME_YEAR != undefined){
                        if(mainForm.G_NAME_YEAR.type != "hidden"){
                          if(IsNull(mainForm.G_NAME_YEAR.value, "組織名")){
                            mainForm.G_NAME_YEAR.select();
                            mainForm.G_NAME_YEAR.focus();
                            return false;
                          }
                        }
                      }else{
                        if(mainForm.G_NAME_YEAR != undefined){
                          if(mainForm.G_NAME_YEAR.type != "hidden"){
                            if(IsNull(mainForm.G_NAME_YEAR.value, "組織名")){
                              mainForm.G_NAME_YEAR.select();
                              mainForm.G_NAME_YEAR.focus();
                              return false;
                            }
                          }
                        }else{
                          if(mainForm.G_NAME_SEL != undefined){
                            if(mainForm.G_NAME_SEL.type != "hidden"){
                              if(IsNull(mainForm.G_NAME_SEL.value, "組織名")){
                                mainForm.G_NAME_SEL.focus();
                                return false;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if(mainForm.G_KANA != undefined){
              if(mainForm.G_KANA.length == undefined){
                if(mainForm.G_KANA.type != "hidden"){
                  if(mainForm.G_KANA.type != "select-one"){
                    if(IsNull(mainForm.G_KANA.value, "組織フリガナ")){
                      mainForm.G_KANA.select();
                      mainForm.G_KANA.focus();
                      return false;
                    }
                  }else{
                    if(IsNull(mainForm.G_KANA.value, "組織フリガナ")){
                      mainForm.G_KANA.focus();
                      return false;
                    }
                  }
                }else{
                  if(mainForm.G_KANA_1 != undefined){
                    if(mainForm.G_KANA_1.type != "hidden"){
                      if(IsNull(mainForm.G_KANA_1.value, "組織フリガナ")){
                        mainForm.G_KANA_1.select();
                        mainForm.G_KANA_1.focus();
                        return false;
                      }
                    }
                  }else{
                    if(mainForm.G_KANA_u != undefined){
                      if(mainForm.G_KANA_u.type != "hidden"){
                        if(IsNull(mainForm.G_KANA_u.value, "組織フリガナ")){
                          mainForm.G_KANA_u.select();
                          mainForm.G_KANA_u.focus();
                          return false;
                        }
                      }
                    }else{
                      if(mainForm.G_KANA_YEAR != undefined){
                        if(mainForm.G_KANA_YEAR.type != "hidden"){
                          if(IsNull(mainForm.G_KANA_YEAR.value, "組織フリガナ")){
                            mainForm.G_KANA_YEAR.select();
                            mainForm.G_KANA_YEAR.focus();
                            return false;
                          }
                        }
                      }else{
                        if(mainForm.G_KANA_YEAR != undefined){
                          if(mainForm.G_KANA_YEAR.type != "hidden"){
                            if(IsNull(mainForm.G_KANA_YEAR.value, "組織フリガナ")){
                              mainForm.G_KANA_YEAR.select();
                              mainForm.G_KANA_YEAR.focus();
                              return false;
                            }
                          }
                        }else{
                          if(mainForm.G_KANA_SEL != undefined){
                            if(mainForm.G_KANA_SEL.type != "hidden"){
                              if(IsNull(mainForm.G_KANA_SEL.value, "組織フリガナ")){
                                mainForm.G_KANA_SEL.focus();
                                return false;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if(mainForm.P_C_NAME != undefined){
              if(mainForm.P_C_NAME.length == undefined){
                if(mainForm.P_C_NAME.type != "hidden"){
                  if(mainForm.P_C_NAME.type != "select-one"){
                    if(IsNull(mainForm.P_C_NAME.value, "氏名")){
                      mainForm.P_C_NAME.select();
                      mainForm.P_C_NAME.focus();
                      return false;
                    }
                  }else{
                    if(IsNull(mainForm.P_C_NAME.value, "氏名")){
                      mainForm.P_C_NAME.focus();
                      return false;
                    }
                  }
                }else{
                  if(mainForm.P_C_NAME_1 != undefined){
                    if(mainForm.P_C_NAME_1.type != "hidden"){
                      if(IsNull(mainForm.P_C_NAME_1.value, "氏名")){
                        mainForm.P_C_NAME_1.select();
                        mainForm.P_C_NAME_1.focus();
                        return false;
                      }
                    }
                  }else{
                    if(mainForm.P_C_NAME_u != undefined){
                      if(mainForm.P_C_NAME_u.type != "hidden"){
                        if(IsNull(mainForm.P_C_NAME_u.value, "氏名")){
                          mainForm.P_C_NAME_u.select();
                          mainForm.P_C_NAME_u.focus();
                          return false;
                        }
                      }
                    }else{
                      if(mainForm.P_C_NAME_YEAR != undefined){
                        if(mainForm.P_C_NAME_YEAR.type != "hidden"){
                          if(IsNull(mainForm.P_C_NAME_YEAR.value, "氏名")){
                            mainForm.P_C_NAME_YEAR.select();
                            mainForm.P_C_NAME_YEAR.focus();
                            return false;
                          }
                        }
                      }else{
                        if(mainForm.P_C_NAME_YEAR != undefined){
                          if(mainForm.P_C_NAME_YEAR.type != "hidden"){
                            if(IsNull(mainForm.P_C_NAME_YEAR.value, "氏名")){
                              mainForm.P_C_NAME_YEAR.select();
                              mainForm.P_C_NAME_YEAR.focus();
                              return false;
                            }
                          }
                        }else{
                          if(mainForm.P_C_NAME_SEL != undefined){
                            if(mainForm.P_C_NAME_SEL.type != "hidden"){
                              if(IsNull(mainForm.P_C_NAME_SEL.value, "氏名")){
                                mainForm.P_C_NAME_SEL.focus();
                                return false;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if(mainForm.P_C_KANA != undefined){
              if(mainForm.P_C_KANA.length == undefined){
                if(mainForm.P_C_KANA.type != "hidden"){
                  if(mainForm.P_C_KANA.type != "select-one"){
                    if(IsNull(mainForm.P_C_KANA.value, "個人フリガナ")){
                      mainForm.P_C_KANA.select();
                      mainForm.P_C_KANA.focus();
                      return false;
                    }
                  }else{
                    if(IsNull(mainForm.P_C_KANA.value, "個人フリガナ")){
                      mainForm.P_C_KANA.focus();
                      return false;
                    }
                  }
                }else{
                  if(mainForm.P_C_KANA_1 != undefined){
                    if(mainForm.P_C_KANA_1.type != "hidden"){
                      if(IsNull(mainForm.P_C_KANA_1.value, "個人フリガナ")){
                        mainForm.P_C_KANA_1.select();
                        mainForm.P_C_KANA_1.focus();
                        return false;
                      }
                    }
                  }else{
                    if(mainForm.P_C_KANA_u != undefined){
                      if(mainForm.P_C_KANA_u.type != "hidden"){
                        if(IsNull(mainForm.P_C_KANA_u.value, "個人フリガナ")){
                          mainForm.P_C_KANA_u.select();
                          mainForm.P_C_KANA_u.focus();
                          return false;
                        }
                      }
                    }else{
                      if(mainForm.P_C_KANA_YEAR != undefined){
                        if(mainForm.P_C_KANA_YEAR.type != "hidden"){
                          if(IsNull(mainForm.P_C_KANA_YEAR.value, "個人フリガナ")){
                            mainForm.P_C_KANA_YEAR.select();
                            mainForm.P_C_KANA_YEAR.focus();
                            return false;
                          }
                        }
                      }else{
                        if(mainForm.P_C_KANA_YEAR != undefined){
                          if(mainForm.P_C_KANA_YEAR.type != "hidden"){
                            if(IsNull(mainForm.P_C_KANA_YEAR.value, "個人フリガナ")){
                              mainForm.P_C_KANA_YEAR.select();
                              mainForm.P_C_KANA_YEAR.focus();
                              return false;
                            }
                          }
                        }else{
                          if(mainForm.P_C_KANA_SEL != undefined){
                            if(mainForm.P_C_KANA_SEL.type != "hidden"){
                              if(IsNull(mainForm.P_C_KANA_SEL.value, "個人フリガナ")){
                                mainForm.P_C_KANA_SEL.focus();
                                return false;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if(mainForm.P_C_EMAIL != undefined){
              if(mainForm.P_C_EMAIL.length == undefined){
                if(mainForm.P_C_EMAIL.type != "hidden"){
                  if(mainForm.P_C_EMAIL.type != "select-one"){
                    if(IsNull(mainForm.P_C_EMAIL.value, "個人E-MAIL")){
                      mainForm.P_C_EMAIL.select();
                      mainForm.P_C_EMAIL.focus();
                      return false;
                    }
                  }else{
                    if(IsNull(mainForm.P_C_EMAIL.value, "個人E-MAIL")){
                      mainForm.P_C_EMAIL.focus();
                      return false;
                    }
                  }
                }else{
                  if(mainForm.P_C_EMAIL_1 != undefined){
                    if(mainForm.P_C_EMAIL_1.type != "hidden"){
                      if(IsNull(mainForm.P_C_EMAIL_1.value, "個人E-MAIL")){
                        mainForm.P_C_EMAIL_1.select();
                        mainForm.P_C_EMAIL_1.focus();
                        return false;
                      }
                    }
                  }else{
                    if(mainForm.P_C_EMAIL_u != undefined){
                      if(mainForm.P_C_EMAIL_u.type != "hidden"){
                        if(IsNull(mainForm.P_C_EMAIL_u.value, "個人E-MAIL")){
                          mainForm.P_C_EMAIL_u.select();
                          mainForm.P_C_EMAIL_u.focus();
                          return false;
                        }
                      }
                    }else{
                      if(mainForm.P_C_EMAIL_YEAR != undefined){
                        if(mainForm.P_C_EMAIL_YEAR.type != "hidden"){
                          if(IsNull(mainForm.P_C_EMAIL_YEAR.value, "個人E-MAIL")){
                            mainForm.P_C_EMAIL_YEAR.select();
                            mainForm.P_C_EMAIL_YEAR.focus();
                            return false;
                          }
                        }
                      }else{
                        if(mainForm.P_C_EMAIL_YEAR != undefined){
                          if(mainForm.P_C_EMAIL_YEAR.type != "hidden"){
                            if(IsNull(mainForm.P_C_EMAIL_YEAR.value, "個人E-MAIL")){
                              mainForm.P_C_EMAIL_YEAR.select();
                              mainForm.P_C_EMAIL_YEAR.focus();
                              return false;
                            }
                          }
                        }else{
                          if(mainForm.P_C_EMAIL_SEL != undefined){
                            if(mainForm.P_C_EMAIL_SEL.type != "hidden"){
                              if(IsNull(mainForm.P_C_EMAIL_SEL.value, "個人E-MAIL")){
                                mainForm.P_C_EMAIL_SEL.focus();
                                return false;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
            return true;
          }

         function OnLoad(){
          var form = document.mainForm;

          OnChangeLowerGroup();

           SetData();

           if(form.FAX_TIME_FROM != undefined) {

             if (form.M_FAX_TIMEZONE.type != "hidden"){

               if(form.M_FAX_TIMEZONE[4].checked == true){
                 form.FAX_TIME_FROM_H.disabled = false;
                 form.FAX_TIME_FROM_N.disabled = false;
                 form.FAX_TIME_TO_H.disabled   = false;
                 form.FAX_TIME_TO_N.disabled   = false;
               } else {
                 form.FAX_TIME_FROM_H.disabled = true;
                 form.FAX_TIME_FROM_N.disabled = true;
                 form.FAX_TIME_TO_H.disabled   = true;
                 form.FAX_TIME_TO_N.disabled   = true;
               }
             }
           }

          OnUseRegisteredGid();

          OnUseRegisteredPid();

         }

         function OnConfirm(){
            var form = document.mainForm;
            var merumaga_flg = '<?php echo $dataSetting['merumaga_flg'][0]; ?>'; 
            if(merumaga_flg == "2"){
                var i;
                for(i = 0; i < form.merumaga_flg.length; i ++){
                  if(form.merumaga_flg[i].checked){
                    if(form.merumaga_flg[i].value != "1"){
                      alert("同意確認を「する」にしてください。");
                      return
                    }
                  }
                }
            }
            if(form.P_P_ID.value == ""){
               if(form.P_P_ID.type == "hidden"){
                  OnMakePidAuto();
               }
            }else if(form.P_PASSWORD != undefined && form.P_PASSWORD.value == "" && form.P_PASSWORD.type == "hidden"){
              OnBlurPid();
            }
            if(form.M_LG_ID.type == "hidden") 
              form.M_LG_ID.value = "<?php echo get_post_meta($args['id'],'set_lg_g_id',true); ?>";
            
            // if(!chkMustInput()) return;

            var e_flg;
            var e_flg2;
            var f_flg;
            e_flg = 0
            e_flg2 = 0
            f_flg = 0
            if(document.mainForm.M_CONTRACT_TYPE != undefined && document.mainForm.M_CONTACT_ID != undefined &&
              document.mainForm.M_CONTACT_EMAIL != undefined && document.mainForm.M_CO_C_FAX_1 != undefined){
               if(document.mainForm.M_CONTACT_EMAIL.type != "hidden" && document.mainForm.M_CO_C_FAX_1.type != "hidden"){
                  var in_mail = false;      
                  var in_fax = false;       
                  var sel_contact = "";     
                  var focus_obj_mail = "";  
                  var focus_obj_fax = "";
                  switch (form.M_CONTACT_ID.value){
                     case "0":
                       if(form.P_C_EMAIL != undefined){
                         if(form.P_C_EMAIL.value != ""){
                           in_mail = true;
                         }
                       }
                       if(form.P_C_PMAIL != undefined){
                         if(form.P_C_PMAIL.value != ""){
                           in_mail = true;
                         }
                       }
                       if(form.P_C_FAX_1 != undefined){
                         if(form.P_C_FAX_1.value != ""){
                           in_fax = true;
                         }
                       }
                       sel_contact = "個人データ登録先";
                       focus_obj_mail = form.P_C_EMAIL;
                       focus_obj_fax = form.P_C_FAX_1;
                       break;
                     case "4":
                       if(form.G_EMAIL != undefined){
                         if(form.G_EMAIL.value != ""){
                           in_mail = true;
                         }
                       }
                       if(form.G_FAX_1 != undefined){
                         if(form.G_FAX_1.value != ""){
                           in_fax = true;
                         }
                       }
                       sel_contact = "組織データ登録先";
                       focus_obj_mail = form.G_EMAIL;
                       focus_obj_fax = form.G_FAX_1;
                       break;
                     case "5":
                       if(form.P_P_EMAIL != undefined){
                         if(form.P_P_EMAIL.value != ""){
                           in_mail = true;
                         }
                       }
                       if(form.P_P_FAX_1 != undefined){
                         if(form.P_P_FAX_1.value != ""){
                           in_fax = true;
                         }
                       }
                       sel_contact = "プライベート情報登録先";
                       focus_obj_mail = form.P_P_EMAIL;
                       focus_obj_fax = form.P_P_FAX_1;
                       break;
                     case "2":
                       if(form.M_CONTACT_EMAIL != undefined){
                         if(form.M_CONTACT_EMAIL.value != ""){
                           in_mail = true;
                         }
                       }
                       if(form.M_CO_C_FAX_1 != undefined){
                         if(form.M_CO_C_FAX_1.value != ""){
                           in_fax = true;
                         }
                       }
                       sel_contact = "新たに設定";
                       focus_obj_mail = form.M_CONTACT_EMAIL;
                       focus_obj_fax = form.M_CO_C_FAX_1;
                       break;
                  }
                  switch (form.M_CONTRACT_TYPE.value){
                     case "1":
                        if(in_mail == false && in_fax == true){
                           if(!confirm("メールアドレスが未入力です。\n" +
                                  "連絡手段を【FAX会員】に変更してもよろしいですか？\n\n" +
                                  "『は　い』　⇒　連絡手段を【FAX会員】に変更します\n" +
                                  "『いいえ』　⇒　連絡手段の変更は行いません")) {
                              if(focus_obj_mail != undefined){
                                if(focus_obj_mail.type != "hidden"){
                                  if(!confirm("連絡先指定【" + sel_contact +"】のメールアドレスが\n未入力ですがよろしいですか？")) {
                                      focus_obj_mail.focus();
                                      return;
                                  }
                                }
                              }
                           }else{
                              form.M_CONTRACT_TYPE.value = 2;
                           }
                        }else if(in_mail == false && in_fax == false){
                           if(!confirm("メールアドレス、FAX番号が未登録です。\n" +
                                     "連絡手段を【不明】に変更してもよろしいですか？\n\n" +
                                     "『は　い』　⇒　連絡手段を【不明】に変更します\n" +
                                     "『いいえ』　⇒　連絡手段の変更は行いません")) {
                              if(focus_obj_mail != undefined){
                                if(focus_obj_mail.type != "hidden"){
                                  if(!confirm("連絡先指定【" + sel_contact +"】のメールアドレスが\n未入力ですがよろしいですか？")) {
                                    errProc(focus_obj_mail);
                                    return;
                                  }
                                }
                              }
                           }else{
                              form.M_CONTRACT_TYPE.value = 0;
                           }
                        }else{
                        }
                        break;
                     case "2":
                       if(in_mail == true && in_fax == false){
                         if(!confirm("FAX番号が未入力です。\n" +
                                  "連絡手段を【メール会員】に変更してもよろしいですか？\n\n" +
                                  "『は　い』　⇒　連絡手段を【メール会員】に変更します\n" +
                                  "『いいえ』　⇒　連絡手段の変更は行いません")) {
                           if(focus_obj_fax != undefined){
                             if(focus_obj_fax.type != "hidden"){
                               if(!confirm("連絡先指定【" + sel_contact +"】のFAX番号が\n未入力ですがよろしいですか？")) {
                                 errProc(focus_obj_fax);
                                 return;
                               }
                             }
                           }
                         }else{
                           form.M_CONTRACT_TYPE.value = 1;
                         }
                       }else if(in_mail == true && in_fax == true){
                         if(confirm("メールアドレスが入力されています。\n" +
                                  "連絡手段を【メール会員】に変更してもよろしいですか？\n\n" +
                                  "『は　い』　⇒　連絡手段を【メール会員】に変更します\n" +
                                  "『いいえ』　⇒　連絡手段の変更は行いません")) {
                           form.M_CONTRACT_TYPE.value = 1;
                         }
                       }else if(in_mail == false && in_fax == false){
                         if(!confirm("メールアドレス、FAX番号が未入力です。\n" +
                                  "連絡手段を【不明】に変更してもよろしいですか？\n\n" +
                                  "『は　い』　⇒　連絡手段を【不明】に変更します\n" +
                                  "『いいえ』　⇒　連絡手段の変更は行いません")) {
                           if(focus_obj_fax != undefined){
                             if(focus_obj_fax.type != "hidden"){
                               if(!confirm("連絡先指定【" + sel_contact +"】のFAX番号が\n未入力ですがよろしいですか？")) {
                                 errProc(focus_obj_fax);
                                 return;
                               }
                             }
                           }
                         }else{
                           form.M_CONTRACT_TYPE.value = 0;
                         }
                        }
                     break;
                     default:
                          if(in_mail == true){
                            if(confirm("メールアドレスが入力されています。\n" +
                                     "連絡手段を【メール会員】に変更してもよろしいですか？\n\n" +
                                     "『は　い』　⇒　連絡手段を【メール会員】に変更します\n" +
                                     "『いいえ』　⇒　連絡手段の変更は行いません")) {
                              form.M_CONTRACT_TYPE.value = 1;
                            }
                          }else if(in_fax == true){
                            if(confirm("FAX番号が入力されています。\n" +
                                     "連絡手段を【FAX会員】に変更してもよろしいですか？\n\n" +
                                     "『は　い』　⇒　連絡手段を【FAX会員】に変更します\n" +
                                     "『いいえ』　⇒　連絡手段の変更は行いません")) {
                              form.M_CONTRACT_TYPE.value = 2;
                            }
                          }
                     break;
                  }
               }
            }
            if(!CheckInputData()) return;
            document.mainForm.M_CONTRACT_TYPE.disabled = false;
            document.mainForm.M_LG_G_ID_SEL.disabled = false;
            document.mainForm.M_LG_ID.disabled = false;
         }

         function OnUseRegisteredGid(){
         }


         function OnUseRegisteredPid(){
         }

         function ShowLowerGroupName(gid){
          var i;
          var obj = document.mainForm.M_LG_G_ID_SEL;
          if(obj.type == "hidden") return;
          for(i=0;i<obj.options.length;i++){
           if(obj.options[i].value==gid){
            obj.selectedIndex = i;
            break;
           }
          }
          if(i==obj.options.length){
           obj.selectedIndex = 0;
          }
          document.mainForm.M_LG_NAME.value = obj.options[obj.selectedIndex].text;
         }

         function OnMakeGidAuto(){
          document.mainForm.G_G_ID.value = GetMakeGid();
          OnBlurGid();
         }

         function OnMakePidAuto(){
         }

         function retSearchVal_MakePid(retSearchVal){
          if(retSearchVal == "Err"){
            alert("IDの採番に失敗しました。");
            return;
          }
          document.mainForm.P_P_ID.value = retSearchVal;
          OnBlurPid();
         }

         function OnMakeGidAuto2(){
         }

         function retSearchVal_MakeGid(retSearchVal){
          if(retSearchVal == "Err"){
            alert("IDの採番に失敗しました。");
            return;
          }
          document.mainForm.G_G_ID.value = retSearchVal;
          OnBlurGid();
         }


         function retSearchVal_CheckPidGid(retSearchVal,ArgVal){

          if(ArgVal > 0){
            OnMakeGidAuto2();
          }

          if(retSearchVal > 0){
            OnMakePidAuto();
          }
         }

         function SetData(){
          var form = document.mainForm;


          if(form.M_BILLING_ID != undefined){

           changeClaimForm();
          }
          if(form.M_CONTACT_ID != undefined){

           changeContactForm();
          }

          if(form.M_CLAIM_CLS != undefined){
           OnClaimClassChange(mainForm.M_CLAIM_CLS.value);
          }

          OnBankCodeChangeAll();


         }


         function OnZipCallbackG(zipcode, sta, addr1, addr2){
          var form = document.mainForm;
          form.G_POST_u.value = zipcode.split("-")[0];
          form.G_POST_l.value = zipcode.split("-")[1];
          form.G_STA.value = sta;
          form.G_ADR.value = addr1+addr2;
         }

         function OnZipCallbackP(zipcode, sta, addr1, addr2){
           var form = document.mainForm;
           form.P_C_POST_u.value = zipcode.split("-")[0];
           form.P_C_POST_l.value = zipcode.split("-")[1];
           form.P_C_STA.value = sta;
           form.P_C_ADR.value = addr1+addr2;
         }

         function OnZipCallbackPP(zipcode, sta, addr1, addr2){
          var form = document.mainForm;
          form.P_P_POST_u.value = zipcode.split("-")[0];
          form.P_P_POST_l.value = zipcode.split("-")[1];
          form.P_P_STA.value = sta;
          form.P_P_ADR.value = addr1+addr2;
         }

         function OnSearchLowerGroup(post_id, page_link){
          buf = page_link+'post_id='+post_id;
            gToolWnd = open(buf,
            'DetailWnd',
            'width=1000,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }

         function OnSearchCategory(post_id, page_link){
            var form = document.mainForm;
            var key = form.G_INDUSTRY_CD.value;
            buf = page_link+'post_id='+post_id;
            gToolWnd = open(buf,
            'DetailWnd',
            'width=1000,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }

         function OnSearchBankG(post_id, page_link){
            var value = document.mainForm.G_BANK_CD.value;
            var vCode = value.split("-+_+-")[0];
            var vName = value.split("-+_+-")[1];
           if(vCode == ''){
             alert('銀行コードを選択してください。');
             return;
           }
           buf = page_link+'sel=G_BANK_CD&bank_code='+vCode+'&bank_name='+vName+'&post_id='+post_id;
           gToolWnd = open(buf,
            'DetailWnd',
            'width=1000,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }

          function OnSearchBankP(post_id, page_link){
            var value = document.mainForm.P_BANK_CD.value;
            var vCode = value.split("-+_+-")[0];
            var vName = value.split("-+_+-")[1];
            if(vCode == ''){
             alert('銀行コードを選択してください。');
             return;
            }
           buf = page_link+'sel=P_BANK_CD&bank_code='+vCode+'&bank_name='+vName+'&post_id='+post_id;
           gToolWnd = open(buf,
            'DetailWnd',
            'width=1000,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }

         function OnSearchBankM(post_id, page_link){
            var value = document.mainForm.M_BANK_CD.value;
            var vCode = value.split("-+_+-")[0];
            var vName = value.split("-+_+-")[1];
            if(vCode == ''){
              alert('銀行コードを選択してください。');
              return;
            }
           buf = page_link+'sel=M_BANK_CD&bank_code='+vCode+'&bank_name='+vName+'&post_id='+post_id;
           gToolWnd = open(buf,
            'DetailWnd',
            'width=1000,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }


         function CheckNeed(elem, bCheck){
          var i;
          var buf;
          var tag;

          if(bCheck){
           for(i=0;i<elem.length;i++){

            elem[i].innerHTML = '<span style="color: #FF0000">※</span>' + elem[i].innerHTML;
           }

          }else{
           for(i=0;i<elem.length;i++){

            buf = elem[i].innerHTML;
            buf = buf.toUpperCase();

            tag = '<span style="color: #FF0000">※</span>';
            tag = tag.toUpperCase();

            buf = buf.replace(new RegExp(tag, "g"), "");
            tag = tag.toUpperCase();

            buf = buf.replace(new RegExp(tag, "g"), "");

            elem[i].innerHTML = buf;
           }
          }
         }
         function OnContactDestChangeRadio(val,msg){
          var form = document.mainForm;
          form.M_CONTACT_ID.value = val;

           changeContactForm(true);
         }

         function OnClaimDestChangeRadio(val,msg){
          var form = document.mainForm;
          form.M_BILLING_ID.value = val;

           changeClaimForm(true);
         }

         function OnExplanationFile(url){
           gToolWnd = window.open(url,
            'SearchWnd',
            'width=1000,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
         }


         function IsContactSame(val){
          var form = document.mainForm;

          switch (val) {

          case "0":
           if(form.M_CONTACT_G_NAME != undefined){
             if(form.M_CONTACT_G_NAME.value     != form.G_NAME.value)       return false;
           }
           if(form.M_CONTACT_G_NAME_KN != undefined){
             if(form.M_CONTACT_G_NAME_KN.value     != form.G_NAME_KN.value)       return false;
           }
           if(form.M_CONTACT_C_NAME.value     != form.P_C_NAME.value)       return false;
           if(form.M_CONTACT_C_NAME_KN.value     != form.P_C_KANA.value)       return false;
             if(form.M_CONTACT_AFFILIATION != undefined){
               if(form.M_CONTACT_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
             }
             if(form.M_CONTACT_POSITION != undefined){
               if(form.M_CONTACT_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
             }
           if(form.M_CONTACT_EMAIL.value    != form.P_C_EMAIL.value)      return false;
           if(form.M_CONTACT_CC_EMAIL != undefined){
             if(form.M_CONTACT_CC_EMAIL.value != form.P_C_CC_EMAIL.value)   return false;
           }
           if(form.M_CONTACT_TEL.value      != form.P_C_TEL.value)        return false;
           if(form.M_CO_C_TEL_1.value    != form.P_C_TEL_1.value)      return false;
           if(form.M_CO_C_TEL_2.value    != form.P_C_TEL_2.value)      return false;
           if(form.M_CO_C_TEL_3.value    != form.P_C_TEL_3.value)      return false;
           if(form.M_CONTACT_FAX.value      != form.P_C_FAX.value)        return false;
           if(form.M_CO_C_FAX_1.value    != form.P_C_FAX_1.value)      return false;
           if(form.M_CO_C_FAX_2.value    != form.P_C_FAX_2.value)      return false;
           if(form.M_CO_C_FAX_3.value    != form.P_C_FAX_3.value)      return false;
           if(form.M_CONTACT_POST.value     != form.P_C_POST.value)       return false;
           if(form.M_CO_C_POST_u.value   != form.P_C_POST_u.value)     return false;
           if(form.M_CO_C_POST_l.value   != form.P_C_POST_l.value)     return false;
           if(form.M_CONTACT_STA.value      != form.P_C_STA.value)        return false;
           if(form.M_CONTACT_ADR.value  != form.P_C_ADR.value)    return false;
           if(form.M_CONTACT_ADR2.value != form.P_C_ADR2.value)   return false;
           return true;

          case "2":
            return true;

          case "4":
           if(form.M_CONTACT_G_NAME != undefined){
             if(form.M_CONTACT_G_NAME.value      != form.G_NAME.value)          return false;
           }
           if(form.M_CONTACT_G_NAME_KN != undefined){
             if(form.M_CONTACT_G_NAME_KN.value      != form.G_NAME_KN.value)          return false;
           }
           if(form.M_CONTACT_C_NAME.value      != form.P_C_NAME.value)        return false;
           if(form.M_CONTACT_C_NAME_KN.value      != form.P_C_KANA.value)        return false;

             if(form.M_CONTACT_AFFILIATION != undefined){
               if(form.M_CONTACT_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
             }
             if(form.M_CONTACT_POSITION != undefined){
               if(form.M_CONTACT_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
             }

           if(form.M_CONTACT_EMAIL.value     != form.G_EMAIL.value)       return false;
           if(form.M_CONTACT_CC_EMAIL != undefined){
             if(form.M_CONTACT_CC_EMAIL.value  != form.G_CC_EMAIL.value)    return false;
           }
           if(form.M_CONTACT_TEL.value       != form.G_TEL.value)         return false;
           if(form.M_CO_C_TEL_1.value     != form.G_TEL_1.value)       return false;
           if(form.M_CO_C_TEL_2.value     != form.G_TEL_2.value)       return false;
           if(form.M_CO_C_TEL_3.value     != form.G_TEL_3.value)       return false;
           if(form.M_CONTACT_FAX.value       != form.G_FAX.value)         return false;
           if(form.M_CO_C_FAX_1.value     != form.G_FAX_1.value)       return false;
           if(form.M_CO_C_FAX_2.value     != form.G_FAX_2.value)       return false;
           if(form.M_CO_C_FAX_3.value     != form.G_FAX_3.value)       return false;
           if(form.M_CONTACT_POST.value      != form.G_POST.value)        return false;
           if(form.M_CO_C_POST_u.value    != form.G_POST_u.value)      return false;
           if(form.M_CO_C_POST_l.value    != form.G_POST_l.value)      return false;
           if(form.M_CONTACT_STA.value       != form.G_STA.value)         return false;
           if(form.M_CONTACT_ADR.value   != form.G_ADDRESS.value)     return false;
           if(form.M_CONTACT_ADR2.value  != form.G_ADDRESS2.value)    return false;
           return true;

          case "5":
           if(form.M_CONTACT_G_NAME != undefined){
             if(form.M_CONTACT_G_NAME.value      != "")      return false;
           }
           if(form.M_CONTACT_G_NAME_KN != undefined){
             if(form.M_CONTACT_G_NAME_KN.value      != "")      return false;
           }
           if(form.M_CONTACT_C_NAME.value      != form.P_C_NAME.value)      return false;
           if(form.M_CONTACT_C_NAME_KN.value      != form.P_C_KANA.value)      return false;

           if(form.M_CONTACT_AFFILIATION != undefined){
             if(form.M_CONTACT_AFFILIATION.value != "") return false;
           }
           if(form.M_CONTACT_POSITION != undefined){
             if(form.M_CONTACT_POSITION.value    != "") return false;
           }

           if(form.M_CONTACT_EMAIL.value     != form.P_P_EMAIL.value)     return false;
           if(form.M_CONTACT_CC_EMAIL != undefined){
             if(form.M_CONTACT_CC_EMAIL.value  != form.P_P_CC_EMAIL.value)  return false;
           }
           if(form.M_CONTACT_TEL.value       != form.P_P_TEL.value)       return false;
           if(form.M_CO_C_TEL_1.value     != form.P_P_TEL_1.value)     return false;
           if(form.M_CO_C_TEL_2.value     != form.P_P_TEL_2.value)     return false;
           if(form.M_CO_C_TEL_3.value     != form.P_P_TEL_3.value)     return false;
           if(form.M_CONTACT_FAX.value       != form.P_P_FAX.value)       return false;
           if(form.M_CO_C_FAX_1.value     != form.P_P_FAX_1.value)     return false;
           if(form.M_CO_C_FAX_2.value     != form.P_P_FAX_2.value)     return false;
           if(form.M_CO_C_FAX_3.value     != form.P_P_FAX_3.value)     return false;
           if(form.M_CONTACT_POST.value      != form.P_P_POST.value)      return false;
           if(form.M_CO_C_POST_u.value    != form.P_P_POST_u.value)    return false;
           if(form.M_CO_C_POST_l.value    != form.P_P_POST_l.value)    return false;
           if(form.M_CONTACT_STA.value       != form.P_P_STA.value)       return false;
           if(form.M_CONTACT_ADR.value   != form.P_P_ADR.value)   return false;
           if(form.M_CONTACT_ADR2.value  != form.P_P_ADR2.value)  return false;
           return true;
          default:
           return false;
          }
         }

         function IsClaimDestSame(val){
          var form = document.mainForm;

          switch (val) {

          case "0":
           if(form.M_BILLING_G_NAME != undefined){
             if(form.M_BILLING_G_NAME.value     != form.G_NAME.value)       return false;
           }
           if(form.M_BILLING_G_KANA != undefined){
             if(form.M_BILLING_G_KANA.value     != form.G_NAME_KN.value)       return false;
           }
           if(form.M_BILLING_G_NAME_EN != undefined){
             if(form.M_BILLING_G_NAME_EN.value     != form.G_NAME_EN.value)       return false;
           }
           if(form.M_BILLING_C_NAME.value     != form.P_C_NAME.value)       return false;
           if(form.M_BILLING_C_NAME_KN.value     != form.P_C_KANA.value)       return false;

           if(form.M_BILLING_AFFILIATION != undefined){
             if(form.M_BILLING_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
           }
           if(form.M_CONTACT_POSITION != undefined){
             if(form.M_BILLING_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
           }

           if(form.M_BILLING_EMAIL.value    != form.P_C_EMAIL.value)      return false;
           if(form.M_BILLING_CC_EMAIL != undefined){
             if(form.M_BILLING_CC_EMAIL.value != form.P_C_CC_EMAIL.value)   return false;
           }
           if(form.M_BILLING_TEL.value      != form.P_C_TEL.value)        return false;
           if(form.M_CL_C_TEL_1.value    != form.P_C_TEL_1.value)      return false;
           if(form.M_CL_C_TEL_2.value    != form.P_C_TEL_2.value)      return false;
           if(form.M_CL_C_TEL_3.value    != form.P_C_TEL_3.value)      return false;
           if(form.M_BILLING_FAX.value      != form.P_C_FAX.value)        return false;
           if(form.M_CL_C_FAX_1.value    != form.P_C_FAX_1.value)      return false;
           if(form.M_CL_C_FAX_2.value    != form.P_C_FAX_2.value)      return false;
           if(form.M_CL_C_FAX_3.value    != form.P_C_FAX_3.value)      return false;
           if(form.M_BILLING_POST.value     != form.P_C_POST.value)       return false;
           if(form.M_CL_C_POST_u.value   != form.P_C_POST_u.value)     return false;
           if(form.M_CL_C_POST_l.value   != form.P_C_POST_l.value)     return false;
           if(form.M_BILLING_STA.value      != form.P_C_STA.value)        return false;
           if(form.M_BILLING_ADR.value  != form.P_C_ADR.value)    return false;
           if(form.M_BILLING_ADR2.value != form.P_C_ADR2.value)   return false;
           return true;

          case "2":
           return true;

          case "4":
           if(form.M_BILLING_G_NAME != undefined){
             if(form.M_BILLING_G_NAME.value      != form.G_NAME.value)        return false;
           }
           if(form.M_BILLING_G_KANA != undefined){
             if(form.M_BILLING_G_KANA.value      != form.G_NAME_KN.value)        return false;
           }
           if(form.M_BILLING_G_NAME_EN != undefined){
             if(form.M_BILLING_G_NAME_EN.value      != form.G_NAME_EN.value)        return false;
           }
           if(form.M_BILLING_C_NAME.value      != form.P_C_NAME.value)        return false;
           if(form.M_BILLING_C_NAME_KN.value      != form.P_C_KANA.value)        return false;

           if(form.M_BILLING_AFFILIATION != undefined){
             if(form.M_BILLING_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
           }
           if(form.M_CONTACT_POSITION != undefined){
             if(form.M_BILLING_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
           }

           if(form.M_BILLING_EMAIL.value     != form.G_EMAIL.value)       return false;
           if(form.M_BILLING_CC_EMAIL != undefined){
             if(form.M_BILLING_CC_EMAIL.value  != form.G_CC_EMAIL.value)    return false;
           }
           if(form.M_BILLING_TEL.value       != form.G_TEL.value)         return false;
           if(form.M_CL_C_TEL_1.value     != form.G_TEL_1.value)       return false;
           if(form.M_CL_C_TEL_2.value     != form.G_TEL_2.value)       return false;
           if(form.M_CL_C_TEL_3.value     != form.G_TEL_3.value)       return false;
           if(form.M_BILLING_FAX.value       != form.G_FAX.value)         return false;
           if(form.M_CL_C_FAX_1.value     != form.G_FAX_1.value)       return false;
           if(form.M_CL_C_FAX_2.value     != form.G_FAX_2.value)       return false;
           if(form.M_CL_C_FAX_3.value     != form.G_FAX_3.value)       return false;
           if(form.M_BILLING_POST.value      != form.G_POST.value)        return false;
           if(form.M_CL_C_POST_u.value    != form.G_POST_u.value)      return false;
           if(form.M_CL_C_POST_l.value    != form.G_POST_l.value)      return false;
           if(form.M_BILLING_STA.value       != form.G_STA.value)         return false;
           if(form.M_BILLING_ADR.value   != form.G_ADDRESS.value)     return false;
           if(form.M_BILLING_ADR2.value  != form.G_ADDRESS2.value)    return false;
           return true;

          case "5":
           if(form.M_BILLING_G_NAME != undefined){
             if(form.M_BILLING_G_NAME.value      != "")      return false;
           }
           if(form.M_BILLING_G_KANA != undefined){
             if(form.M_BILLING_G_KANA.value      != "")      return false;
           }
           if(form.M_BILLING_G_NAME_EN != undefined){
             if(form.M_BILLING_G_NAME_EN.value      != "")      return false;
           }
           if(form.M_BILLING_C_NAME.value      != form.P_C_NAME.value)      return false;
           if(form.M_BILLING_C_NAME_KN.value      != form.P_C_KANA.value)      return false;

           if(form.M_BILLING_AFFILIATION != undefined){
             if(form.M_BILLING_AFFILIATION.value != "")  return false;
           }
           if(form.M_CONTACT_POSITION != undefined){
             if(form.M_BILLING_POSITION.value    != "") return false;
           }

           if(form.M_BILLING_EMAIL.value     != form.P_P_EMAIL.value)     return false;
           if(form.M_BILLING_CC_EMAIL != undefined){
             if(form.M_BILLING_CC_EMAIL.value  != form.P_P_CC_EMAIL.value)  return false;
           }
           if(form.M_BILLING_TEL.value       != form.P_P_TEL.value)       return false;
           if(form.M_CL_C_TEL_1.value     != form.P_P_TEL_1.value)     return false;
           if(form.M_CL_C_TEL_2.value     != form.P_P_TEL_2.value)     return false;
           if(form.M_CL_C_TEL_3.value     != form.P_P_TEL_3.value)     return false;
           if(form.M_BILLING_FAX.value       != form.P_P_FAX.value)       return false;
           if(form.M_CL_C_FAX_1.value     != form.P_P_FAX_1.value)     return false;
           if(form.M_CL_C_FAX_2.value     != form.P_P_FAX_2.value)     return false;
           if(form.M_CL_C_FAX_3.value     != form.P_P_FAX_3.value)     return false;
           if(form.M_BILLING_POST.value      != form.P_P_POST.value)      return false;
           if(form.M_CL_C_POST_u.value    != form.P_P_POST_u.value)    return false;
           if(form.M_CL_C_POST_l.value    != form.P_P_POST_l.value)    return false;
           if(form.M_BILLING_STA.value       != form.P_P_STA.value)       return false;
           if(form.M_BILLING_ADR.value   != form.P_P_ADR.value)   return false;
           if(form.M_BILLING_ADR2.value  != form.P_P_ADR2.value)  return false;
           return true;
          default:
           return false;
          }
         }

         function OnClaimClassChange(val){

          CheckNeed(m_mainFeeInfo, false);

          if(val==""){
           CheckNeed(m_mainFeeInfo, false);

          }else{
           CheckNeed(m_mainFeeInfo, true);
          }

          if(val=="0"){
           CheckNeed(m_claimClassNeed, true);

          }else{
           CheckNeed(m_claimClassNeed, false);
          }
         }

         function OnBankCodeChangeAll(){
          var bankCode;
          var mrr;
          var res;
          var i, j;
          var buf;
          var list;
          var bank, branch;
          var form = document.mainForm;

          bankCode = "";
          for (j=1; j<=3; j++) {
           bank = "";
           switch (j) {
            case 1:
             if(form.M_BANK_CD != undefined && form.M_BRANCH_CD != undefined){
              bank = form.M_BANK_CD;
              branch = form.M_BRANCH_CD;
             }
             break;
            case 2:
             bankCode = bankCode + ",,";
             if(form.P_BANK_CD != undefined && form.P_BRANCH_CD != undefined){
              bank = form.P_BANK_CD;
              branch = form.P_BRANCH_CD;
             }
             break;
            case 3:
             bankCode = bankCode + ",,";
             if(form.G_BANK_CD != undefined && form.G_BRANCH_CD != undefined){
              bank = form.G_BANK_CD;
              branch = form.G_BRANCH_CD;
             }
             break;
           }
           if(bank != undefined && branch != undefined){
              if (bank == "") {
              } else {

                if(bank.type == "hidden") {

                } else if(bank.selectedIndex < 0) {
                branch.length           = 0;
                branch.length           = 1;
                  if(branch.options != undefined){
                    branch.options[0].value = "";
                    branch.options[0].text  = "";
                  }
                } else {
                  bankCode = bankCode + bank.options[bank.selectedIndex].value;

                  if(bank.options[bank.selectedIndex].value==""){
                  branch.length           = 0;
                  branch.length           = 1;
                    if(branch.options != undefined){
                      branch.options[0].value = "";
                      branch.options[0].text  = "";
                    }
                  }
                }
              }
           }
          }

          if(bankCode.replace(',,', '')==""){
           branch.length           = 0;
           branch.length           = 1;
            if(branch.options != undefined){
              branch.options[0].value = "";
              branch.options[0].text  = "";
            }
           return;
          }
         }


         function OnBankCodeChange(bank, branch, ArgVal, post_id){
            var bankCode; 
            var mrr;      
            var res;      
            var i;        
            var buf;      
            var list;     
            if(bank.type == "hidden") return;
            
            if(bank.selectedIndex < 0) return;
            bankCode = bank.options[bank.selectedIndex].value;
            var vCode = bankCode.split("-+_+-")[0];
            
            var name = branch.name;
            $.ajax({
               url: '<?php echo get_home_url(); ?>/wp-admin/admin-ajax.php',
               type : "POST",
               data: {
                  action: 'rs_get_branch_list',
                  bankCode: vCode,
                  tg_id: "<?php echo get_option('nakama-member-group-id'); ?>",
                  post_id: post_id,
               },
               success: function(response) {
                  var obj = JSON.parse(response);
                  var html = '';
                  html += '<option value=""></option>';
                  $.each(obj, function (i, item) {
                     html += "<option value='"+item.BRANCH_CD+"'>"+item.BRANCH_CD+" "+item.BRANCH_NM+"</option>";
                  });
                  $("select[name='"+name+"']").html(html);
               },
               error: function() {}
            });
         }

         function retSearchVal_GetBranchListAll2(retSearchVal, ArgVal){
           var i, j;
           var buf;
           var list;
           var bank, branch;
           var sBranchCode;
           var form = document.mainForm;

           retSearchVal = retSearchVal.split(',,');

           for (j=1; j<=3; j++) {
             switch (j) {
             case 1:
               if (form.S_BRANCH_CODE.value == ''){
                 sBranchCode = "";
               }else{
                 sBranchCode = form.S_BRANCH_CODE.value;
               }
                bank = form.M_BANK_CD;
                branch = form.M_BRANCH_CD;
               break;

             case 2:
               if (form.S_BRANCH_CODE2.value == ''){
                 sBranchCode = "";
               }else{
                 sBranchCode = form.S_BRANCH_CODE2.value;
               }
                bank = form.P_BANK_CD;
                branch = form.P_BRANCH_CD;
               break;

             case 3:
               if (form.S_BRANCH_CODE3.value == ''){
                 sBranchCode = "";
               }else{
                 sBranchCode = form.S_BRANCH_CODE3.value;
               }
                bank = form.G_BANK_CD;
                branch = form.G_BRANCH_CD;
               break;
             }
             if(bank != undefined){
              if(bank.type == "hidden") {

              } else {
                list = retSearchVal[j-1].split("&");
                branch.length = 0;
                branch.length = list.length;
                if (list[0] != "") branch.length++;
                branch.options[0].value = "";
                branch.options[0].text  = "";
                for (i = 1; i < branch.length; i++) {
                  if (list[i - 1] == "") continue;
                  buf = list[i - 1].split("=");
                  branch.options[i].value = buf[0];
                  branch.options[i].text  = buf[0] + " " + buf[1];

                  if (branch.options[i].value == sBranchCode) {
                    branch.selectedIndex = i;
                  }
                }
              }
             }
           }
         }

         function retSearchVal_GetBranchList(retSearchVal, ArgVal){
           var i;
           var buf;
           var list;
           var bank, branch;
           var form = document.mainForm;
           var sBranchCode;

           if (form.S_BRANCH_CODE.value == ''){
             sBranchCode = "";
           }else{
             sBranchCode = form.S_BRANCH_CODE.value;
           }

           switch (ArgVal) {
           case "2":
             bank = form.P_BANK_CD;
             branch = form.P_BRANCH_CD;
             break;
           case "3":
             bank = form.G_BANK_CD;
             branch = form.G_BRANCH_CD;
             break;
           default:
             bank = form.M_BANK_CD;
             branch = form.M_BRANCH_CD;
           }

           list = retSearchVal.split("&");
           branch.length = 0;
           branch.length = list.length;
           if (list[0] != "") branch.length++;
           branch.options[0].value = "";
           branch.options[0].text  = "";
           for (i = 1; i < branch.length; i++) {
             if (list[i - 1] == "") continue;
             buf = list[i - 1].split("=");
             branch.options[i].value = buf[0];
             branch.options[i].text  = buf[0] + " " + buf[1];

             if (branch.options[i].value == sBranchCode) {
               branch.selectedIndex = i;
             }
           }

         }

         function SetToday(objY, objM, objD){
           var t = new Date();
           var y;
           y = t.getYear()
           if (y < 2000) { y += 1900; }
           objY.value = y;
           objM.value = ("00" + (t.getMonth() + 1)).slice(-2);
           objD.value = ("00" + t.getDate()).slice(-2);
         }

         function OnZipCallbackCl(zipcode, sta, addr1, addr2){
           var form = document.mainForm;
           form.M_CL_C_POST_u.value = zipcode.split("-")[0];
           form.M_CL_C_POST_l.value = zipcode.split("-")[1];
           form.M_BILLING_STA.value = sta;
           form.M_BILLING_ADR.value = addr1;
           form.M_BILLING_ADR2.value = addr2;
         }

         function OnZipCallbackCo(zipcode, sta, addr1, addr2){
           var form = document.mainForm;
           form.M_CO_C_POST_u.value = zipcode.split("-")[0];
           form.M_CO_C_POST_l.value = zipcode.split("-")[1];
           form.M_CONTACT_STA.value = sta;
           form.M_CONTACT_ADR.value = addr1;
           form.M_CONTACT_ADR2.value = addr2;
         }
         function OnFeeStatus(postid, page_link){
          var form = document.mainForm;
          var tg_id = form.feestatus_tg_id.value;
          var p_id = form.feestatus_p_id.value;
          var c_name = form.feestatus_c_name.value;
           buf = page_link+'tg_id='+tg_id+'&p_id='+p_id+'&c_name='+c_name+"&post_id="+postid;
           gToolWnd = open(buf,
            'DetailWnd',
            'width=1000,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }
         function ShowImage(url){
           location.href = "./window/ImageView.asp?img=" + url;
         }
         function OnCheck(p_id){
           var pmail;
           var form = document.mainForm;
           pmail = form.P_C_PMAIL.value;
           if(pmail != ''){
             if(!confirm("携帯簡易ログインURLを送信します。\nよろしいですか？")){
               return false;
             }
             alert('携帯簡易ログインURLを送信しました。');
           }
         }


         function change_listnodisp(val){
          var form=document.mainForm;
          if(val == "1"){
           if(form.G_O_NAME != undefined){
             form.G_O_NAME.value = "1";
           }
           if(form.G_O_KANA != undefined){
             form.G_O_KANA.value = "1";
           }
          }else{
           if(form.G_O_NAME != undefined){
             form.G_O_NAME.value = "2";
           }
           if(form.G_O_KANA != undefined){
             form.G_O_KANA.value = "2";
           }
          }
         }


         function RegistFelicaId(p_id){
           location.href = "regist_felicaid.asp?p_id=" + p_id;
         }

         function ActivePointDetail(gid,pid){
           gToolWnd = window.open('./ActivePointDetail.asp?gid=' + gid + '&pid=' + pid,'DetailWnd',
             'width=950,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
         }

         //個人パスワード表示
         function chgMastPPassword(){
           var form = document.mainForm;
           var tmpVal1, tmpVal2;
           if(form.P_PASSWORD != undefined){
             if(form.P_PASSWORD.type != "hidden"){
               tmpVal1 = form.P_PASSWORD.value;
               tmpVal2 = form.P_PASSWORD2.value;
               if(form.P_PASSWORD.type != "text"){
                 $('input[name="P_PASSWORD"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="text" name="P_PASSWORD" value="' + tmpVal1 + '">');
                 $('input[name="P_PASSWORD2"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="text" name="P_PASSWORD2" value="' + tmpVal2 + '">');
                 form.dispPPassBtn.value = "パスワード非表示";
               }else{
                 $('input[name="P_PASSWORD"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="password" name="P_PASSWORD" value="' + tmpVal1 + '">');
                 $('input[name="P_PASSWORD2"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="password" name="P_PASSWORD2" value="' + tmpVal2 + '">');
                 form.dispPPassBtn.value = "パスワード表示";
               }
             }
           }
         }


         function CheckFrameParent(objname) {
           var objKey = document.getElementsByName(objname);
           if (objKey[0].checked == false) {
             for(var i = 1; i < objKey.length; i++) {
               objKey[i].checked = false;
             }
           }
         }


         function CheckFrameChild(objname) {
           var objKey = document.getElementsByName(objname);
           var flg = false;
           for(var i = 1; i < objKey.length; i++) {
             if (objKey[i].checked) {
               flg = true;
               break;
             }
           }
           objKey[0].checked = flg;
         }
         </script>
    </head>
  <body onUnload="OnUnload();" onLoad="OnLoad();">
    <div class="container regist_page">
    	<div class="content_header_file">
        <?php 
        $class = array();
        $class['table'] = "input_table";
        $class['tdHead'] = "RegField";
        $class['tdOne'] = "RegGroup";
        $class['tdSecond'] = "RegItem_add";
        $class['tdThird'] = "RegValue";
        $class['tdFour'] = "RegItem"; ?>
        <?php
        if(empty($header_file)){
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/entry_header_reg.inc");
        } else {
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/".$header_file);
        }
        ?>
    	</div>
        
        <form name="mainForm" id="mainForm" enctype="multipart/form-data" method="post" class="mt-15" autocomplete="off">
          <?php
            $merumaga_flg = $dataSetting['merumaga_flg'][0]; 
            $merumaga_file = $dataSetting['disp_merumaga_file'][0];
            $tg_id = $dataSetting['top_g_id'];
            $gInputOpen = $dataSetting['input_open'];
            if($merumaga_flg == "2"):
              if(file_exists(__ROOT__."/settingform/inc/".$merumaga_file)): ?>
                <center>
                  <b><font size="4">
                  同意確認:
                  <input type="radio" name="merumaga_flg" value="1">する&nbsp;
                  <input type="radio" name="merumaga_flg" value="0" checked>しない
                  </font size="5"></b>
                </center>
              <?php 
              endif;
            endif; ?>
          <div class="full-w">
            <div class="w-note left">
              <p class="note">
                ※マークの項目は必須入力です。
              </p>
            </div>
            <ul class="list-button right">
              <?php if ($disp_FeeStatus == 1): ?>
              <li>
                <?php $page_link = $members->getPageSlug('nakama-member-feestatus');?>
                <input type="button" name="" value="会費支払状況" onclick="OnFeeStatus(<?php echo $postid; ?>, '<?php echo $page_link; ?>');">
              </li>
              <?php endif; ?>
            </ul>
          </div>
          <?php $members->ShowAllData($file_ini, 'div_regist', true, $args['id']); ?>
          <script>
            var arrThreeInput = [
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'組織ＴＥＬ')), '組織ＴＥＬ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'組織ＦＡＸ')), '組織ＦＡＸ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'個人ＴＥＬ')), '個人ＴＥＬ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'個人ＦＡＸ')), '個人ＦＡＸ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'個人携帯ＴＥＬ')), '個人携帯ＴＥＬ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'プライベート　ＴＥＬ')), 'プライベート　ＴＥＬ'); ?>',
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'プライベート　ＦＡＸ')), 'プライベート　ＦＡＸ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'プライベート　ＴＥＬ')), 'プライベート　ＴＥＬ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'請求先ＴＥＬ')), '請求先ＴＥＬ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'請求先ＦＡＸ')), '請求先ＦＡＸ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'連絡先ＴＥＬ')), '連絡先ＴＥＬ'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'連絡先ＦＡＸ')), '連絡先ＦＡＸ'); ?>'];
            var arrTwoInput = [
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'組織〒')), '組織〒'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'個人〒')), '個人〒'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'郵便番号')), '郵便番号'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'請求先〒')), '請求先〒'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'連絡先〒')), '連絡先〒'); ?>'];
            var arrGetFromHidden = ['<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'ＦＡＸ送信時間帯')), 'ＦＡＸ送信時間帯'); ?>'];
            var arrDatetime = [
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'生年月日')), '生年月日'); ?>',
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'設立年月日')), '設立年月日'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'喪中開始年月日')), '喪中開始年月日'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'喪中終了年月日')), '喪中終了年月日'); ?>',
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'入会年月日')), '入会年月日'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'退会年月日')), '退会年月日'); ?>', 
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'異動年月日')), '異動年月日'); ?>',
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'転出年月日')), '転出年月日'); ?>',
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'転入年月日')), '転入年月日'); ?>'];
            var arrSelectandInput = ['<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'グループID')), 'グループID'); ?>'];
            var arrInputRadio = ['<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'個人自由項目１')), '個人自由項目１'); ?>'];
            var arrRadioGetLabel = [
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'メルマガ登録設定')), 'メルマガ登録設定'); ?>',
              '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'会議室氏名表示区分')), '会議室氏名表示区分'); ?>'];
            var label_P_P_BIRTH = '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'生年月日')), '生年月日'); ?>';
            var label_G_FOUND_DATE = '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'設立年月日')), '設立年月日'); ?>';
            var label_M_LG_NAME = '<?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($GLOBALS['arTitle'],'グループID')), 'グループID'); ?>';
          </script>
          <input type="hidden" name="arrElement" value="">
          <input type="hidden" name="arrElementBefore" value="">
          <input type="hidden" name="check_input_open" value="<?php echo !empty($gInputOpen) ? $gInputOpen[0] : null ; ?>">
          <input type="checkbox" name="useRegisteredGid" style="display:none;">
          <input type="checkbox" name="useRegisteredPid" style="display:none;">
          <input type="button"   name="useGroupInfo"     style="display:none;">
          <input type="hidden" name="flgFee"          value="<?php echo $members->fGetFeeData($args['id'], get_post_meta($args['id'],'top_g_id',true));?>">
          <input type="hidden" name="k_top_gid"       value="">
          <input type="hidden" name="k_gid"           value="">
          <input type="hidden" name="k_pid"           value="">
          <input type="hidden" name="strToday"        value="<?php echo $members->editDate(date("Y-m-d H:i:s"),1); ?>">
          <input type="hidden" name="m_lg_g_id_old"   value="<?php echo (isset($_GET['m_reinput']) == 1)?$_GET['m_lg_g_id_old']:""; ?>">
          <input type="hidden" name="set_lg_g_id"     value="<?php echo get_post_meta($args['id'],'set_lg_g_id',true);?>">
          <input type="hidden" name="set_g_id"        value="<?php echo get_post_meta($args['id'],'set_g_id',true);?>">
          <input type="hidden" name="m_chg"           value="<?php echo (isset($_GET['g_chg']))?$_GET['g_chg']:''; ?>">
          <input type="hidden" name="forward_mail"    value="">
          <input type="hidden" name="release"         value="">
          <input type="hidden" name="mail_flg"        value="">
          <input type="hidden" name="g_g_id_old"      value="">
          <input type="hidden" name="page_no"         value="">
          <input type="hidden" name="S_BRANCH_CODE"   value="">
          <input type="hidden" name="S_BRANCH_CODE2"  value="">
          <input type="hidden" name="S_BRANCH_CODE3"  value="">
          <input type="hidden" name="df_co_email"     value="">
          <input type="hidden" name="mobile_flg"      value="">
          <input type="hidden" name="elist"           value="">
          <input type="hidden" name="ActiveRf"        value="">
          <input type="hidden" name="NoneRMf"         value="">
          <input type="hidden" name="clNew_M_CL_G_NAME"         value="">
          <input type="hidden" name="clNew_M_CL_G_KANA"         value="">
          <input type="hidden" name="clNew_M_CL_C_NAME"         value="">
          <input type="hidden" name="clNew_M_CL_C_KANA"         value="">
          <input type="hidden" name="clNew_M_CL_C_AFFILIATION"  value="">
          <input type="hidden" name="clNew_M_CL_C_POSITION"     value="">
          <input type="hidden" name="clNew_M_CL_C_EMAIL"        value="">
          <input type="hidden" name="clNew_M_CL_C_CC_EMAIL"     value="">
          <input type="hidden" name="clNew_M_CL_C_TEL"          value="">
          <input type="hidden" name="clNew_M_CL_C_TEL_1"        value="">
          <input type="hidden" name="clNew_M_CL_C_TEL_2"        value="">
          <input type="hidden" name="clNew_M_CL_C_TEL_3"        value="">
          <input type="hidden" name="clNew_M_CL_C_FAX"          value="">
          <input type="hidden" name="clNew_M_CL_C_FAX_1"        value="">
          <input type="hidden" name="clNew_M_CL_C_FAX_2"        value="">
          <input type="hidden" name="clNew_M_CL_C_FAX_3"        value="">
          <input type="hidden" name="clNew_M_CL_C_POST"         value="">
          <input type="hidden" name="clNew_M_CL_C_POST_u"       value="">
          <input type="hidden" name="clNew_M_CL_C_POST_l"       value="">
          <input type="hidden" name="clNew_M_CL_C_STA"          value="">
          <input type="hidden" name="clNew_M_CL_C_ADDRESS"      value="">
          <input type="hidden" name="clNew_M_CL_C_ADDRESS2"     value="">
          <input type="hidden" name="clNew_M_ACCAUNT_TYPE"      value="">
          <input type="hidden" name="clNew_M_ACCAUNT_NUMBER"    value="">
          <input type="hidden" name="clNew_M_ACCAUNT_NAME"      value="">
          <input type="hidden" name="clNew_M_CUST_NO"           value="">
          <input type="hidden" name="clNew_M_SAVINGS_CODE"      value="">
          <input type="hidden" name="clNew_M_SAVINGS_NUMBER"    value="">
          <input type="hidden" name="clNew_M_BANK_CODE"         value="">
          <input type="hidden" name="clNew_M_BRANCH_CODE"       value="">
          <input type="hidden" name="clNew_S_BRANCH_CODE"       value="">
          <input type="hidden" name="coNew_M_CO_G_NAME"         value="">
          <input type="hidden" name="coNew_M_CO_G_KANA"         value="">
          <input type="hidden" name="coNew_M_CO_C_NAME"         value="">
          <input type="hidden" name="coNew_M_CO_C_KANA"         value="">
          <input type="hidden" name="coNew_M_CO_C_AFFILIATION"  value="">
          <input type="hidden" name="coNew_M_CO_C_POSITION"     value="">
          <input type="hidden" name="coNew_M_CO_C_EMAIL"        value="">
          <input type="hidden" name="coNew_M_CO_C_CC_EMAIL"     value="">
          <input type="hidden" name="coNew_M_CO_C_TEL"          value="">
          <input type="hidden" name="coNew_M_CO_C_TEL_1"        value="">
          <input type="hidden" name="coNew_M_CO_C_TEL_2"        value="">
          <input type="hidden" name="coNew_M_CO_C_TEL_3"        value="">
          <input type="hidden" name="coNew_M_CO_C_FAX"          value="">
          <input type="hidden" name="coNew_M_CO_C_FAX_1"        value="">
          <input type="hidden" name="coNew_M_CO_C_FAX_2"        value="">
          <input type="hidden" name="coNew_M_CO_C_FAX_3"        value="">
          <input type="hidden" name="coNew_M_CO_C_POST"         value="">
          <input type="hidden" name="coNew_M_CO_C_POST_u"       value="">
          <input type="hidden" name="coNew_M_CO_C_POST_l"       value="">
          <input type="hidden" name="coNew_M_CO_C_STA"          value="">
          <input type="hidden" name="coNew_M_CO_C_ADDRESS"      value="">
          <input type="hidden" name="coNew_M_CO_C_ADDRESS2"     value="">
          <IFRAME name="getData"  src="" style="display:none"></IFRAME>
          <IFRAME name="getData2" src="" style="display:none"></IFRAME>
          <IFRAME name="getData3" src="" style="display:none"></IFRAME>
          <IFRAME name="getData4" src="" style="display:none"></IFRAME>
          <IFRAME name="getData5" src="" style="display:none"></IFRAME>
          <IFRAME name="getData6" src="" style="display:none"></IFRAME>
        </form>
    </div>
    <script type="text/javascript">
      function changeContactForm(){
         var form = document.mainForm;
         if (form.M_CONTACT_C_NAME != undefined) {
            var contactdest  = document.getElementById("M_CONTACT_ID");
            switch(contactdest.value) {
               case "0":
                  checkDispIni(form.M_CONTACT_C_NAME, form.P_C_NAME);
                  checkDispIni(form.M_CONTACT_C_NAME_KN, form.P_C_KANA);
                  checkDispIni(form.M_CONTACT_G_NAME, form.G_NAME);
                  checkDispIni(form.M_CONTACT_G_NAME_KN, form.G_NAME_KN);
                  checkDispIni(form.M_CONTACT_AFFILIATION, form.S_AFFILIATION_NAME);
                  checkDispIni(form.M_CONTACT_POSITION, form.S_OFFICIAL_POSITION);
                  checkDispIni(form.M_CONTACT_EMAIL, form.P_C_EMAIL);
                  checkDispIni(form.M_CONTACT_CC_EMAIL, form.P_C_CC_EMAIL);
                  checkDispIni(form.M_CONTACT_TEL, form.P_C_TEL);
                  checkDispIni(form.M_CO_C_TEL_1, form.P_C_TEL_1);
                  checkDispIni(form.M_CO_C_TEL_2, form.P_C_TEL_2);
                  checkDispIni(form.M_CO_C_TEL_3, form.P_C_TEL_3);
                  checkDispIni(form.M_CONTACT_FAX, form.P_C_FAX);
                  checkDispIni(form.M_CO_C_FAX_1, form.P_C_FAX_1);
                  checkDispIni(form.M_CO_C_FAX_2, form.P_C_FAX_2);
                  checkDispIni(form.M_CO_C_FAX_3, form.P_C_FAX_3);
                  checkDispIni(form.M_CONTACT_POST, form.P_C_POST);
                  checkDispIni(form.M_CO_C_POST_u, form.P_C_POST_u);
                  checkDispIni(form.M_CO_C_POST_l, form.P_C_POST_l);
                  checkDispIni(form.M_CONTACT_STA, form.P_C_STA);
                  checkDispIni(form.M_CONTACT_ADR, form.P_C_ADR);
                  checkDispIni(form.M_CONTACT_ADR2, form.P_C_ADR2);

                  checkDispReadOnly(form.M_CONTACT_G_NAME, false);
                  checkDispReadOnly(form.M_CONTACT_G_NAME_KN, false);
                  checkDispReadOnly(form.M_CONTACT_AFFILIATION, false);
                  checkDispReadOnly(form.M_CONTACT_POSITION, false);
              
                  checkDispColor(form.M_CONTACT_G_NAME, "");
                  checkDispColor(form.M_CONTACT_G_NAME_KN, "");
                  checkDispColor(form.M_CONTACT_AFFILIATION, "");
                  checkDispColor(form.M_CONTACT_POSITION, "");
                  break;
            
                  case "4":
                    
                    checkDispIni(form.M_CONTACT_C_NAME, form.P_C_NAME);
                    checkDispIni(form.M_CONTACT_C_NAME_KN, form.P_C_KANA);
                    checkDispIni(form.M_CONTACT_G_NAME, form.G_NAME);
                    checkDispIni(form.M_CONTACT_G_NAME_KN, form.G_NAME_KN);
                    checkDispIni(form.M_CONTACT_AFFILIATION, form.S_AFFILIATION_NAME);
                    checkDispIni(form.M_CONTACT_POSITION, form.S_OFFICIAL_POSITION);
                    checkDispIni(form.M_CONTACT_EMAIL, form.G_EMAIL);
                    checkDispIni(form.M_CONTACT_CC_EMAIL, form.G_CC_EMAIL);
                    checkDispIni(form.M_CONTACT_TEL, form.G_TEL);
                    checkDispIni(form.M_CO_C_TEL_1, form.G_TEL_1);
                    checkDispIni(form.M_CO_C_TEL_2, form.G_TEL_2);
                    checkDispIni(form.M_CO_C_TEL_3, form.G_TEL_3);
                    checkDispIni(form.M_CONTACT_FAX, form.G_FAX);
                    checkDispIni(form.M_CO_C_FAX_1, form.G_FAX_1);
                    checkDispIni(form.M_CO_C_FAX_2, form.G_FAX_2);
                    checkDispIni(form.M_CO_C_FAX_3, form.G_FAX_3);
                    checkDispIni(form.M_CONTACT_POST, form.G_POST);
                    checkDispIni(form.M_CO_C_POST_u, form.G_POST_u);
                    checkDispIni(form.M_CO_C_POST_l, form.G_POST_l);
                    checkDispIni(form.M_CONTACT_STA, form.G_STA);
                    checkDispIni(form.M_CONTACT_ADR, form.G_ADDRESS);
                    checkDispIni(form.M_CONTACT_ADR2, form.G_ADDRESS2);

                    checkDispReadOnly(form.M_CONTACT_G_NAME, false);
                    checkDispReadOnly(form.M_CONTACT_G_NAME_KN, false);
                    checkDispReadOnly(form.M_CONTACT_AFFILIATION, false);
                    checkDispReadOnly(form.M_CONTACT_POSITION, false);
                    
                    checkDispColor(form.M_CONTACT_G_NAME, "");
                    checkDispColor(form.M_CONTACT_G_NAME_KN, "");
                    checkDispColor(form.M_CONTACT_AFFILIATION, "");
                    checkDispColor(form.M_CONTACT_POSITION, "");

                    break;
                  case "5":
                    
                    checkDispIni(form.M_CONTACT_C_NAME, form.P_C_NAME);
                    checkDispIni(form.M_CONTACT_C_NAME_KN, form.P_C_KANA);
                    checkDisp(form.M_CONTACT_G_NAME, "");
                    checkDisp(form.M_CONTACT_G_NAME_KN, "");
                    checkDisp(form.M_CONTACT_AFFILIATION, "");
                    checkDisp(form.M_CONTACT_POSITION, "");
                    checkDispIni(form.M_CONTACT_EMAIL, form.P_P_EMAIL);
                    checkDispIni(form.M_CONTACT_CC_EMAIL, form.P_P_CC_EMAIL);
                    checkDispIni(form.M_CONTACT_TEL, form.P_P_TEL);
                    checkDispIni(form.M_CO_C_TEL_1, form.P_P_TEL_1);
                    checkDispIni(form.M_CO_C_TEL_2, form.P_P_TEL_2);
                    checkDispIni(form.M_CO_C_TEL_3, form.P_P_TEL_3);
                    checkDispIni(form.M_CONTACT_FAX, form.P_P_FAX);
                    checkDispIni(form.M_CO_C_FAX_1, form.P_P_FAX_1);
                    checkDispIni(form.M_CO_C_FAX_2, form.P_P_FAX_2);
                    checkDispIni(form.M_CO_C_FAX_3, form.P_P_FAX_3);
                    checkDispIni(form.M_CONTACT_POST, form.P_P_POST);
                    checkDispIni(form.M_CO_C_POST_u, form.P_P_POST_u);
                    checkDispIni(form.M_CO_C_POST_l, form.P_P_POST_l);
                    checkDispIni(form.M_CONTACT_STA, form.P_P_STA);
                    checkDispIni(form.M_CONTACT_ADR, form.P_P_ADR);
                    checkDispIni(form.M_CONTACT_ADR2, form.P_P_ADR2);

                    checkDispReadOnly(form.M_CONTACT_G_NAME, true);
                    checkDispReadOnly(form.M_CONTACT_G_NAME_KN, true);
                    checkDispReadOnly(form.M_CONTACT_AFFILIATION, true);
                    checkDispReadOnly(form.M_CONTACT_POSITION, true);
                    
                    checkDispColor(form.M_CONTACT_G_NAME, "#FFFFCC");
                    checkDispColor(form.M_CONTACT_G_NAME_KN, "#FFFFCC");
                    checkDispColor(form.M_CONTACT_AFFILIATION, "#FFFFCC");
                    checkDispColor(form.M_CONTACT_POSITION, "#FFFFCC");
                    break;
               case "2":

               if(form.m_chg.value == "1"){
                 
                 checkDispBoth(form.M_CONTACT_C_NAME       , form.coNew_M_CO_C_NAME);
                 checkDispBoth(form.M_CONTACT_C_NAME_KN       , form.coNew_M_CO_C_KANA);
                 checkDispBoth(form.M_CONTACT_G_NAME       , form.coNew_M_CO_G_NAME);
                 checkDispBoth(form.M_CONTACT_G_NAME_KN       , form.coNew_M_CO_G_KANA);
                 checkDispBoth(form.M_CONTACT_AFFILIATION, form.coNew_M_CO_C_AFFILIATION);
                 checkDispBoth(form.M_CONTACT_POSITION   , form.coNew_M_CO_C_POSITION);
                 checkDispBoth(form.M_CONTACT_EMAIL      , form.coNew_M_CO_C_EMAIL);
                 checkDispBoth(form.M_CONTACT_CC_EMAIL   , form.coNew_M_CO_C_CC_EMAIL);
                 form.coNew_M_CO_C_TEL.value = form.coNew_M_CO_C_TEL_1.value + "-" + form.coNew_M_CO_C_TEL_2.value + "-" + form.coNew_M_CO_C_TEL_3.value;
                 checkDispBoth(form.M_CONTACT_TEL        , form.coNew_M_CO_C_TEL);
                 checkDispBoth(form.M_CO_C_TEL_1      , form.coNew_M_CO_C_TEL_1);
                 checkDispBoth(form.M_CO_C_TEL_2      , form.coNew_M_CO_C_TEL_2);
                 checkDispBoth(form.M_CO_C_TEL_3      , form.coNew_M_CO_C_TEL_3);
                 form.coNew_M_CO_C_FAX.value = form.coNew_M_CO_C_FAX_1.value + "-" + form.coNew_M_CO_C_FAX_2.value + "-" + form.coNew_M_CO_C_FAX_3.value;
                 checkDispBoth(form.M_CONTACT_FAX        , form.coNew_M_CO_C_FAX);
                 checkDispBoth(form.M_CO_C_FAX_1      , form.coNew_M_CO_C_FAX_1);
                 checkDispBoth(form.M_CO_C_FAX_2      , form.coNew_M_CO_C_FAX_2);
                 checkDispBoth(form.M_CO_C_FAX_3      , form.coNew_M_CO_C_FAX_3);
                 form.coNew_M_CO_C_POST.value = form.coNew_M_CO_C_POST_u.value + "-" + form.coNew_M_CO_C_POST_l.value;
                 checkDispBoth(form.M_CONTACT_POST       , form.coNew_M_CO_C_POST);
                 checkDispBoth(form.M_CO_C_POST_u     , form.coNew_M_CO_C_POST_u);
                 checkDispBoth(form.M_CO_C_POST_l     , form.coNew_M_CO_C_POST_l);
                 checkDispBoth(form.M_CONTACT_STA        , form.coNew_M_CO_C_STA);
                 checkDispBoth(form.M_CONTACT_ADR    , form.coNew_M_CO_C_ADDRESS);
                 checkDispBoth(form.M_CONTACT_ADR2   , form.coNew_M_CO_C_ADDRESS2);
               }

               checkDispReadOnly(form.M_CONTACT_G_NAME, false);
               checkDispReadOnly(form.M_CONTACT_G_NAME_KN, false);
               checkDispReadOnly(form.M_CONTACT_AFFILIATION, false);
               checkDispReadOnly(form.M_CONTACT_POSITION, false);
              
               checkDispColor(form.M_CONTACT_G_NAME, "");
               checkDispColor(form.M_CONTACT_G_NAME_KN, "");
               checkDispColor(form.M_CONTACT_AFFILIATION, "");
               checkDispColor(form.M_CONTACT_POSITION, "");
               setContactEnabled();
               break;
            }
        }
      }

      function changeClaimForm(msg){
         var form = document.mainForm;

         if (form.M_BILLING_C_NAME != undefined) {
          var claimdest  = document.getElementById("M_BILLING_ID");
          switch(claimdest.value) {
            case "0":
              checkDispIni(form.M_BILLING_C_NAME, form.P_C_NAME);
              checkDispIni(form.M_BILLING_C_NAME_KN, form.P_C_KANA);
              checkDispIni(form.M_BILLING_G_NAME, form.G_NAME);
              checkDispIni(form.M_BILLING_G_KANA, form.G_NAME_KN);
              checkDispIni(form.M_BILLING_G_NAME_EN, form.G_NAME_EN);
              checkDispIni(form.M_BILLING_AFFILIATION, form.S_AFFILIATION_NAME);
              checkDispIni(form.M_BILLING_POSITION, form.S_OFFICIAL_POSITION);
              checkDispIni(form.M_BILLING_EMAIL, form.P_C_EMAIL);
              checkDispIni(form.M_BILLING_CC_EMAIL, form.P_C_CC_EMAIL);
              checkDispIni(form.M_BILLING_TEL, form.P_C_TEL);
              checkDispIni(form.M_CL_C_TEL_1, form.P_C_TEL_1);
              checkDispIni(form.M_CL_C_TEL_2, form.P_C_TEL_2);
              checkDispIni(form.M_CL_C_TEL_3, form.P_C_TEL_3);
              checkDispIni(form.M_BILLING_FAX, form.P_C_FAX);
              checkDispIni(form.M_CL_C_FAX_1, form.P_C_FAX_1);
              checkDispIni(form.M_CL_C_FAX_2, form.P_C_FAX_2);
              checkDispIni(form.M_CL_C_FAX_3, form.P_C_FAX_3);
              checkDispIni(form.M_BILLING_POST, form.P_C_POST);
              checkDispIni(form.M_CL_C_POST_u, form.P_C_POST_u);
              checkDispIni(form.M_CL_C_POST_l, form.P_C_POST_l);
              checkDispIni(form.M_BILLING_STA, form.P_C_STA);
              checkDispIni(form.M_BILLING_ADR, form.P_C_ADR);
              checkDispIni(form.M_BILLING_ADR2, form.P_C_ADR2);
              checkDispIni(form.S_BRANCH_CODE, form.P_BRANCH_CD);
              checkDispIni(form.M_ACCAUNT_TYPE, form.P_ACCAUNT_TYPE);
              checkDispIni(form.M_ACCOUNT_NO, form.P_ACCOUNT_NO);
              checkDispIni(form.M_ACCOUNT_NM, form.P_ACCOUNT_NM);
              checkDispIni(form.M_CUST_NO, form.P_CUST_NO);
              checkDispIni(form.M_SAVINGS_CD, form.P_SAVINGS_CD);
              checkDispIni(form.M_SAVINGS_NO, form.P_SAVINGS_NO);
              checkDispIni(form.M_BANK_CD, form.P_BANK_CD);
              OnBankCodeChange(form.P_BANK_CD, form.M_BRANCH_CD);
              checkDispIni(form.M_BANK_SET_NAME, form.P_BANK_CD);
              checkDispIni(form.M_BRANCH_SET_NAME, form.P_BRANCH_CD);
              checkDispIni(form.M_BRANCH_CD, form.P_BRANCH_CD);
              checkDispReadOnly(form.M_BILLING_G_NAME, false);
              checkDispReadOnly(form.M_BILLING_G_KANA, false);
              checkDispReadOnly(form.M_BILLING_G_NAME_EN, false);
              checkDispReadOnly(form.M_BILLING_AFFILIATION, false);
              checkDispReadOnly(form.M_BILLING_POSITION, false);
              checkDispColor(form.M_BILLING_G_NAME, "");
              checkDispColor(form.M_BILLING_G_KANA, "");
              checkDispColor(form.M_BILLING_G_NAME_EN, "");
              checkDispColor(form.M_BILLING_AFFILIATION, "");
              checkDispColor(form.M_BILLING_POSITION, "");
              break;
            case "4":
              checkDispIni(form.M_BILLING_C_NAME, form.P_C_NAME);
              checkDispIni(form.M_BILLING_C_NAME_KN, form.P_C_KANA);
              checkDispIni(form.M_BILLING_G_NAME, form.G_NAME);
              checkDispIni(form.M_BILLING_G_KANA, form.G_NAME_KN);
              checkDispIni(form.M_BILLING_G_NAME_EN, form.G_NAME_EN);
              checkDispIni(form.M_BILLING_AFFILIATION, form.S_AFFILIATION_NAME);
              checkDispIni(form.M_BILLING_POSITION, form.S_OFFICIAL_POSITION);
              checkDispIni(form.M_BILLING_EMAIL, form.G_EMAIL);
              checkDispIni(form.M_BILLING_CC_EMAIL, form.G_CC_EMAIL);
              checkDispIni(form.M_BILLING_TEL, form.G_TEL);
              checkDispIni(form.M_CL_C_TEL_1, form.G_TEL_1);
              checkDispIni(form.M_CL_C_TEL_2, form.G_TEL_2);
              checkDispIni(form.M_CL_C_TEL_3, form.G_TEL_3);
              checkDispIni(form.M_BILLING_FAX, form.G_FAX);
              checkDispIni(form.M_CL_C_FAX_1, form.G_FAX_1);
              checkDispIni(form.M_CL_C_FAX_2, form.G_FAX_2);
              checkDispIni(form.M_CL_C_FAX_3, form.G_FAX_3);
              checkDispIni(form.M_BILLING_POST, form.G_POST);
              checkDispIni(form.M_CL_C_POST_u, form.G_POST_u);
              checkDispIni(form.M_CL_C_POST_l, form.G_POST_l);
              checkDispIni(form.M_BILLING_STA, form.G_STA);
              checkDispIni(form.M_BILLING_ADR, form.G_ADDRESS);
              checkDispIni(form.M_BILLING_ADR2, form.G_ADDRESS2);
              checkDispIni(form.S_BRANCH_CODE, form.G_BRANCH_CD);
              checkDispIni(form.M_ACCAUNT_TYPE, form.G_ACCAUNT_TYPE);
              checkDispIni(form.M_ACCOUNT_NO, form.G_ACCOUNT_NO);
              checkDispIni(form.M_ACCOUNT_NM, form.G_ACCAUNT_NM);
              checkDispIni(form.M_CUST_NO, form.G_CUST_NO);
              checkDispIni(form.M_SAVINGS_CD, form.G_SAVINGS_CD);
              checkDispIni(form.M_SAVINGS_NO, form.G_SAVINGS_NO);
              checkDispIni(form.M_BANK_CD, form.G_BANK_CD);
              OnBankCodeChange(form.G_BANK_CD, form.M_BRANCH_CD);
              checkDispIni(form.M_BANK_SET_NAME, form.G_BANK_CD);
              checkDispIni(form.M_BRANCH_SET_NAME, form.G_BRANCH_CD);
              checkDispIni(form.M_BRANCH_CD, form.G_BRANCH_CD);
              checkDispReadOnly(form.M_BILLING_G_NAME, false);
              checkDispReadOnly(form.M_BILLING_G_KANA, false);
              checkDispReadOnly(form.M_BILLING_G_NAME_EN, false);
              checkDispReadOnly(form.M_BILLING_AFFILIATION, false);
              checkDispReadOnly(form.M_BILLING_POSITION, false);
              checkDispColor(form.M_BILLING_G_NAME, "");
              checkDispColor(form.M_BILLING_G_KANA, "");
              checkDispColor(form.M_BILLING_G_NAME_EN, "");
              checkDispColor(form.M_BILLING_AFFILIATION, "");
              checkDispColor(form.M_BILLING_POSITION, "");
              break;
            case "5":
              checkDispIni(form.M_BILLING_C_NAME, form.P_C_NAME);
              checkDispIni(form.M_BILLING_C_NAME_KN, form.P_C_KANA);
              checkDisp(form.M_BILLING_G_NAME, "");
              checkDisp(form.M_BILLING_G_KANA, "");
              checkDisp(form.M_BILLING_G_NAME_EN, "");
              checkDisp(form.M_BILLING_AFFILIATION, "");
              checkDisp(form.M_BILLING_POSITION, "");
              checkDispIni(form.M_BILLING_EMAIL, form.P_P_EMAIL);
              checkDispIni(form.M_BILLING_CC_EMAIL, form.P_P_CC_EMAIL);
              checkDispIni(form.M_BILLING_TEL, form.P_P_TEL);
              checkDispIni(form.M_CL_C_TEL_1, form.P_P_TEL_1);
              checkDispIni(form.M_CL_C_TEL_2, form.P_P_TEL_2);
              checkDispIni(form.M_CL_C_TEL_3, form.P_P_TEL_3);
              checkDispIni(form.M_BILLING_FAX, form.P_P_FAX);
              checkDispIni(form.M_CL_C_FAX_1, form.P_P_FAX_1);
              checkDispIni(form.M_CL_C_FAX_2, form.P_P_FAX_2);
              checkDispIni(form.M_CL_C_FAX_3, form.P_P_FAX_3);
              checkDispIni(form.M_BILLING_POST, form.P_P_POST);
              checkDispIni(form.M_CL_C_POST_u, form.P_P_POST_u);
              checkDispIni(form.M_CL_C_POST_l, form.P_P_POST_l);
              checkDispIni(form.M_BILLING_STA, form.P_P_STA);
              checkDispIni(form.M_BILLING_ADR, form.P_P_ADR);
              checkDispIni(form.M_BILLING_ADR2, form.P_P_ADR2);
              checkDispIni(form.S_BRANCH_CODE, form.P_BRANCH_CD);
              checkDispIni(form.M_ACCAUNT_TYPE, form.P_ACCAUNT_TYPE);
              checkDispIni(form.M_ACCOUNT_NO, form.P_ACCOUNT_NO);
              checkDispIni(form.M_ACCOUNT_NM, form.P_ACCOUNT_NM);
              checkDispIni(form.M_CUST_NO, form.P_CUST_NO);
              checkDispIni(form.M_SAVINGS_CD, form.P_SAVINGS_CD);
              checkDispIni(form.M_SAVINGS_NO, form.P_SAVINGS_NO);


              checkDispIni(form.M_BANK_CD, form.P_BANK_CD);
              OnBankCodeChange(form.P_BANK_CD, form.M_BRANCH_CD);
              checkDispIni(form.M_BANK_SET_NAME, form.P_BANK_CD);
              checkDispIni(form.M_BRANCH_SET_NAME, form.P_BRANCH_CD);
              checkDispIni(form.M_BRANCH_CD, form.P_BRANCH_CD);

              checkDispReadOnly(form.M_BILLING_G_NAME, true);
              checkDispReadOnly(form.M_BILLING_G_KANA, true);
              checkDispReadOnly(form.M_BILLING_G_NAME_EN, true);
              checkDispReadOnly(form.M_BILLING_AFFILIATION, true);
              checkDispReadOnly(form.M_BILLING_POSITION, true);

              checkDispColor(form.M_BILLING_G_NAME, "#FFFFCC");
              checkDispColor(form.M_BILLING_G_KANA, "#FFFFCC");
              checkDispColor(form.M_BILLING_G_NAME_EN, "#FFFFCC");
              checkDispColor(form.M_BILLING_AFFILIATION, "#FFFFCC");
              checkDispColor(form.M_BILLING_POSITION, "#FFFFCC");

              break;
            case "2":
               if(form.m_chg.value == "1"){

                 checkDispBoth(form.M_BILLING_C_NAME        , form.clNew_M_CL_C_NAME);
                 checkDispBoth(form.M_BILLING_C_NAME_KN        , form.clNew_M_CL_C_KANA);
                 checkDispBoth(form.M_BILLING_G_NAME        , form.clNew_M_CL_G_NAME);
                 checkDispBoth(form.M_BILLING_G_KANA        , form.clNew_M_CL_G_KANA);
                 checkDispBoth(form.M_BILLING_AFFILIATION , form.clNew_M_CL_C_AFFILIATION);
                 checkDispBoth(form.M_BILLING_POSITION    , form.clNew_M_CL_C_POSITION);
                 checkDispBoth(form.M_BILLING_EMAIL       , form.clNew_M_CL_C_EMAIL);
                 checkDispBoth(form.M_BILLING_CC_EMAIL    , form.clNew_M_CL_C_CC_EMAIL);
                 form.clNew_M_CL_C_TEL.value = form.clNew_M_CL_C_TEL_1.value + "-" + form.clNew_M_CL_C_TEL_2.value + "-" + form.clNew_M_CL_C_TEL_3.value;
                 checkDispBoth(form.M_BILLING_TEL         , form.clNew_M_CL_C_TEL);
                 checkDispBoth(form.M_CL_C_TEL_1       , form.clNew_M_CL_C_TEL_1);
                 checkDispBoth(form.M_CL_C_TEL_2       , form.clNew_M_CL_C_TEL_2);
                 checkDispBoth(form.M_CL_C_TEL_3       , form.clNew_M_CL_C_TEL_3);
                 form.clNew_M_CL_C_FAX.value = form.clNew_M_CL_C_FAX_1.value + "-" + form.clNew_M_CL_C_FAX_2.value + "-" + form.clNew_M_CL_C_FAX_3.value;
                 checkDispBoth(form.M_BILLING_FAX         , form.clNew_M_CL_C_FAX);
                 checkDispBoth(form.M_CL_C_FAX_1       , form.clNew_M_CL_C_FAX_1);
                 checkDispBoth(form.M_CL_C_FAX_2       , form.clNew_M_CL_C_FAX_2);
                 checkDispBoth(form.M_CL_C_FAX_3       , form.clNew_M_CL_C_FAX_3);
                 form.clNew_M_CL_C_POST.value = form.clNew_M_CL_C_POST_u.value + "-" + form.clNew_M_CL_C_POST_l.value;
                 checkDispBoth(form.M_BILLING_POST        , form.clNew_M_CL_C_POST);
                 checkDispBoth(form.M_CL_C_POST_u      , form.clNew_M_CL_C_POST_u);
                 checkDispBoth(form.M_CL_C_POST_l      , form.clNew_M_CL_C_POST_l);
                 checkDispBoth(form.M_BILLING_STA         , form.clNew_M_CL_C_STA);
                 checkDispBoth(form.M_BILLING_ADR     , form.clNew_M_CL_C_ADDRESS);
                 checkDispBoth(form.M_BILLING_ADR2    , form.clNew_M_CL_C_ADDRESS2);
                 checkDispBoth(form.M_ACCAUNT_TYPE     , form.clNew_M_ACCAUNT_TYPE);
                 checkDispBoth(form.M_ACCOUNT_NO   , form.clNew_M_ACCAUNT_NUMBER);
                 checkDispBoth(form.M_ACCOUNT_NM     , form.clNew_M_ACCAUNT_NAME);
                 checkDispBoth(form.M_CUST_NO          , form.clNew_M_CUST_NO);
                 checkDispBoth(form.M_SAVINGS_CD     , form.clNew_M_SAVINGS_CODE);
                 checkDispBoth(form.M_SAVINGS_NO   , form.clNew_M_SAVINGS_NUMBER);
                 checkDispBoth(form.M_BANK_CD        , form.clNew_M_BANK_CODE);
                 checkDispBoth(form.S_BRANCH_CODE      , form.clNew_S_BRANCH_CODE);
                 OnBankCodeChange(form.M_BANK_CD, form.M_BRANCH_CD);
                 checkDispBoth(form.M_BRANCH_CD, form.clNew_M_BRANCH_CODE);
               }

               checkDispReadOnly(form.M_BILLING_G_NAME, false);
               checkDispReadOnly(form.M_BILLING_G_KANA, false);
               checkDispReadOnly(form.M_BILLING_G_NAME_EN, false);
               checkDispReadOnly(form.M_BILLING_AFFILIATION, false);
               checkDispReadOnly(form.M_BILLING_POSITION, false);
               checkDispColor(form.M_BILLING_G_NAME, "");
               checkDispColor(form.M_BILLING_G_KANA, "");
               checkDispColor(form.M_BILLING_G_NAME_EN, "");
               checkDispColor(form.M_BILLING_AFFILIATION, "");
               checkDispColor(form.M_BILLING_POSITION, "");
               setBillingEnabled();
               break;
          }
        }
      }


      function changeContactFormValue(){
        var form = document.mainForm;
        var contactdest     = document.getElementById("M_CONTACT_ID");
        switch(contactdest.value) {
          case "0":
            checkDispBoth(form.P_C_NAME, form.M_CONTACT_C_NAME);
            checkDispBoth(form.P_C_KANA, form.M_CONTACT_C_NAME_KN);
            checkDispBoth(form.G_NAME, form.M_CONTACT_G_NAME);
            checkDispBoth(form.G_NAME_KN, form.M_CONTACT_G_NAME_KN);
            checkDispBoth(form.S_AFFILIATION_NAME, form.M_CONTACT_AFFILIATION);
            checkDispBoth(form.S_OFFICIAL_POSITION, form.M_CONTACT_POSITION);
            checkDispBoth(form.P_C_EMAIL, form.M_CONTACT_EMAIL);
            checkDispBoth(form.P_C_CC_EMAIL, form.M_CONTACT_CC_EMAIL);
            form.M_CONTACT_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
            checkDispBoth(form.P_C_TEL, form.M_CONTACT_TEL);
            checkDispBoth(form.P_C_TEL_1, form.M_CO_C_TEL_1);
            checkDispBoth(form.P_C_TEL_2, form.M_CO_C_TEL_2);
            checkDispBoth(form.P_C_TEL_3, form.M_CO_C_TEL_3);
            form.M_CONTACT_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
            checkDispBoth(form.P_C_FAX, form.M_CONTACT_FAX);
            checkDispBoth(form.P_C_FAX_1, form.M_CO_C_FAX_1);
            checkDispBoth(form.P_C_FAX_2, form.M_CO_C_FAX_2);
            checkDispBoth(form.P_C_FAX_3, form.M_CO_C_FAX_3);
            form.M_CONTACT_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
            checkDispBoth(form.P_C_POST, form.M_CONTACT_POST);
            checkDispBoth(form.P_C_POST_u, form.M_CO_C_POST_u);
            checkDispBoth(form.P_C_POST_l, form.M_CO_C_POST_l);
            checkDispBoth(form.P_C_STA, form.M_CONTACT_STA);
            checkDispBoth(form.P_C_ADR, form.M_CONTACT_ADR);
            checkDispBoth(form.P_C_ADR2, form.M_CONTACT_ADR2);
            break;


          case "4":
            checkDispBoth(form.P_C_NAME, form.M_CONTACT_C_NAME);
            checkDispBoth(form.P_C_KANA, form.M_CONTACT_C_NAME_KN);
            checkDispBoth(form.G_NAME, form.M_CONTACT_G_NAME);
            checkDispBoth(form.G_NAME_KN, form.M_CONTACT_G_NAME_KN);
            checkDispBoth(form.S_AFFILIATION_NAME, form.M_CONTACT_AFFILIATION);
            checkDispBoth(form.S_OFFICIAL_POSITION, form.M_CONTACT_POSITION);
            checkDispBoth(form.G_EMAIL, form.M_CONTACT_EMAIL);
            checkDispBoth(form.G_CC_EMAIL, form.M_CONTACT_CC_EMAIL);
            form.M_CONTACT_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
            checkDispBoth(form.G_TEL, form.M_CONTACT_TEL);
            checkDispBoth(form.G_TEL_1, form.M_CO_C_TEL_1);
            checkDispBoth(form.G_TEL_2, form.M_CO_C_TEL_2);
            checkDispBoth(form.G_TEL_3, form.M_CO_C_TEL_3);
            form.M_CONTACT_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
            checkDispBoth(form.G_FAX, form.M_CONTACT_FAX);
            checkDispBoth(form.G_FAX_1, form.M_CO_C_FAX_1);
            checkDispBoth(form.G_FAX_2, form.M_CO_C_FAX_2);
            checkDispBoth(form.G_FAX_3, form.M_CO_C_FAX_3);
            form.M_CONTACT_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
            checkDispBoth(form.G_POST, form.M_CONTACT_POST);
            checkDispBoth(form.G_POST_u, form.M_CO_C_POST_u);
            checkDispBoth(form.G_POST_l, form.M_CO_C_POST_l);
            checkDispBoth(form.G_STA, form.M_CONTACT_STA);
            checkDispBoth(form.G_ADDRESS, form.M_CONTACT_ADR);
            checkDispBoth(form.G_ADDRESS2, form.M_CONTACT_ADR2);
            break;


          case "5":
            checkDispBoth(form.P_C_NAME, form.M_CONTACT_C_NAME);
            checkDispBoth(form.P_C_KANA, form.M_CONTACT_C_NAME_KN);
            checkDispBoth(form.P_P_EMAIL, form.M_CONTACT_EMAIL);
            checkDispBoth(form.P_P_CC_EMAIL, form.M_CONTACT_CC_EMAIL);
            form.M_CONTACT_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
            checkDispBoth(form.P_P_TEL, form.M_CONTACT_TEL);
            checkDispBoth(form.P_P_TEL_1, form.M_CO_C_TEL_1);
            checkDispBoth(form.P_P_TEL_2, form.M_CO_C_TEL_2);
            checkDispBoth(form.P_P_TEL_3, form.M_CO_C_TEL_3);
            form.M_CONTACT_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
            checkDispBoth(form.P_P_FAX, form.M_CONTACT_FAX);
            checkDispBoth(form.P_P_FAX_1, form.M_CO_C_FAX_1);
            checkDispBoth(form.P_P_FAX_2, form.M_CO_C_FAX_2);
            checkDispBoth(form.P_P_FAX_3, form.M_CO_C_FAX_3);
            form.M_CONTACT_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
            checkDispBoth(form.P_P_POST, form.M_CONTACT_POST);
            checkDispBoth(form.P_P_POST_u, form.M_CO_C_POST_u);
            checkDispBoth(form.P_P_POST_l, form.M_CO_C_POST_l);
            checkDispBoth(form.P_P_STA, form.M_CONTACT_STA);
            checkDispBoth(form.P_P_ADR, form.M_CONTACT_ADR);
            checkDispBoth(form.P_P_ADR2, form.M_CONTACT_ADR2);
            break;


          case "2":
            break;
        }

        if(contactdest.value=="2"){

          checkDispBoth(form.coNew_M_CO_C_NAME       , form.M_CONTACT_C_NAME);
          checkDispBoth(form.coNew_M_CO_C_KANA       , form.M_CONTACT_C_NAME_KN);
          checkDispBoth(form.coNew_M_CO_G_NAME       , form.M_CONTACT_G_NAME);
          checkDispBoth(form.coNew_M_CO_G_KANA       , form.M_CONTACT_G_NAME_KN);
          checkDispBoth(form.coNew_M_CO_C_AFFILIATION, form.M_CONTACT_AFFILIATION);
          checkDispBoth(form.coNew_M_CO_C_POSITION   , form.M_CONTACT_POSITION);
          checkDispBoth(form.coNew_M_CO_C_EMAIL      , form.M_CONTACT_EMAIL);
          checkDispBoth(form.coNew_M_CO_C_CC_EMAIL   , form.M_CONTACT_CC_EMAIL);
          form.M_CONTACT_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
          checkDispBoth(form.coNew_M_CO_C_TEL        , form.M_CONTACT_TEL);
          checkDispBoth(form.coNew_M_CO_C_TEL_1      , form.M_CO_C_TEL_1);
          checkDispBoth(form.coNew_M_CO_C_TEL_2      , form.M_CO_C_TEL_2);
          checkDispBoth(form.coNew_M_CO_C_TEL_3      , form.M_CO_C_TEL_3);
          form.M_CONTACT_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
          checkDispBoth(form.coNew_M_CO_C_FAX        , form.M_CONTACT_FAX);
          checkDispBoth(form.coNew_M_CO_C_FAX_1      , form.M_CO_C_FAX_1);
          checkDispBoth(form.coNew_M_CO_C_FAX_2      , form.M_CO_C_FAX_2);
          checkDispBoth(form.coNew_M_CO_C_FAX_3      , form.M_CO_C_FAX_3);
            form.M_CONTACT_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
          checkDispBoth(form.coNew_M_CO_C_POST       , form.M_CONTACT_POST);
          checkDispBoth(form.coNew_M_CO_C_POST_u     , form.M_CO_C_POST_u);
          checkDispBoth(form.coNew_M_CO_C_POST_l     , form.M_CO_C_POST_l);
          checkDispBoth(form.coNew_M_CO_C_STA        , form.M_CONTACT_STA);
          checkDispBoth(form.coNew_M_CO_C_ADDRESS    , form.M_CONTACT_ADR);
          checkDispBoth(form.coNew_M_CO_C_ADDRESS2   , form.M_CONTACT_ADR2);
        }

      }


      function changeClaimFormValue(){
        var form = document.mainForm;
        var claimdest     = document.getElementById("M_BILLING_ID");
        var contactdest     = document.getElementById("M_CONTACT_ID");

        switch (claimdest.value) {

          case "0":
            checkDispBoth(form.P_C_NAME, form.M_BILLING_C_NAME);
            checkDispBoth(form.P_C_KANA, form.M_BILLING_C_NAME_KN);
            checkDispBoth(form.G_NAME, form.M_BILLING_G_NAME);
            checkDispBoth(form.G_NAME_KN, form.M_BILLING_G_KANA);
            checkDispBoth(form.G_NAME_EN, form.M_BILLING_G_NAME_EN);
            checkDispBoth(form.S_AFFILIATION_NAME, form.M_BILLING_AFFILIATION);
            checkDispBoth(form.S_OFFICIAL_POSITION, form.M_BILLING_POSITION);
            checkDispBoth(form.P_C_EMAIL, form.M_BILLING_EMAIL);
            checkDispBoth(form.P_C_CC_EMAIL, form.M_BILLING_CC_EMAIL);
            form.M_BILLING_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
            checkDispBoth(form.P_C_TEL, form.M_BILLING_TEL);
            checkDispBoth(form.P_C_TEL_1, form.M_CL_C_TEL_1);
            checkDispBoth(form.P_C_TEL_2, form.M_CL_C_TEL_2);
            checkDispBoth(form.P_C_TEL_3, form.M_CL_C_TEL_3);
            form.M_BILLING_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
            checkDispBoth(form.P_C_FAX, form.M_BILLING_FAX);
            checkDispBoth(form.P_C_FAX_1, form.M_CL_C_FAX_1);
            checkDispBoth(form.P_C_FAX_2, form.M_CL_C_FAX_2);
            checkDispBoth(form.P_C_FAX_3, form.M_CL_C_FAX_3);
            form.M_BILLING_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
            checkDispBoth(form.P_C_POST, form.M_BILLING_POST);
            checkDispBoth(form.P_C_POST_u, form.M_CL_C_POST_u);
            checkDispBoth(form.P_C_POST_l, form.M_CL_C_POST_l);
            checkDispBoth(form.P_C_STA, form.M_BILLING_STA);
            checkDispBoth(form.P_C_ADR, form.M_BILLING_ADR);
            checkDispBoth(form.P_C_ADR2, form.M_BILLING_ADR2);
            checkDispBoth(form.P_BRANCH_CD, form.M_BRANCH_CD);
            checkDispBoth(form.P_BANK_CD, form.M_BANK_CD);
            checkDispBoth(form.P_ACCAUNT_TYPE, form.M_ACCAUNT_TYPE);
            checkDispBoth(form.P_ACCOUNT_NO, form.M_ACCOUNT_NO);
            checkDispBoth(form.P_ACCOUNT_NM, form.M_ACCOUNT_NM);
            checkDispBoth(form.P_CUST_NO, form.M_CUST_NO);
            checkDispBoth(form.P_SAVINGS_CD, form.M_SAVINGS_CD);
            checkDispBoth(form.P_SAVINGS_NO, form.M_SAVINGS_NO);


            checkDispIni(form.P_BANK_CD, form.M_BANK_CD);
            OnBankCodeChange(form.M_BANK_CD, form.P_BRANCH_CD, '2');
            checkDispIni(form.P_BANK_SET_NAME, form.M_BANK_CD);
            checkDispIni(form.P_BRANCH_SET_NAME, form.M_BRANCH_CD);
            setTimeout("checkDispIni(document.mainForm.P_BRANCH_CD, document.mainForm.M_BRANCH_CD)", 1000);
            break;


          case "4":
            checkDispBoth(form.P_C_NAME, form.M_BILLING_C_NAME);
            checkDispBoth(form.P_C_KANA, form.M_BILLING_C_NAME_KN);
            checkDispBoth(form.G_NAME, form.M_BILLING_G_NAME);
            checkDispBoth(form.G_NAME_KN, form.M_BILLING_G_KANA);
            checkDispBoth(form.G_NAME_EN, form.M_BILLING_G_NAME_EN);
            checkDispBoth(form.S_AFFILIATION_NAME, form.M_BILLING_AFFILIATION);
            checkDispBoth(form.S_OFFICIAL_POSITION, form.M_BILLING_POSITION);
            checkDispBoth(form.G_EMAIL, form.M_BILLING_EMAIL);
            checkDispBoth(form.G_CC_EMAIL, form.M_BILLING_CC_EMAIL);
            form.M_BILLING_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
            checkDispBoth(form.G_TEL, form.M_BILLING_TEL);
            checkDispBoth(form.G_TEL_1, form.M_CL_C_TEL_1);
            checkDispBoth(form.G_TEL_2, form.M_CL_C_TEL_2);
            checkDispBoth(form.G_TEL_3, form.M_CL_C_TEL_3);
            form.M_BILLING_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
            checkDispBoth(form.G_FAX, form.M_BILLING_FAX);
            checkDispBoth(form.G_FAX_1, form.M_CL_C_FAX_1);
            checkDispBoth(form.G_FAX_2, form.M_CL_C_FAX_2);
            checkDispBoth(form.G_FAX_3, form.M_CL_C_FAX_3);
            form.M_BILLING_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
            checkDispBoth(form.G_POST, form.M_BILLING_POST);
            checkDispBoth(form.G_POST_u, form.M_CL_C_POST_u);
            checkDispBoth(form.G_POST_l, form.M_CL_C_POST_l);
            checkDispBoth(form.G_STA, form.M_BILLING_STA);
            checkDispBoth(form.G_ADDRESS, form.M_BILLING_ADR);
            checkDispBoth(form.G_ADDRESS2, form.M_BILLING_ADR2);
            checkDispBoth(form.G_ACCAUNT_TYPE, form.M_ACCAUNT_TYPE);
            checkDispBoth(form.G_ACCOUNT_NO, form.M_ACCOUNT_NO);
            checkDispBoth(form.G_ACCAUNT_NM, form.M_ACCOUNT_NM);
            checkDispBoth(form.G_CUST_NO, form.M_CUST_NO);
            checkDispBoth(form.G_SAVINGS_CD, form.M_SAVINGS_CD);
            checkDispBoth(form.G_SAVINGS_NO, form.M_SAVINGS_NO);


            checkDispIni(form.G_BANK_CD, form.M_BANK_CD);
            OnBankCodeChange(form.M_BANK_CD, form.G_BRANCH_CD, '3');
            checkDispIni(form.G_BANK_SET_NAME, form.M_BANK_CD);
            checkDispIni(form.G_BRANCH_SET_NAME, form.M_BRANCH_CD);
            setTimeout("checkDispIni(document.mainForm.G_BRANCH_CD, document.mainForm.M_BRANCH_CD)", 1000);
            break;


          case "5":
            checkDispBoth(form.P_C_NAME, form.M_BILLING_C_NAME);
            checkDispBoth(form.P_C_KANA, form.M_BILLING_C_NAME_KN);
            checkDispBoth(form.P_P_EMAIL, form.M_BILLING_EMAIL);
            checkDispBoth(form.P_P_CC_EMAIL, form.M_BILLING_CC_EMAIL);
            form.M_BILLING_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
            checkDispBoth(form.P_P_TEL, form.M_BILLING_TEL);
            checkDispBoth(form.P_P_TEL_1, form.M_CL_C_TEL_1);
            checkDispBoth(form.P_P_TEL_2, form.M_CL_C_TEL_2);
            checkDispBoth(form.P_P_TEL_3, form.M_CL_C_TEL_3);
            form.M_BILLING_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
            checkDispBoth(form.P_P_FAX, form.M_BILLING_FAX);
            checkDispBoth(form.P_P_FAX_1, form.M_CL_C_FAX_1);
            checkDispBoth(form.P_P_FAX_2, form.M_CL_C_FAX_2);
            checkDispBoth(form.P_P_FAX_3, form.M_CL_C_FAX_3);
            form.M_BILLING_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
            checkDispBoth(form.P_P_POST, form.M_BILLING_POST);
            checkDispBoth(form.P_P_POST_u, form.M_CL_C_POST_u);
            checkDispBoth(form.P_P_POST_l, form.M_CL_C_POST_l);
            checkDispBoth(form.P_P_STA, form.M_BILLING_STA);
            checkDispBoth(form.P_P_ADR, form.M_BILLING_ADR);
            checkDispBoth(form.P_P_ADR2, form.M_BILLING_ADR2);
            checkDispBoth(form.P_ACCAUNT_TYPE, form.M_ACCAUNT_TYPE);
            checkDispBoth(form.P_ACCOUNT_NO, form.M_ACCOUNT_NO);
            checkDispBoth(form.P_ACCOUNT_NM, form.M_ACCOUNT_NM);
            checkDispBoth(form.P_CUST_NO, form.M_CUST_NO);
            checkDispBoth(form.P_SAVINGS_CD, form.M_SAVINGS_CD);
            checkDispBoth(form.P_SAVINGS_NO, form.M_SAVINGS_NO);


            checkDispIni(form.P_BANK_CD, form.M_BANK_CD);
            OnBankCodeChange(form.M_BANK_CD, form.P_BRANCH_CD, '2');
            checkDispIni(form.P_BANK_SET_NAME, form.M_BANK_CD);
            checkDispIni(form.P_BRANCH_SET_NAME, form.M_BRANCH_CD);
            setTimeout("checkDispIni(document.mainForm.P_BRANCH_CD, document.mainForm.M_BRANCH_CD)", 1000);
            break;


          case "2":
            break;

        }

        if(claimdest.value=="2"){

          checkDispBoth(form.clNew_M_CL_C_NAME        , form.M_BILLING_C_NAME);
          checkDispBoth(form.clNew_M_CL_C_KANA        , form.M_BILLING_C_NAME_KN);
          checkDispBoth(form.clNew_M_CL_G_NAME        , form.M_BILLING_G_NAME);
          checkDispBoth(form.clNew_M_CL_G_KANA        , form.M_BILLING_G_KANA);
          checkDispBoth(form.clNew_M_CL_C_AFFILIATION , form.M_BILLING_AFFILIATION);
          checkDispBoth(form.clNew_M_CL_C_POSITION    , form.M_BILLING_POSITION);
          checkDispBoth(form.clNew_M_CL_C_EMAIL       , form.M_BILLING_EMAIL);
          checkDispBoth(form.clNew_M_CL_C_CC_EMAIL    , form.M_BILLING_CC_EMAIL);
          form.M_BILLING_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
          checkDispBoth(form.clNew_M_CL_C_TEL         , form.M_BILLING_TEL);
          checkDispBoth(form.clNew_M_CL_C_TEL_1       , form.M_CL_C_TEL_1);
          checkDispBoth(form.clNew_M_CL_C_TEL_2       , form.M_CL_C_TEL_2);
          checkDispBoth(form.clNew_M_CL_C_TEL_3       , form.M_CL_C_TEL_3);
          form.M_BILLING_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
          checkDispBoth(form.clNew_M_CL_C_FAX         , form.M_BILLING_FAX);
          checkDispBoth(form.clNew_M_CL_C_FAX_1       , form.M_CL_C_FAX_1);
          checkDispBoth(form.clNew_M_CL_C_FAX_2       , form.M_CL_C_FAX_2);
          checkDispBoth(form.clNew_M_CL_C_FAX_3       , form.M_CL_C_FAX_3);
          form.M_BILLING_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
          checkDispBoth(form.clNew_M_CL_C_POST        , form.M_BILLING_POST);
          checkDispBoth(form.clNew_M_CL_C_POST_u      , form.M_CL_C_POST_u);
          checkDispBoth(form.clNew_M_CL_C_POST_l      , form.M_CL_C_POST_l);
          checkDispBoth(form.clNew_M_CL_C_STA         , form.M_BILLING_STA);
          checkDispBoth(form.clNew_M_CL_C_ADDRESS     , form.M_BILLING_ADR);
          checkDispBoth(form.clNew_M_CL_C_ADDRESS2    , form.M_BILLING_ADR2);
          checkDispBoth(form.clNew_M_ACCAUNT_TYPE     , form.M_ACCAUNT_TYPE);
          checkDispBoth(form.clNew_M_ACCAUNT_NUMBER   , form.M_ACCOUNT_NO);
          checkDispBoth(form.clNew_M_ACCAUNT_NAME     , form.M_ACCOUNT_NM);
          checkDispBoth(form.clNew_M_CUST_NO          , form.M_CUST_NO);
          checkDispBoth(form.clNew_M_SAVINGS_CODE     , form.M_SAVINGS_CD);
          checkDispBoth(form.clNew_M_SAVINGS_NUMBER   , form.M_SAVINGS_NO);
          checkDispBoth(form.clNew_M_BANK_CODE        , form.M_BANK_CD);
          checkDispBoth(form.clNew_S_BRANCH_CODE      , form.M_BRANCH_CD);
          checkDispBoth(form.clNew_M_BRANCH_CODE      , form.M_BRANCH_CD);
        }

      }


      function changeContactFormDisp(){
        var form = document.mainForm;
        var claimdest     = document.getElementById("M_BILLING_ID");
        var contactdest     = document.getElementById("M_CONTACT_ID");



        if ((contactdest.value == 5) && (claimdest.value==5)) {

          checkDispReadOnly(form.M_BILLING_G_NAME, true);
          checkDispReadOnly(form.M_BILLING_G_KANA, true);
          checkDispReadOnly(form.M_BILLING_G_NAME_EN, true);
          checkDispReadOnly(form.M_BILLING_AFFILIATION, true);
          checkDispReadOnly(form.M_BILLING_POSITION, true);

          checkDispColor(form.M_BILLING_G_NAME, "#FFFFCC");
          checkDispColor(form.M_BILLING_G_KANA, "#FFFFCC");
          checkDispColor(form.M_BILLING_G_NAME_EN, "#FFFFCC");
          checkDispColor(form.M_BILLING_AFFILIATION, "#FFFFCC");
          checkDispColor(form.M_BILLING_POSITION, "#FFFFCC");
        } else {
          checkDispReadOnly(form.M_BILLING_G_NAME, false);
          checkDispReadOnly(form.M_BILLING_G_KANA, false);
          checkDispReadOnly(form.M_BILLING_G_NAME_EN, false);
          checkDispReadOnly(form.M_BILLING_AFFILIATION, false);
          checkDispReadOnly(form.M_BILLING_POSITION, false);

          checkDispColor(form.M_BILLING_G_NAME, "");
          checkDispColor(form.M_BILLING_G_KANA, "");
          checkDispColor(form.M_BILLING_G_NAME_EN, "");
          checkDispColor(form.M_BILLING_AFFILIATION, "");
          checkDispColor(form.M_BILLING_POSITION, "");
        }


      }


      function checkDisp(form1, form2){
        if (form1 != undefined) {
          form1.value = form2;
        }
      }

      function checkDispBoth(form1, form2){
        if (form1 != undefined && form2 != undefined) {
          form1.value = form2.value;
        }
      }

      function checkDispIni(form1, form2){
        if (form1 != undefined && form2 != undefined) {
          if (form2.type != "hidden" && form2.readOnly != true) {
            form1.value = form2.value;

            checkDispReadOnly(form1, false);
            checkDispColor(form1, "")

          } else {
            form1.value = form2.value;

            checkDispReadOnly(form1, true);
            checkDispColor(form1, "#FFFFCC")

          }
        }
      }

      function checkDispReadOnly(form1, flg){
        if (form1 != undefined) {
          form1.readOnly = flg;
        }
      }

      function checkDispColor(form1, color){
        if (form1 != undefined) {
          form1.style.backgroundColor = color;
        }
      }

      function checkDispDisabled(form1, value){
        if (form1 != undefined) {
          form1.disabled = value;
        }
      }

      function setContactDisabled(){
        var form = document.mainForm;

        checkDispReadOnly(form.M_CONTACT_G_NAME        , true);
        checkDispReadOnly(form.M_CONTACT_G_NAME_KN        , true);
        checkDispReadOnly(form.M_CONTACT_C_NAME        , true);
        checkDispReadOnly(form.M_CONTACT_C_NAME_KN        , true);
        checkDispReadOnly(form.M_CONTACT_EMAIL       , true);
        checkDispReadOnly(form.M_CONTACT_CC_EMAIL    , true);
        checkDispReadOnly(form.M_CONTACT_TEL         , true);
        checkDispReadOnly(form.M_CO_C_TEL_1       , true);
        checkDispReadOnly(form.M_CO_C_TEL_2       , true);
        checkDispReadOnly(form.M_CO_C_TEL_3       , true);
        checkDispReadOnly(form.M_CONTACT_FAX         , true);
        checkDispReadOnly(form.M_CO_C_FAX_1       , true);
        checkDispReadOnly(form.M_CO_C_FAX_2       , true);
        checkDispReadOnly(form.M_CO_C_FAX_3       , true);
        checkDispReadOnly(form.M_CONTACT_POST        , true);
        checkDispReadOnly(form.M_CO_C_POST_u      , true);
        checkDispReadOnly(form.M_CO_C_POST_l      , true);
        checkDispReadOnly(form.M_CONTACT_STA         , true);
        checkDispReadOnly(form.M_CONTACT_ADR     , true);
        checkDispReadOnly(form.M_CONTACT_ADR2    , true);
        checkDispReadOnly(form.M_CONTACT_AFFILIATION , true);
        checkDispReadOnly(form.M_CONTACT_POSITION    , true);

        checkDispColor(form.M_CONTACT_G_NAME         , "#FFFFCC");
        checkDispColor(form.M_CONTACT_G_NAME_KN         , "#FFFFCC");
        checkDispColor(form.M_CONTACT_C_NAME         , "#FFFFCC");
        checkDispColor(form.M_CONTACT_C_NAME_KN         , "#FFFFCC");
        checkDispColor(form.M_CONTACT_EMAIL        , "#FFFFCC");
        checkDispColor(form.M_CONTACT_CC_EMAIL     , "#FFFFCC");
        checkDispColor(form.M_CO_C_TEL_1        , "#FFFFCC");
        checkDispColor(form.M_CO_C_TEL_2        , "#FFFFCC");
        checkDispColor(form.M_CO_C_TEL_3        , "#FFFFCC");
        checkDispColor(form.M_CO_C_FAX_1        , "#FFFFCC");
        checkDispColor(form.M_CO_C_FAX_2        , "#FFFFCC");
        checkDispColor(form.M_CO_C_FAX_3        , "#FFFFCC");
        checkDispColor(form.M_CO_C_POST_u       , "#FFFFCC");
        checkDispColor(form.M_CO_C_POST_l       , "#FFFFCC");
        checkDispColor(form.M_CONTACT_STA          , "#FFFFCC");
        checkDispColor(form.M_CONTACT_ADR      , "#FFFFCC");
        checkDispColor(form.M_CONTACT_ADR2     , "#FFFFCC");
        checkDispColor(form.M_CONTACT_AFFILIATION  , "#FFFFCC");
        checkDispColor(form.M_CONTACT_POSITION     , "#FFFFCC");

        checkDispDisabled(form.search_button1 , true);
      }

      function setBillingDisabled(){
        var form = document.mainForm;

        checkDispReadOnly(form.M_BILLING_G_NAME        , true);
        checkDispReadOnly(form.M_BILLING_G_KANA        , true);
        checkDispReadOnly(form.M_BILLING_G_NAME_EN        , true);
        checkDispReadOnly(form.M_BILLING_C_NAME        , true);
        checkDispReadOnly(form.M_BILLING_C_NAME_KN        , true);
        checkDispReadOnly(form.M_BILLING_EMAIL       , true);
        checkDispReadOnly(form.M_BILLING_CC_EMAIL    , true);
        checkDispReadOnly(form.M_BILLING_TEL         , true);
        checkDispReadOnly(form.M_CL_C_TEL_1       , true);
        checkDispReadOnly(form.M_CL_C_TEL_2       , true);
        checkDispReadOnly(form.M_CL_C_TEL_3       , true);
        checkDispReadOnly(form.M_BILLING_FAX         , true);
        checkDispReadOnly(form.M_CL_C_FAX_1       , true);
        checkDispReadOnly(form.M_CL_C_FAX_2       , true);
        checkDispReadOnly(form.M_CL_C_FAX_3       , true);
        checkDispReadOnly(form.M_BILLING_POST        , true);
        checkDispReadOnly(form.M_CL_C_POST_u      , true);
        checkDispReadOnly(form.M_CL_C_POST_l      , true);
        checkDispReadOnly(form.M_BILLING_STA         , true);
        checkDispReadOnly(form.M_BILLING_ADR     , true);
        checkDispReadOnly(form.M_BILLING_ADR2    , true);
        checkDispReadOnly(form.M_BILLING_AFFILIATION , true);
        checkDispReadOnly(form.M_BILLING_POSITION    , true);

        checkDispColor(form.M_BILLING_G_NAME         , "#FFFFCC");
        checkDispColor(form.M_BILLING_G_KANA         , "#FFFFCC");
        checkDispColor(form.M_BILLING_G_NAME_EN         , "#FFFFCC");
        checkDispColor(form.M_BILLING_C_NAME         , "#FFFFCC");
        checkDispColor(form.M_BILLING_C_NAME_KN         , "#FFFFCC");
        checkDispColor(form.M_BILLING_EMAIL        , "#FFFFCC");
        checkDispColor(form.M_BILLING_CC_EMAIL     , "#FFFFCC");
        checkDispColor(form.M_CL_C_TEL_1        , "#FFFFCC");
        checkDispColor(form.M_CL_C_TEL_2        , "#FFFFCC");
        checkDispColor(form.M_CL_C_TEL_3        , "#FFFFCC");
        checkDispColor(form.M_CL_C_FAX_1        , "#FFFFCC");
        checkDispColor(form.M_CL_C_FAX_2        , "#FFFFCC");
        checkDispColor(form.M_CL_C_FAX_3        , "#FFFFCC");
        checkDispColor(form.M_CL_C_POST_u       , "#FFFFCC");
        checkDispColor(form.M_CL_C_POST_l       , "#FFFFCC");
        checkDispColor(form.M_BILLING_STA          , "#FFFFCC");
        checkDispColor(form.M_BILLING_ADR      , "#FFFFCC");
        checkDispColor(form.M_BILLING_ADR2     , "#FFFFCC");
        checkDispColor(form.M_BILLING_AFFILIATION  , "#FFFFCC");
        checkDispColor(form.M_BILLING_POSITION     , "#FFFFCC");

        checkDispReadOnly(form.M_BANK_CD      , true);
        checkDispReadOnly(form.M_BRANCH_CD    , true);
        checkDispReadOnly(form.M_ACCAUNT_TYPE   , true);
        checkDispReadOnly(form.M_ACCOUNT_NO , true);
        checkDispReadOnly(form.M_ACCOUNT_NM   , true);
        checkDispReadOnly(form.M_CUST_NO        , true);
        checkDispReadOnly(form.M_SAVINGS_CD   , true);
        checkDispReadOnly(form.M_SAVINGS_NO , true);

        checkDispColor(form.M_BANK_CD       , "#FFFFCC");
        checkDispColor(form.M_BRANCH_CD     , "#FFFFCC");
        checkDispColor(form.M_ACCAUNT_TYPE    , "#FFFFCC");
        checkDispColor(form.M_ACCOUNT_NO  , "#FFFFCC");
        checkDispColor(form.M_ACCOUNT_NM    , "#FFFFCC");
        checkDispColor(form.M_CUST_NO         , "#FFFFCC");
        checkDispColor(form.M_SAVINGS_CD    , "#FFFFCC");
        checkDispColor(form.M_SAVINGS_NO  , "#FFFFCC");

        checkDispDisabled(form.M_BANK_CD    , true);
        checkDispDisabled(form.M_BRANCH_CD  , true);
        checkDispDisabled(form.search_button2 , true);
        checkDispDisabled(form.search_button3 , true);
        checkDispDisabled(form.search_button_m, true);
        checkDispDisabled(form.M_ACCAUNT_TYPE , true);

      }

      function setContactEnabled(){
        var form = document.mainForm;

        checkDispReadOnly(form.M_CONTACT_G_NAME        , false);
        checkDispReadOnly(form.M_CONTACT_G_NAME_KN        , false);
        checkDispReadOnly(form.M_CONTACT_C_NAME        , false);
        checkDispReadOnly(form.M_CONTACT_C_NAME_KN        , false);
        checkDispReadOnly(form.M_CONTACT_EMAIL       , false);
        checkDispReadOnly(form.M_CONTACT_CC_EMAIL    , false);
        checkDispReadOnly(form.M_CONTACT_TEL         , false);
        checkDispReadOnly(form.M_CO_C_TEL_1       , false);
        checkDispReadOnly(form.M_CO_C_TEL_2       , false);
        checkDispReadOnly(form.M_CO_C_TEL_3       , false);
        checkDispReadOnly(form.M_CONTACT_FAX         , false);
        checkDispReadOnly(form.M_CO_C_FAX_1       , false);
        checkDispReadOnly(form.M_CO_C_FAX_2       , false);
        checkDispReadOnly(form.M_CO_C_FAX_3       , false);
        checkDispReadOnly(form.M_CONTACT_POST        , false);
        checkDispReadOnly(form.M_CO_C_POST_u      , false);
        checkDispReadOnly(form.M_CO_C_POST_l      , false);
        checkDispReadOnly(form.M_CONTACT_STA         , false);
        checkDispReadOnly(form.M_CONTACT_ADR     , false);
        checkDispReadOnly(form.M_CONTACT_ADR2    , false);
        checkDispReadOnly(form.M_CONTACT_AFFILIATION , false);
        checkDispReadOnly(form.M_CONTACT_POSITION    , false);

        checkDispColor(form.M_CONTACT_G_NAME         , "");
        checkDispColor(form.M_CONTACT_G_NAME_KN         , "");
        checkDispColor(form.M_CONTACT_C_NAME         , "");
        checkDispColor(form.M_CONTACT_C_NAME_KN         , "");
        checkDispColor(form.M_CONTACT_EMAIL        , "");
        checkDispColor(form.M_CONTACT_CC_EMAIL     , "");
        checkDispColor(form.M_CO_C_TEL_1        , "");
        checkDispColor(form.M_CO_C_TEL_2        , "");
        checkDispColor(form.M_CO_C_TEL_3        , "");
        checkDispColor(form.M_CO_C_FAX_1        , "");
        checkDispColor(form.M_CO_C_FAX_2        , "");
        checkDispColor(form.M_CO_C_FAX_3        , "");
        checkDispColor(form.M_CO_C_POST_u       , "");
        checkDispColor(form.M_CO_C_POST_l       , "");
        checkDispColor(form.M_CONTACT_STA          , "");
        checkDispColor(form.M_CONTACT_ADR      , "");
        checkDispColor(form.M_CONTACT_ADR2     , "");
        checkDispColor(form.M_CONTACT_AFFILIATION  , "");
        checkDispColor(form.M_CONTACT_POSITION     , "");

        checkDispDisabled(form.search_button1 , false);
      }

      function setBillingEnabled(){
        var form = document.mainForm;

        checkDispReadOnly(form.M_BILLING_G_NAME        , false);
        checkDispReadOnly(form.M_BILLING_G_KANA        , false);
        checkDispReadOnly(form.M_BILLING_G_NAME_EN        , false);
        checkDispReadOnly(form.M_BILLING_C_NAME        , false);
        checkDispReadOnly(form.M_BILLING_C_NAME_KN        , false);
        checkDispReadOnly(form.M_BILLING_EMAIL       , false);
        checkDispReadOnly(form.M_BILLING_CC_EMAIL    , false);
        checkDispReadOnly(form.M_BILLING_TEL         , false);
        checkDispReadOnly(form.M_CL_C_TEL_1       , false);
        checkDispReadOnly(form.M_CL_C_TEL_2       , false);
        checkDispReadOnly(form.M_CL_C_TEL_3       , false);
        checkDispReadOnly(form.M_BILLING_FAX         , false);
        checkDispReadOnly(form.M_CL_C_FAX_1       , false);
        checkDispReadOnly(form.M_CL_C_FAX_2       , false);
        checkDispReadOnly(form.M_CL_C_FAX_3       , false);
        checkDispReadOnly(form.M_BILLING_POST        , false);
        checkDispReadOnly(form.M_CL_C_POST_u      , false);
        checkDispReadOnly(form.M_CL_C_POST_l      , false);
        checkDispReadOnly(form.M_BILLING_STA         , false);
        checkDispReadOnly(form.M_BILLING_ADR     , false);
        checkDispReadOnly(form.M_BILLING_ADR2    , false);
        checkDispReadOnly(form.M_BILLING_AFFILIATION , false);
        checkDispReadOnly(form.M_BILLING_POSITION    , false);

        checkDispColor(form.M_BILLING_G_NAME         , "");
        checkDispColor(form.M_BILLING_G_KANA         , "");
        checkDispColor(form.M_BILLING_G_NAME_EN         , "");
        checkDispColor(form.M_BILLING_C_NAME         , "");
        checkDispColor(form.M_BILLING_C_NAME_KN         , "");
        checkDispColor(form.M_BILLING_EMAIL        , "");
        checkDispColor(form.M_BILLING_CC_EMAIL     , "");
        checkDispColor(form.M_CL_C_TEL_1        , "");
        checkDispColor(form.M_CL_C_TEL_2        , "");
        checkDispColor(form.M_CL_C_TEL_3        , "");
        checkDispColor(form.M_CL_C_FAX_1        , "");
        checkDispColor(form.M_CL_C_FAX_2        , "");
        checkDispColor(form.M_CL_C_FAX_3        , "");
        checkDispColor(form.M_CL_C_POST_u       , "");
        checkDispColor(form.M_CL_C_POST_l       , "");
        checkDispColor(form.M_BILLING_STA          , "");
        checkDispColor(form.M_BILLING_ADR      , "");
        checkDispColor(form.M_BILLING_ADR2     , "");
        checkDispColor(form.M_BILLING_AFFILIATION  , "");
        checkDispColor(form.M_BILLING_POSITION     , "");

        checkDispReadOnly(form.M_BANK_CD      , false);
        checkDispReadOnly(form.M_BRANCH_CD    , false);
        checkDispReadOnly(form.M_ACCAUNT_TYPE   , false);
        checkDispReadOnly(form.M_ACCOUNT_NO , false);
        checkDispReadOnly(form.M_ACCOUNT_NM   , false);
        checkDispReadOnly(form.M_CUST_NO        , false);
        checkDispReadOnly(form.M_SAVINGS_CD   , false);
        checkDispReadOnly(form.M_SAVINGS_NO , false);

        checkDispColor(form.M_BANK_CD       , "");
        checkDispColor(form.M_BRANCH_CD     , "");
        checkDispColor(form.M_ACCAUNT_TYPE    , "");
        checkDispColor(form.M_ACCOUNT_NO  , "");
        checkDispColor(form.M_ACCOUNT_NM    , "");
        checkDispColor(form.M_CUST_NO         , "");
        checkDispColor(form.M_SAVINGS_CD    , "");
        checkDispColor(form.M_SAVINGS_NO  , "");

        checkDispDisabled(form.M_BANK_CD    , false);
        checkDispDisabled(form.M_BRANCH_CD  , false);
        checkDispDisabled(form.search_button2 , false);
        checkDispDisabled(form.search_button3 , false);
        checkDispDisabled(form.search_button_m, false);
        checkDispDisabled(form.M_ACCAUNT_TYPE , false);

      }
      //-->
      </script>

      <script type="text/javascript">
      <!--
      var g_focusItem = null;
      var g_focusElem = null;


      function SetDicItem(itemPos){
        var i;
        var itemName = "";
        for(i=0;i<document.all.length;i++){
          if(document.all[i].name == document.activeElement.name){
            itemName = document.all[i - itemPos].innerHTML;
            break;
          }
        }
        g_focusItem = itemName;
        g_focusElem = document.activeElement.name;
      }

      function ShowDicWnd(url){
        var dicName;
        switch(g_focusItem){
        case "役職":
          dicName = "役職名";
          break;
        }
        return open(url + "?form=document.mainForm&item=" + dicName + "&text=" + g_focusElem,
          'SearchWnd',
          'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
      }

      function ShowDicWnd2(url,dicName,eleName){
        return open(url + "?form=document.mainForm&item=" + dicName + "&text=" + eleName,
          'SearchWnd',
          'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
      }

      

      


    

      function retSearchVal_CheckGid(retSearchVal){
        var form = document.mainForm;
        if(retSearchVal != 0){
          alert("入力された組織IDは既に使用されています。\n別の組織IDを使用してください。");
          return errProc(form.G_G_ID);
        }
      }


      

      function errProc(elem){
        if(elem.type != "hidden"){
          if(elem.type != "select-one") elem.select();
          elem.focus();
        }
        return false;
      }

    

      function retSearchVal_CheckInputData(PCEmail, PCPmail, PPEmail, PPPmail, PPID, GGID){
        var form = document.mainForm;
        var RegCheck = true;

        if(form.NoneRMf != undefined){
          if(form.NoneRMf.value == "1"){
            RegCheck = false;
          }
        }
        if(form.entry_active == undefined){
          if(PPID != 0 && RegCheck == true){
            alert("入力された個人IDは既に使用されています。\n別の個人IDを使用してください。");
            return errProc(form.P_P_ID);
          }

          if(form.P_C_EMAIL.type != "hidden"){
            if(PCEmail != 0){
              alert("入力された個人E-MAILは既に登録されています。\n別の個人E-MAILを入力してください。");
              return errProc(form.P_C_EMAIL);
            }
          }
          if(form.P_C_PMAIL.type != "hidden"){
            if(PCPmail != 0){
              alert("入力された携帯メールは既に登録されています。\n別の携帯メールを入力してください。");
              return errProc(form.P_C_PMAIL);
            }
          }
          if(form.P_P_EMAIL.type != "hidden"){
            if(PPEmail != 0){
              alert("入力されたプライベートE-MAILは既に登録されています。\n別のプライベートE-MAILを入力してください。");
              return errProc(form.P_P_EMAIL);
            }
          }
          if(form.P_P_PMAIL.type != "hidden"){
            if(PPPmail != 0){
              alert("入力されたプライベート携帯メールアドレスは既に登録されています。\n別のプライベート携帯メールアドレスを入力してください。");
              return errProc(form.P_P_PMAIL);
            }
          }

          if(GGID != 0 && RegCheck == true){
            alert("入力された組織IDは既に使用されています。\n別の組織IDを使用してください。");
            return errProc(form.G_G_ID);
          }
        }


        if(form.M_CONTACT_ID != undefined){

          changeContactForm();
        }
        if(form.M_BILLING_ID != undefined){

          changeClaimForm();
        }


        var act_win;
        if(form.NewRegCheckFlg != undefined){
          act_win = "confirm_active_ex.asp";
        }else{

          act_win = "confirm.asp";

        }


        if(form.flgFee.value == '1' && form.M_STATUS.value != '1'){
          form.method = "post";
          form.action = act_win + "?feewin=1";
          form.submit();
        }else{
          form.method = "post";
          form.action = act_win + "";
          form.submit();
        }

      }

      function dispSwitch(mode){
        var form = document.mainForm;
        if(mode=='on'){

          form.FAX_TIME_FROM_H.disabled = false;
          form.FAX_TIME_FROM_N.disabled = false;
          form.FAX_TIME_TO_H.disabled   = false;
          form.FAX_TIME_TO_N.disabled   = false;
        }else{

          form.FAX_TIME_FROM_H.disabled = true;
          form.FAX_TIME_FROM_N.disabled = true;
          form.FAX_TIME_TO_H.disabled   = true;
          form.FAX_TIME_TO_N.disabled   = true;
        }
      }

      function funcHanToZen(obj){
        var str = obj.value;
        var convert = wanakana.toKana(str);
        obj.value = convert;
      }
      function retSearchVal_rsHanToZen(retVal, objname){
        var form = document.mainForm;

        form[objname].value = retVal;

        switch (objname){
        case "P_C_ADR":
        case "P_C_ADR2":
          changeContactForm();
          changeClaimForm();
          break;

        case "P_P_ADR":
        case "P_P_ADR2":
          changeContactForm();
          changeClaimForm();
          break;

        case "G_ADR":
        case "G_ADR2":
          changeContactForm();
          changeClaimForm();
          break;

        case "M_CONTACT_ADR":
        case "M_CONTACT_ADR2":
          changeContactFormValue();
          changeClaimForm();
          break;

        case "M_BILLING_ADR":
        case "M_BILLING_ADR2":
        case "M_BILLING_ADR3":
          changeClaimFormValue();
          changeClaimForm();
          break;

        default:
          break;
        }

      }
      function funcZenToHan(obj){
        var str = obj.value;
        var convert = wanakana.toRomaji(str);
        obj.value = convert;
      }
      function retSearchVal_rsZenToHan(retVal, objname){
        var form = document.mainForm;
        var tmp;

        switch (objname){
        case "G_ACCAUNT_NM":
          tmp = retVal;
          form[objname].value = retVal;
          if(getByte(tmp) != tmp.length){
            alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
            return;
          }

          changeContactForm();
          changeClaimForm();
          break;

        case "P_ACCOUNT_NM":
          tmp = retVal;
          form[objname].value = retVal;
          if(getByte(tmp) != tmp.length){
            alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
            return;
          }

          changeContactForm();
          changeClaimForm();
          break;

        case "M_ACCOUNT_NM":
          tmp = retVal;
          form[objname].value = retVal;
          if(getByte(tmp) != tmp.length){
            alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
            return;
          }

          changeClaimFormValue();
          break;

        default:
          form[objname].value = retVal;
          break;
        }

      }

      function funcHiraganaToKatakana(obj){
        var str = obj.value;
        var convert = wanakana.toKatakana(str);
        obj.value = convert;
      }
      function retSearchVal_rsHiraganaToKatakana(retVal, objname){

        var form = document.mainForm;


        form[objname].value = retVal;

        switch (objname){
        case "P_C_KANA":
        case "G_NAME_KN":
          changeContactForm();
          changeClaimForm();
          break;

        case "M_CONTACT_G_NAME_KN":
        case "M_CONTACT_C_NAME_KN":
          changeContactFormValue();
          changeClaimForm();

        case "M_BILLING_G_KANA":
        case "M_CL_C_KANA":
          changeClaimFormValue();
          changeClaimForm();

        default:
          break;
        }

      }

      

      function getAllItem(){
        var arrAll = [];
        $('#mainForm input').each(
            function(index){  
              var input = $(this);
              var input_type = input.attr('type');
              if((input_type == 'hidden') || (input_type == 'text') || (input_type == 'email')){
                var object = {
                type : 'input',
                input_type : input_type,
                name : input.attr('name'),
                value : input.val()
                };
                arrAll.push(object);
              }else if((input_type == 'radio') || (input_type == 'checkbox')) {
                if (input.is(':checked')) {
                  var object = {
                  type : 'input',
                  input_type : input_type,
                  name : input.attr('name'),
                  value : input.val()
                  };
                  arrAll.push(object);
                }
              }
            }
        );
        $('#mainForm select').each(
            function(index){  
              var input = $(this);
              var object = {
                type : 'select',
                name : input.attr('name'),
                value : input.val()
              };
              arrAll.push(object);
            }
        );
        $('#mainForm textarea').each(
            function(index){  
              var input = $(this);
              var object = {
                type : 'textarea',
                name : input.attr('name'),
                value : input.val()
              };
              arrAll.push(object);
            }
        );
        return arrAll;
      }

      function setAllItem(){
        if ( typeof(Storage) !== "undefined") {
            var dataItem = sessionStorage.getItem('allitem');
            dataItem = JSON.parse(dataItem);
            for(item of dataItem){
              if(item.type == 'textarea'){
                $('textarea[name="'+item.name+'"]').val(item.value);
              }else if(item.type == 'select'){
                $('select[name="'+item.name+'"]').val(item.value);
                // $('select[name="'+item.name+'"] option[value="'+item.value+'"]').prop('selected', true);
              }else if(item.type == 'input'){
                if((item.input_type == 'text') || (item.input_type == 'hidden') || (item.input_type == 'email')){
                  $('input[name="'+item.name+'"]').val(item.value);
                }else if((item.input_type == 'radio') || (item.input_type == 'checkbox')){
                  $('input[name="'+item.name+'"][value="'+item.value+'"]').prop("checked", true);
                }
              }
            }
        }
      }

      jQuery(document).ready(function(){
        if ( typeof(Storage) !== "undefined") {
          var confirm_back = sessionStorage.getItem('confirm_back');
          if(confirm_back == 'back'){
            setAllItem();
          }
        }
      });

    
</script>
  <div class="preLoading">
    <div class="img_loading">
      <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/img/member/Spinner.gif" alt="">
      <span>処理中です...</span>
    </div>
  </div>
  </body>
</html>
