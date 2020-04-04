<?php 
   define('__ROOT__', dirname(dirname(__FILE__)));
   require_once(__ROOT__.'/config/constant.php');
   require_once(__ROOT__.'/controller/memberController.php');
   $members = new memberController();
?>
<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <title>内容確認・更新</title>
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
      <link rel="stylesheet" type="text/css" href="./assets/css/setting_public_item.css">
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/sedai_link.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/inputcheck.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/jquery-1.6.3.min.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/autoKana.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/setting_public_item.js"></script>
   </head>
   <body onload="OnLoad();">
      <div class="container">
         <div class="top-edit">
            <h3 class="DispTitle">
               新規会員登録
            </h3>
         </div>
         <table width="100%" align="left" border="0">
            <tbody>
               <tr>
                  <td align="left">
                     <font size="4" color="darkorange">
                     <strong>【会員公開リストに参加する】</strong>
                     </font>
                  </td>
               </tr>
               <tr>
                  <td align="left">
                     <font size="2" color="#ff0000">
                     ※「ご注意：Internet Explorerのバージョンが「6.0SP1」以下の場合は送信することができません。」
                     </font>
                  </td>
               </tr>
               <tr>
                  <td align="left">
                     <br>
                     下のフォームに表示された内容が現在のご登録内容です。<br>
                     内容確認の結果、変更がない場合は「キャンセス」で終了してください。<br>
                     内容を変更する場合は、変更箇所をプルダウンで選択するか、表示内容の上から直接変更し、最後に「更新」で終了してください。<br>
                     <br>
                  </td>
               </tr>
            </tbody>
         </table>
         <!-- ■■■ 登録画面 ■■■ -->
         <form name="mainForm" id="mainForm" enctype="multipart/form-data">
          <div class="full-w">
              <div class="w-note left">
                <p class="note">
                  ※マークの項目は必須入力です。
                </p>
              </div>
              <ul class="list-button right">
                  <li>
                    <input type="button" value="FelicaID登録" onclick="Javascript:RegistFelicaId(&#39;dmshibuyap0001&#39;);">
                  </li>
                  <li>
                    <?php $page_link = $members->getPageSlug('nakama-member-feestatus');?>
                    <input type="button" value="会費支払状況" onclick="OnFeeStatus('<?php echo $page_link; ?>');">
                  </li>
              </ul>
            </div>
            <p class="text-left">
              入力項目の背景色は前回入力内容との比較です。 <span class="select_note"><span style="color:#CCCCFF;">■</span>：追加　<span style="color:#CCFFCC;">■</span>：変更　<span style="color:#FFCCFF;">■</span>：削除</span>
            </p>
            <table class="input_table" align="center" cellspacing="0" cellpadding="3">
               <tbody>
                  <tr>
                     <td class="RegField" colspan="2">項目名</td>
                     <td class="RegField">記入欄</td>
                     <td class="RegField">公開設定</td>
                  </tr>
                  <tr>
                     <td class="RegGroup" style="font-weight:bold;font-size:12pt;" rowspan="4" width="100">会員意思確認</td>
                     <td colspan="1" class="RegItem" nowrap="">
                        <span style="color: #FF0000">※</span>一般公開会員<br>リスト表示設定
                     </td>
                     <td class="RegValue">
                        一般公開会員リストに表示
                        <span class="select_input">
                          <select name="G_LIST_NODISP" onchange="Javascript: change_listnodisp(this.value);">
                           <option value="1" selected="">する</option>
                           <option value="0">しない</option>
                        </select>
                        &nbsp;
                        <input type="button" class="btn_setting" name="explanation" value="説明" onclick="OnExplanationFile(&#39;/dantai/nakama/html/一般公開会員リスト表示説明.html&#39;);">
                        </span>
                        <br>
                        <font color="red">
                        <br>
                        </font>
                     </td>
                     <td class="RegValue">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="1" class="RegItem" nowrap="">
                        <span style="color: #FF0000">※</span>会員間の企業<br>情報交換の設定
                     </td>
                     <td class="RegValue">
                        会員間の企業情報を交換
                        <span class="select_input">
                          <select name="G_BIZ_INFO">
                           <option value="1" selected="">する</option>
                           <option value="0">しない</option>
                        </select>
                        &nbsp;
                        <input type="button" class="btn_setting" name="explanation" value="説明" onclick="OnExplanationFile(&#39;/dantai/nakama/html/会員間の企業情報交換.html&#39;);"></span>
                        <br>
                        <font color="red">
                        <br>
                        </font>
                     </td>
                     <td class="RegValue">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="1" class="RegItem" nowrap="">
                        <span style="color: #FF0000">※</span>会員一覧からの<br>詳細画面表示設定
                     </td>
                     <td class="RegValue">
                        詳細画面表示
                        <span class="select_input">
                          <select name="G_LIST_NOLINK">
                           <option value="1" selected="">する</option>
                           <option value="0">しない</option>
                        </select>
                        &nbsp;
                        <input type="button" class="btn_setting" name="explanation" value="説明" onclick="OnExplanationFile(&#39;/dantai/nakama/html/会員一覧からの詳細画面表示.html&#39;);">
                        </span>
                        <br>
                        <p class="note">※詳細画面とは、企業情報専用に別途公開するすべてを表示する画面です。</p>
                     </td>
                     <td class="RegValue">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="1" class="RegItem" nowrap="">
                        メルマガ登録
                     </td>
                     <td class="RegValue">
                        <label><input type="radio" name="MLMAGA_G_ID" value="1" checked="">する</label>
                        <label><input type="radio" name="MLMAGA_G_ID" value="0">しない</label>
                        <label class="show_reg">現在 登録済</label>
                     </td>
                     <td class="RegValue">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
            <br>
            <input type="hidden" name="G_MARKETING_FLG" value="0">
            <input type="hidden" name="G_WORKFORCE" value="180">
            <input type="hidden" name="G_PARTTIME" value="59">
            <input type="hidden" name="G_ANNVAL_BUSINESS" value="5783">
            <input type="hidden" name="G_LICENSE" value="">
            <input type="hidden" name="G_PATENT" value="">
            <input type="hidden" name="G_HOLD_CAR" value="71">
            <input type="hidden" name="G_E_TAX" value="0">
            <input type="hidden" name="G_ADD_MARKETING15" value="">
            <input type="hidden" name="G_ADD_MARKETING16" value="">
            <input type="hidden" name="G_NAME" value="（株）恵比寿フラワーショップ（デモ）">
            <input type="hidden" name="G_NAME_HIDDEN" value="1">
            <input type="hidden" name="G_O_NAME" value="1">
            <input type="hidden" name="G_CATEGORY" value="金融業">
            <input type="hidden" name="G_O_CATEGORY" value="1">
            <input type="hidden" name="G_URL" value="http://www.dynax.co.jp/">
            <input type="hidden" name="G_O_URL" value="1">
            <input type="hidden" name="G_TEL_1" value="03">
            <input type="hidden" name="G_TEL_2" value="5488">
            <input type="hidden" name="G_TEL_3" value="7030">
            <input type="hidden" name="G_TEL" value="03-5488-7030">
            <input type="hidden" name="G_O_TEL" value="1">
            <input type="hidden" name="G_FAX_1" value="03">
            <input type="hidden" name="G_FAX_2" value="5488">
            <input type="hidden" name="G_FAX_3" value="7063">
            <input type="hidden" name="G_FAX" value="03-5488-7063">
            <input type="hidden" name="G_O_FAX" value="1">
            <input type="hidden" name="G_CAPITAL" value="773100000">
            <input type="hidden" name="G_O_CAPITAL" value="1">
            <input type="hidden" name="G_POST_u" value="150">
            <input type="hidden" name="G_POST_l" value="0013">
            <input type="hidden" name="G_POST" value="150-0013">
            <input type="hidden" name="G_O_POST" value="0">
            <input type="hidden" name="G_STA" value="東京都">
            <input type="hidden" name="G_STA_HIDDEN" value="1">
            <input type="hidden" name="G_O_STA" value="1">
            <input type="hidden" name="G_ADDRESS" value="渋谷区恵比寿４－１２－１２">
            <input type="hidden" name="G_ADDRESS_HIDDEN" value="1">
            <input type="hidden" name="G_O_ADDRESS" value="1">
            <input type="hidden" name="G_ADDRESS2" value="">
            <input type="hidden" name="G_ADDRESS2_HIDDEN" value="1">
            <input type="hidden" name="G_APPEAL" value="住宅ローン・格安金利">
            <input type="hidden" name="G_O_APPEAL" value="1">
            <input type="hidden" name="P_PASSWORD" value="coco">
            <input type="hidden" name="P_PASSWORD2" value="coco">
            <input type="hidden" name="P_C_NAME" value="岡野　アイ">
            <input type="hidden" name="P_O_NAME" value="1">
            <input type="hidden" name="P_C_KANA" value="オカノ　アイ">
            <input type="hidden" name="P_O_KANA" value="1">
            <input type="hidden" name="P_C_EMAIL" value="test01@dynax.co.jp">
            <input type="hidden" name="P_O_EMAIL" value="1">
            <input type="hidden" name="P_C_PMAIL" value="">
            <input type="hidden" name="P_O_PMAIL" value="1">
            <input type="hidden" name="P_C_PMAIL_URL" value="">
            <input type="hidden" name="P_C_TEL_1" value="03">
            <input type="hidden" name="P_C_TEL_2" value="5488">
            <input type="hidden" name="P_C_TEL_3" value="7030">
            <input type="hidden" name="P_C_TEL" value="03-5488-7030">
            <input type="hidden" name="P_O_TEL" value="1">
            <input type="hidden" name="P_C_FAX_1" value="03">
            <input type="hidden" name="P_C_FAX_2" value="5488">
            <input type="hidden" name="P_C_FAX_3" value="7063">
            <input type="hidden" name="P_C_FAX" value="03-5488-7063">
            <input type="hidden" name="P_O_FAX" value="1">
            <input type="hidden" name="P_C_POST_u" value="150">
            <input type="hidden" name="P_C_POST_l" value="0013">
            <input type="hidden" name="P_C_POST" value="150-0013">
            <input type="hidden" name="P_O_POST" value="1">
            <input type="hidden" name="P_C_STA" value="東京都">
            <input type="hidden" name="P_O_STA" value="1">
            <input type="hidden" name="P_C_ADDRESS" value="渋谷区恵比寿４－１２－１２">
            <input type="hidden" name="P_O_ADDRESS" value="1">
            <input type="hidden" name="P_C_ADDRESS2" value="">
            <input type="hidden" name="M_CONTACTDEST" id="M_CONTACTDEST" value="0">
            <input type="hidden" name="M_CO_C_NAME" value="岡野　アイ" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_KANA" value="オカノ　アイ" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_EMAIL" value="test01@dynax.co.jp" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_TEL_1" value="03" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_TEL_2" value="5488" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_TEL_3" value="7030" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_TEL" value="03-5488-7030" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_FAX_1" value="03" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_FAX_2" value="5488" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_FAX_3" value="7063" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_FAX" value="03-5488-7063" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_POST_u" value="150" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_POST_l" value="0013" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_POST" value="150-0013" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="search_button1">
            <input type="hidden" name="M_CO_C_STA" value="東京都" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_ADDRESS" value="渋谷区恵比寿４－１２－１２" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_C_ADDRESS2" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_MEMBER_TYPE" value="1">
            <input type="hidden" name="G_KANA" value="フラワーショップ">
            <input type="hidden" name="G_O_KANA" value="1">
            <input type="hidden" name="G_EMAIL" value="dmshibuyap0001@cococica.com">
            <input type="hidden" name="G_O_EMAIL" value="1">
            <input type="hidden" name="m_foundImperialG">
            <input type="hidden" name="G_FOUND_YEAR" value="1970">
            <input type="hidden" name="G_FOUND_MONTH" value="12">
            <input type="hidden" name="G_FOUND_DAY" value="16">
            <input type="hidden" name="G_FOUND_DATE" value="1970/12/16">
            <input type="hidden" name="G_O_FOUND_DATE" value="1">
            <input type="hidden" name="G_SETTLE_MONTH" value="12">
            <input type="hidden" name="G_O_SETTLE_MONTH" value="1">
            <input type="hidden" name="G_REPRESENTATIVE" value="岡野　アイ">
            <input type="hidden" name="G_O_REPRESENTATIVE" value="1">
            <input type="hidden" name="G_REPRESENTATIVE_KANA" value="オカノ　アイ">
            <input type="hidden" name="G_O_REPRESENTATIVE_KANA" value="1">
            <input type="hidden" name="G_IMG">
            <input type="hidden" name="m_delImgG" value="">
            <input type="hidden" name="m_curImgG" value="">
            <input type="hidden" name="G_O_IMG" value="0">
            <input type="hidden" name="G_LOGO">
            <input type="hidden" name="m_delLogoG" value="">
            <input type="hidden" name="m_curLogoG" value="">
            <input type="hidden" name="G_O_LOGO" value="0">
            <input type="hidden" name="P_P_HOBBY" value="">
            <input type="hidden" name="P_C_IMG">
            <input type="hidden" name="m_delImgP" value="">
            <input type="hidden" name="m_curImgP" value="Masato02.jpg">
            <input type="hidden" name="P_O_IMG" value="1">
            <input type="hidden" name="P_C_SEX" value="男">
            <input type="hidden" name="P_O_SEX" value="1">
            <input type="hidden" name="P_CARD_OPEN" value="1">
            <input type="hidden" name="FAX_TIMEZONE" value="0">
            <input type="hidden" name="FAX_TIME_FROM_H" value="">
            <input type="hidden" name="FAX_TIME_FROM_N" value="">
            <input type="hidden" name="FAX_TIME_TO_H" value="">
            <input type="hidden" name="FAX_TIME_TO_N" value="">
            <input type="hidden" name="FAX_TIME_FROM" value="">
            <input type="hidden" name="FAX_TIME_TO" value="">
            <input type="hidden" name="G_SNAME" value="space">
            <input type="hidden" name="G_O_SNAME" value="1">
            <input type="hidden" name="G_CATEGORY_CODE" value="0011">
            <input type="hidden" name="G_O_CATEGORY_CODE" value="1">
            <input type="hidden" name="G_G_ID2" value="dmshibuyag0001">
            <input type="hidden" name="G_G_ID" value="dmshibuyag0001">
            <input type="hidden" name="G_PASSWORD" value="dmshibuyag0001">
            <input type="hidden" name="G_PASSWORD2" value="dmshibuyag0001">
            <input type="hidden" name="G_CLASS" value="1">
            <input type="hidden" name="G_O_CLASS" value="1">
            <input type="hidden" name="G_P_URL" value="">
            <input type="hidden" name="G_O_P_URL" value="0">
            <input type="hidden" name="G_CC_EMAIL" value="">
            <input type="hidden" name="G_O_CC_EMAIL" value="0">
            <input type="hidden" name="G_KEYWORD" value="">
            <input type="hidden" name="G_O_KEYWORD" value="0">
            <input type="hidden" name="G_BLOCK" value="">
            <input type="hidden" name="G_O_BLOCK" value="1">
            <input type="hidden" name="G_HOGEN_CODE" value="1">
            <input type="hidden" name="G_O_HOGEN_CODE" value="1">
            <input type="hidden" name="G_BIKOU1" value="">
            <input type="hidden" name="G_O_BIKOU1" value="1">
            <input type="hidden" name="G_BIKOU2" value="">
            <input type="hidden" name="G_O_BIKOU2" value="1">
            <input type="hidden" name="G_BIKOU3" value="">
            <input type="hidden" name="G_O_BIKOU3" value="1">
            <input type="hidden" name="G_BIKOU4" value="">
            <input type="hidden" name="G_O_BIKOU4" value="1">
            <input type="hidden" name="G_BIKOU5" value="">
            <input type="hidden" name="G_O_BIKOU5" value="1">
            <input type="hidden" name="G_BIKOU6" value="">
            <input type="hidden" name="G_O_BIKOU6" value="1">
            <input type="hidden" name="G_BIKOU7" value="">
            <input type="hidden" name="G_O_BIKOU7" value="1">
            <input type="hidden" name="G_BIKOU8" value="3">
            <input type="hidden" name="G_O_BIKOU8" value="1">
            <input type="hidden" name="G_BIKOU9" value="水">
            <input type="hidden" name="G_O_BIKOU9" value="1">
            <input type="hidden" name="G_BIKOU10" value="">
            <input type="hidden" name="G_O_BIKOU10" value="1">
            <input type="hidden" name="G_BIKOU11" value="">
            <input type="hidden" name="G_O_BIKOU11" value="1">
            <input type="hidden" name="G_BIKOU12" value="">
            <input type="hidden" name="G_O_BIKOU12" value="0">
            <input type="hidden" name="G_BIKOU13" value="">
            <input type="hidden" name="G_O_BIKOU13" value="0">
            <input type="hidden" name="G_BIKOU14" value="">
            <input type="hidden" name="G_O_BIKOU14" value="0">
            <input type="hidden" name="G_BIKOU15" value="">
            <input type="hidden" name="G_O_BIKOU15" value="0">
            <input type="hidden" name="G_BIKOU16" value="">
            <input type="hidden" name="G_O_BIKOU16" value="0">
            <input type="hidden" name="G_BIKOU17" value="">
            <input type="hidden" name="G_O_BIKOU17" value="0">
            <input type="hidden" name="G_BIKOU18" value="">
            <input type="hidden" name="G_O_BIKOU18" value="0">
            <input type="hidden" name="G_BIKOU19" value="">
            <input type="hidden" name="G_O_BIKOU19" value="0">
            <input type="hidden" name="G_BIKOU20" value="">
            <input type="hidden" name="G_O_BIKOU20" value="0">
            <input type="hidden" name="G_BIKOU21" value="">
            <input type="hidden" name="G_O_BIKOU21" value="0">
            <input type="hidden" name="G_BIKOU22" value="">
            <input type="hidden" name="G_O_BIKOU22" value="0">
            <input type="hidden" name="G_BIKOU23" value="">
            <input type="hidden" name="G_O_BIKOU23" value="0">
            <input type="hidden" name="G_BIKOU24" value="">
            <input type="hidden" name="G_O_BIKOU24" value="0">
            <input type="hidden" name="G_BIKOU25" value="">
            <input type="hidden" name="G_O_BIKOU25" value="0">
            <input type="hidden" name="G_BIKOU26" value="">
            <input type="hidden" name="G_O_BIKOU26" value="0">
            <input type="hidden" name="G_BIKOU27" value="">
            <input type="hidden" name="G_O_BIKOU27" value="0">
            <input type="hidden" name="G_BIKOU28" value="">
            <input type="hidden" name="G_O_BIKOU28" value="0">
            <input type="hidden" name="G_BIKOU29" value="">
            <input type="hidden" name="G_O_BIKOU29" value="0">
            <input type="hidden" name="G_BIKOU30" value="">
            <input type="hidden" name="G_O_BIKOU30" value="0">
            <input type="hidden" name="G_BANK_CODE" value="0001">
            <input type="hidden" name="G_BRANCH_CODE" value="001">
            <input type="hidden" name="G_ACCAUNT_TYPE" value="1">
            <input type="hidden" name="G_ACCAUNT_NUMBER" value="1234567">
            <input type="hidden" name="G_ACCAUNT_NAME" value="DYNAX">
            <input type="hidden" name="G_CUST_NO" value="333333333">
            <input type="hidden" name="G_SAVINGS_CODE" value="">
            <input type="hidden" name="G_SAVINGS_NUMBER" value="">
            <input type="hidden" name="G_ADD_MARKETING1" value="">
            <input type="hidden" name="G_ADD_MARKETING2" value="">
            <input type="hidden" name="G_ADD_MARKETING3" value="">
            <input type="hidden" name="G_ADD_MARKETING4" value="">
            <input type="hidden" name="G_ADD_MARKETING5" value="">
            <input type="hidden" name="G_ADD_MARKETING6" value="">
            <input type="hidden" name="G_ADD_MARKETING7" value="">
            <input type="hidden" name="G_ADD_MARKETING8" value="">
            <input type="hidden" name="G_ADD_MARKETING9" value="">
            <input type="hidden" name="G_ADD_MARKETING10" value="">
            <input type="hidden" name="G_ADD_MARKETING11" value="">
            <input type="hidden" name="G_ADD_MARKETING12" value="">
            <input type="hidden" name="G_ADD_MARKETING13" value="">
            <input type="hidden" name="G_ADD_MARKETING14" value="">
            <input type="hidden" name="G_ADD_MARKETING17" value="">
            <input type="hidden" name="G_ADD_MARKETING18" value="">
            <input type="hidden" name="G_ADD_MARKETING19" value="">
            <input type="hidden" name="G_ADD_MARKETING20" value="">
            <input type="hidden" name="P_P_ID" value="dmshibuyap0001">
            <input type="hidden" name="P_P_CLASS" value="1">
            <input type="hidden" name="P_C_URL" value="http://www.dynax.co.jp/sample11">
            <input type="hidden" name="P_O_URL" value="1">
            <input type="hidden" name="P_C_CC_EMAIL" value="">
            <input type="hidden" name="P_O_C_CC_EMAIL" value="0">
            <input type="hidden" name="P_C_PTEL_1" value="">
            <input type="hidden" name="P_C_PTEL_2" value="">
            <input type="hidden" name="P_C_PTEL_3" value="">
            <input type="hidden" name="P_C_PTEL" value="">
            <input type="hidden" name="P_O_PTEL" value="1">
            <input type="hidden" name="P_C_IMG2">
            <input type="hidden" name="m_delImgP2" value="">
            <input type="hidden" name="m_curImgP2" value="KEDUKA.jpg">
            <input type="hidden" name="P_O_IMG2" value="0">
            <input type="hidden" name="P_C_IMG3">
            <input type="hidden" name="m_delImgP3" value="">
            <input type="hidden" name="m_curImgP3" value="igaiga.jpg">
            <input type="hidden" name="P_O_IMG3" value="0">
            <input type="hidden" name="P_C_APPEAL" value="住宅ローン・格安金利">
            <input type="hidden" name="P_O_APPEAL" value="1">
            <input type="hidden" name="P_C_KEYWORD" value="">
            <input type="hidden" name="P_C_BIKOU1" value="">
            <input type="hidden" name="P_O_BIKOU1" value="1">
            <input type="hidden" name="P_C_BIKOU2" value="">
            <input type="hidden" name="P_O_BIKOU2" value="1">
            <input type="hidden" name="P_C_BIKOU3" value="">
            <input type="hidden" name="P_O_BIKOU3" value="1">
            <input type="hidden" name="P_C_BIKOU4" value="">
            <input type="hidden" name="P_O_BIKOU4" value="1">
            <input type="hidden" name="P_C_BIKOU5" value="">
            <input type="hidden" name="P_O_BIKOU5" value="1">
            <input type="hidden" name="P_C_BIKOU6" value="">
            <input type="hidden" name="P_O_BIKOU6" value="1">
            <input type="hidden" name="P_C_BIKOU7" value="">
            <input type="hidden" name="P_O_BIKOU7" value="1">
            <input type="hidden" name="P_C_BIKOU8" value="">
            <input type="hidden" name="P_O_BIKOU8" value="1">
            <input type="hidden" name="P_C_BIKOU9" value="">
            <input type="hidden" name="P_O_BIKOU9" value="1">
            <input type="hidden" name="P_C_BIKOU10" value="">
            <input type="hidden" name="P_O_BIKOU10" value="1">
            <input type="hidden" name="P_C_BIKOU11" value="">
            <input type="hidden" name="P_O_BIKOU11" value="0">
            <input type="hidden" name="P_C_BIKOU12" value="">
            <input type="hidden" name="P_O_BIKOU12" value="0">
            <input type="hidden" name="P_C_BIKOU13" value="">
            <input type="hidden" name="P_O_BIKOU13" value="0">
            <input type="hidden" name="P_C_BIKOU14" value="">
            <input type="hidden" name="P_O_BIKOU14" value="0">
            <input type="hidden" name="P_C_BIKOU15" value="">
            <input type="hidden" name="P_O_BIKOU15" value="0">
            <input type="hidden" name="P_C_BIKOU16" value="">
            <input type="hidden" name="P_O_BIKOU16" value="0">
            <input type="hidden" name="P_C_BIKOU17" value="">
            <input type="hidden" name="P_O_BIKOU17" value="0">
            <input type="hidden" name="P_C_BIKOU18" value="">
            <input type="hidden" name="P_O_BIKOU18" value="0">
            <input type="hidden" name="P_C_BIKOU19" value="">
            <input type="hidden" name="P_O_BIKOU19" value="0">
            <input type="hidden" name="P_C_BIKOU20" value="">
            <input type="hidden" name="P_O_BIKOU20" value="0">
            <input type="hidden" name="P_C_BIKOU21" value="">
            <input type="hidden" name="P_O_BIKOU21" value="0">
            <input type="hidden" name="P_C_BIKOU22" value="">
            <input type="hidden" name="P_O_BIKOU22" value="0">
            <input type="hidden" name="P_C_BIKOU23" value="">
            <input type="hidden" name="P_O_BIKOU23" value="0">
            <input type="hidden" name="P_C_BIKOU24" value="">
            <input type="hidden" name="P_O_BIKOU24" value="0">
            <input type="hidden" name="P_C_BIKOU25" value="">
            <input type="hidden" name="P_O_BIKOU25" value="0">
            <input type="hidden" name="P_C_BIKOU26" value="">
            <input type="hidden" name="P_O_BIKOU26" value="0">
            <input type="hidden" name="P_C_BIKOU27" value="">
            <input type="hidden" name="P_O_BIKOU27" value="0">
            <input type="hidden" name="P_C_BIKOU28" value="">
            <input type="hidden" name="P_O_BIKOU28" value="0">
            <input type="hidden" name="P_C_BIKOU29" value="">
            <input type="hidden" name="P_O_BIKOU29" value="0">
            <input type="hidden" name="P_C_BIKOU30" value="">
            <input type="hidden" name="P_O_BIKOU30" value="0">
            <input type="hidden" name="P_GROUP_ENABLE" value="0">
            <input type="hidden" name="P_MEETING_NAME_DISP" value="0">
            <input type="hidden" name="P_HANDLE_NAME" value="">
            <input type="hidden" name="P_MEETING_NAME_MARK" value="">
            <input type="hidden" name="m_selGid" value="dmshibuyap0001">
            <input type="hidden" name="P_O_G_ID" value="1">
            <input type="hidden" name="P_LOGIN_LOCK" value="0">
            <input type="hidden" name="P_BANK_CODE" value="">
            <input type="hidden" name="P_BRANCH_CODE" value="">
            <input type="hidden" name="P_ACCAUNT_TYPE" value="">
            <input type="hidden" name="P_ACCAUNT_NUMBER" value="">
            <input type="hidden" name="P_ACCAUNT_NAME" value="">
            <input type="hidden" name="P_CUST_NO" value="">
            <input type="hidden" name="P_SAVINGS_CODE" value="">
            <input type="hidden" name="P_SAVINGS_NUMBER" value="">
            <input type="hidden" name="P_P_URL" value="">
            <input type="hidden" name="P_P_BLOOD" value="">
            <input type="hidden" name="P_P_EMAIL" value="">
            <input type="hidden" name="P_P_CC_EMAIL" value="">
            <input type="hidden" name="P_P_TEL_1" value="">
            <input type="hidden" name="P_P_TEL_2" value="">
            <input type="hidden" name="P_P_TEL_3" value="">
            <input type="hidden" name="P_P_TEL" value="">
            <input type="hidden" name="P_P_FAX_1" value="">
            <input type="hidden" name="P_P_FAX_2" value="">
            <input type="hidden" name="P_P_FAX_3" value="">
            <input type="hidden" name="P_P_FAX" value="">
            <input type="hidden" name="P_P_PTEL_1" value="">
            <input type="hidden" name="P_P_PTEL_2" value="">
            <input type="hidden" name="P_P_PTEL_3" value="">
            <input type="hidden" name="P_P_PTEL" value="">
            <input type="hidden" name="P_P_PMAIL" value="">
            <input type="hidden" name="P_P_POST_u" value="">
            <input type="hidden" name="P_P_POST_l" value="">
            <input type="hidden" name="P_P_POST" value="">
            <input type="hidden" name="P_P_STA" value="">
            <input type="hidden" name="P_P_ADDRESS" value="">
            <input type="hidden" name="P_P_ADDRESS2" value="">
            <input type="hidden" name="m_birthImperialP">
            <input type="hidden" name="P_P_BIRTH_YEAR" value="">
            <input type="hidden" name="P_P_BIRTH_MONTH" value="">
            <input type="hidden" name="P_P_BIRTH_DAY" value="">
            <input type="hidden" name="P_P_BIRTH" value="">
            <input type="hidden" name="P_P_FAMILY" value="">
            <input type="hidden" name="m_mournStartImperialP">
            <input type="hidden" name="P_P_MOURNING_DATE_START_YEAR" value="">
            <input type="hidden" name="P_P_MOURNING_DATE_START_MONTH" value="">
            <input type="hidden" name="P_P_MOURNING_DATE_START_DAY" value="">
            <input type="hidden" name="P_P_MOURNING_DATE_START" value="">
            <input type="hidden" name="m_mournEndImperialP">
            <input type="hidden" name="P_P_MOURNING_DATE_END_YEAR" value="">
            <input type="hidden" name="P_P_MOURNING_DATE_END_MONTH" value="">
            <input type="hidden" name="P_P_MOURNING_DATE_END_DAY" value="">
            <input type="hidden" name="P_P_MOURNING_DATE_END" value="">
            <input type="hidden" name="P_P_GRADUATION_YEAR" value="">
            <input type="hidden" name="P_P_DEPARTMENT" value="">
            <input type="hidden" name="P_P_GRADUATION_POSITION" value="">
            <input type="hidden" name="P_P_COUNTRY" value="">
            <input type="hidden" name="P_PRIVATE_OPEN" value="0">
            <input type="hidden" name="M_LG_G_ID_SEL" value="dmshibuyablock03">
            <input type="hidden" name="M_LG_G_ID" value="dmshibuyablock03">
            <input type="hidden" name="M_LG_NAME" value="">
            <input type="hidden" name="M_R_ID" value="">
            <input type="hidden" name="M_X_COMMENT" value="">
            <input type="hidden" name="S_AFFILIATION_NAME" value="">
            <input type="hidden" name="P_O_AFFILIATION" value="1">
            <input type="hidden" name="S_OFFICIAL_POSITION" value="会長">
            <input type="hidden" name="P_O_OFFICIAL" value="1">
            <input type="hidden" name="GM_BIKOU1" value="">
            <input type="hidden" name="GM_BIKOU2" value="">
            <input type="hidden" name="GM_BIKOU3" value="">
            <input type="hidden" name="GM_BIKOU4" value="">
            <input type="hidden" name="GM_BIKOU5" value="">
            <input type="hidden" name="GM_BIKOU6" value="">
            <input type="hidden" name="GM_BIKOU7" value="">
            <input type="hidden" name="GM_BIKOU8" value="">
            <input type="hidden" name="GM_BIKOU9" value="">
            <input type="hidden" name="GM_BIKOU10" value="">
            <input type="hidden" name="GM_BIKOU11" value="">
            <input type="hidden" name="GM_BIKOU12" value="">
            <input type="hidden" name="GM_BIKOU13" value="">
            <input type="hidden" name="GM_BIKOU14" value="">
            <input type="hidden" name="GM_BIKOU15" value="">
            <input type="hidden" name="GM_BIKOU16" value="">
            <input type="hidden" name="GM_BIKOU17" value="">
            <input type="hidden" name="GM_BIKOU18" value="">
            <input type="hidden" name="GM_BIKOU19" value="">
            <input type="hidden" name="GM_BIKOU20" value="">
            <input type="hidden" name="GM_BIKOU21" value="">
            <input type="hidden" name="GM_BIKOU22" value="">
            <input type="hidden" name="GM_BIKOU23" value="">
            <input type="hidden" name="GM_BIKOU24" value="">
            <input type="hidden" name="GM_BIKOU25" value="">
            <input type="hidden" name="GM_BIKOU26" value="">
            <input type="hidden" name="GM_BIKOU27" value="">
            <input type="hidden" name="GM_BIKOU28" value="">
            <input type="hidden" name="GM_BIKOU29" value="">
            <input type="hidden" name="GM_BIKOU30" value="">
            <input type="hidden" name="GM_BIKOU31" value="">
            <input type="hidden" name="GM_BIKOU32" value="">
            <input type="hidden" name="GM_BIKOU33" value="">
            <input type="hidden" name="GM_BIKOU34" value="">
            <input type="hidden" name="GM_BIKOU35" value="">
            <input type="hidden" name="GM_BIKOU36" value="">
            <input type="hidden" name="GM_BIKOU37" value="">
            <input type="hidden" name="GM_BIKOU38" value="">
            <input type="hidden" name="GM_BIKOU39" value="">
            <input type="hidden" name="GM_BIKOU40" value="">
            <input type="hidden" name="GM_BIKOU41" value="">
            <input type="hidden" name="GM_BIKOU42" value="">
            <input type="hidden" name="GM_BIKOU43" value="">
            <input type="hidden" name="GM_BIKOU44" value="">
            <input type="hidden" name="GM_BIKOU45" value="">
            <input type="hidden" name="GM_BIKOU46" value="">
            <input type="hidden" name="GM_BIKOU47" value="">
            <input type="hidden" name="GM_BIKOU48" value="">
            <input type="hidden" name="GM_BIKOU49" value="">
            <input type="hidden" name="GM_BIKOU50" value="">
            <input type="hidden" name="M_STATUS" value="1">
            <input type="hidden" name="m_admImperialM" value="">
            <input type="hidden" name="M_ADMISSION_DATE_Y" value="2001">
            <input type="hidden" name="M_ADMISSION_DATE_M" value="3">
            <input type="hidden" name="M_ADMISSION_DATE_D" value="1">
            <input type="hidden" name="M_ADMISSION_DATE" value="2001/03/01">
            <input type="hidden" name="HDN_ADMISSION_DATE_Y" value="2001">
            <input type="hidden" name="HDN_ADMISSION_DATE_M" value="3">
            <input type="hidden" name="HDN_ADMISSION_DATE_D" value="1">
            <input type="hidden" name="HDN_ADMISSION_NAME" value="入会年月日">
            <input type="hidden" name="m_witImperialM" value="">
            <input type="hidden" name="M_WITHDRAWAL_DATE_Y" value="2014">
            <input type="hidden" name="M_WITHDRAWAL_DATE_M" value="12">
            <input type="hidden" name="M_WITHDRAWAL_DATE_D" value="28">
            <input type="hidden" name="M_WITHDRAWAL_DATE" value="2014/12/28">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_Y" value="2014">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_M" value="12">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_D" value="28">
            <input type="hidden" name="HDN_WITHDRAWAL_NAME" value="退会年月日">
            <input type="hidden" name="m_chaImperialM" value="">
            <input type="hidden" name="M_CHANGE_DATE_Y" value="">
            <input type="hidden" name="M_CHANGE_DATE_M" value="">
            <input type="hidden" name="M_CHANGE_DATE_D" value="">
            <input type="hidden" name="M_CHANGE_DATE" value="">
            <input type="hidden" name="M_CHANGE_REASON" value="">
            <input type="hidden" name="M_CLAIM_CLASS" value="1">
            <input type="hidden" name="M_FEE_RANK" value="C">
            <input type="hidden" name="M_CLAIM_CYCLE" value="12">
            <input type="hidden" name="M_FEE_MEMO" value="">
            <input type="hidden" name="M_ENTRUST_CODE" value="">
            <input type="hidden" name="M_BANK_CODE" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_BRANCH_CODE" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_ACCAUNT_TYPE" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_ACCAUNT_NUMBER" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_ACCAUNT_NAME" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CUST_NO" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_SAVINGS_CODE" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_SAVINGS_NUMBER" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CLAIMDEST" id="M_CLAIMDEST" value="0">
            <input type="hidden" name="M_CL_G_NAME" value="（株）恵比寿フラワーショップ（デモ）" style="">
            <input type="hidden" name="M_CL_G_KANA" value="フラワーショップ" style="">
            <input type="hidden" name="M_CL_C_NAME" value="岡野　アイ" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_KANA" value="オカノ　アイ" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_AFFILIATION" value="" style="">
            <input type="hidden" name="M_CL_C_POSITION" value="会長" style="">
            <input type="hidden" name="M_CL_C_EMAIL" value="test01@dynax.co.jp" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_CC_EMAIL" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_TEL_1" value="03" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_TEL_2" value="5488" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_TEL_3" value="7030" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_TEL" value="03-5488-7030" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_FAX_1" value="03" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_FAX_2" value="5488" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_FAX_3" value="7063" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_FAX" value="03-5488-7063" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_POST_u" value="150" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_POST_l" value="0013" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_POST" value="150-0013" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="search_button2">
            <input type="hidden" name="M_CL_C_STA" value="東京都" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_ADDRESS" value="渋谷区恵比寿４－１２－１２" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CL_C_ADDRESS2" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="M_CO_G_NAME" value="（株）恵比寿フラワーショップ（デモ）" style="">
            <input type="hidden" name="M_CO_G_KANA" value="フラワーショップ" style="">
            <input type="hidden" name="M_CO_C_AFFILIATION" value="" style="">
            <input type="hidden" name="M_CO_C_POSITION" value="会長" style="">
            <input type="hidden" name="M_CO_C_CC_EMAIL" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="ATENA_SUU" value="1">
            <input type="hidden" name="M_RECEIVE_INFO_MAIL" value="1">
            <input type="hidden" name="M_RECEIVE_INFO_FAX" value="1">
            <input type="hidden" name="M_P_ID" value="dmshibuyap0001">
            <input type="hidden" name="M_A_ID" value="dmshibuyag0001">
            <input type="hidden" name="M_C_ID" value="dmshibuyap0001">
            <input type="hidden" name="M_CONTACT_ID" value="dmshibuyap0001">
            <table style="display:none;">
               <tbody>
                  <tr>
                     <td id="m_claimNewNeed"></td>
                     <td id="m_contactNewNeed"></td>
                     <td id="m_mainFeeInfo"></td>
                     <td id="m_claimClassNeed"></td>
                  </tr>
               </tbody>
            </table>
            <ul class="ul-common">
               <li>
                 <input type="button" class="base_button" value="キャンセル" onclick="javascrpt:window.close();">
               </li>
               <li>
                 <input type="button" class="base_button" value="更　新" onclick="OnConfirm();">
               </li>
               <li>
                  <input type="button" class="base_button" value="登録解除" onclick="OnRelease();">
                   <input type="checkbox" name="m_saveHist" style="display: none;" value="1" checked="">
               </li>
             </ul>
            <input type="checkbox" name="useRegisteredGid" style="display:none;">
            <input type="checkbox" name="useRegisteredPid" style="display:none;">
            <input type="button" name="useGroupInfo" style="display:none;">
            <input type="hidden" name="flgFee" value="0">
            <input type="hidden" name="k_top_gid" value="dmshibuya">
            <input type="hidden" name="k_gid" value="dmshibuyag0001">
            <input type="hidden" name="k_pid" value="dmshibuyap0001">
            <input type="hidden" name="strToday" value="20181219">
            <input type="hidden" name="m_lg_g_id_old" value="dmshibuyablock03">
            <input type="hidden" name="set_lg_g_id" value="">
            <input type="hidden" name="set_g_id" value="">
            <input type="hidden" name="m_chg" value="1">
            <input type="hidden" name="forward_mail" value="demo@dynax.co.jp">
            <input type="hidden" name="release" value="">
            <input type="hidden" name="mail_flg" value="0">
            <input type="hidden" name="g_g_id_old" value="dmshibuyag0001">
            <input type="hidden" name="page_no" value="112">
            <input type="hidden" name="S_BRANCH_CODE" value="" readonly="" style="background-color: rgb(255, 255, 204);">
            <input type="hidden" name="S_BRANCH_CODE2" value="">
            <input type="hidden" name="S_BRANCH_CODE3" value="">
            <input type="hidden" name="df_co_email" value="test01@dynax.co.jp">
            <input type="hidden" name="mobile_flg" value="1">
            <input type="hidden" name="elist" value="">
            <input type="hidden" name="ActiveRf" value="0">
            <input type="hidden" name="NoneRMf" value="0">
            <input type="hidden" name="clNew_M_CL_G_NAME" value="（株）恵比寿フラワーショップ（デモ）">
            <input type="hidden" name="clNew_M_CL_G_KANA" value="フラワーショップ">
            <input type="hidden" name="clNew_M_CL_C_NAME" value="岡野　アイ">
            <input type="hidden" name="clNew_M_CL_C_KANA" value="オカノ　アイ">
            <input type="hidden" name="clNew_M_CL_C_AFFILIATION" value="">
            <input type="hidden" name="clNew_M_CL_C_POSITION" value="会長">
            <input type="hidden" name="clNew_M_CL_C_EMAIL" value="test01@dynax.co.jp">
            <input type="hidden" name="clNew_M_CL_C_CC_EMAIL" value="">
            <input type="hidden" name="clNew_M_CL_C_TEL" value="03-5488-7030">
            <input type="hidden" name="clNew_M_CL_C_TEL_1" value="03">
            <input type="hidden" name="clNew_M_CL_C_TEL_2" value="5488">
            <input type="hidden" name="clNew_M_CL_C_TEL_3" value="7030">
            <input type="hidden" name="clNew_M_CL_C_FAX" value="03-5488-7063">
            <input type="hidden" name="clNew_M_CL_C_FAX_1" value="03">
            <input type="hidden" name="clNew_M_CL_C_FAX_2" value="5488">
            <input type="hidden" name="clNew_M_CL_C_FAX_3" value="7063">
            <input type="hidden" name="clNew_M_CL_C_POST" value="150-0013">
            <input type="hidden" name="clNew_M_CL_C_POST_u" value="150">
            <input type="hidden" name="clNew_M_CL_C_POST_l" value="0013">
            <input type="hidden" name="clNew_M_CL_C_STA" value="東京都">
            <input type="hidden" name="clNew_M_CL_C_ADDRESS" value="渋谷区恵比寿４－１２－１２">
            <input type="hidden" name="clNew_M_CL_C_ADDRESS2" value="">
            <input type="hidden" name="clNew_M_ACCAUNT_TYPE" value="">
            <input type="hidden" name="clNew_M_ACCAUNT_NUMBER" value="">
            <input type="hidden" name="clNew_M_ACCAUNT_NAME" value="">
            <input type="hidden" name="clNew_M_CUST_NO" value="">
            <input type="hidden" name="clNew_M_SAVINGS_CODE" value="">
            <input type="hidden" name="clNew_M_SAVINGS_NUMBER" value="">
            <input type="hidden" name="clNew_M_BANK_CODE" value="">
            <input type="hidden" name="clNew_M_BRANCH_CODE" value="">
            <input type="hidden" name="clNew_S_BRANCH_CODE" value="">
            <input type="hidden" name="coNew_M_CO_G_NAME" value="（株）恵比寿フラワーショップ（デモ）">
            <input type="hidden" name="coNew_M_CO_G_KANA" value="フラワーショップ">
            <input type="hidden" name="coNew_M_CO_C_NAME" value="岡野　アイ">
            <input type="hidden" name="coNew_M_CO_C_KANA" value="オカノ　アイ">
            <input type="hidden" name="coNew_M_CO_C_AFFILIATION" value="">
            <input type="hidden" name="coNew_M_CO_C_POSITION" value="会長">
            <input type="hidden" name="coNew_M_CO_C_EMAIL" value="test01@dynax.co.jp">
            <input type="hidden" name="coNew_M_CO_C_CC_EMAIL" value="">
            <input type="hidden" name="coNew_M_CO_C_TEL" value="03-5488-7030">
            <input type="hidden" name="coNew_M_CO_C_TEL_1" value="03">
            <input type="hidden" name="coNew_M_CO_C_TEL_2" value="5488">
            <input type="hidden" name="coNew_M_CO_C_TEL_3" value="7030">
            <input type="hidden" name="coNew_M_CO_C_FAX" value="03-5488-7063">
            <input type="hidden" name="coNew_M_CO_C_FAX_1" value="03">
            <input type="hidden" name="coNew_M_CO_C_FAX_2" value="5488">
            <input type="hidden" name="coNew_M_CO_C_FAX_3" value="7063">
            <input type="hidden" name="coNew_M_CO_C_POST" value="150-0013">
            <input type="hidden" name="coNew_M_CO_C_POST_u" value="150">
            <input type="hidden" name="coNew_M_CO_C_POST_l" value="0013">
            <input type="hidden" name="coNew_M_CO_C_STA" value="東京都">
            <input type="hidden" name="coNew_M_CO_C_ADDRESS" value="渋谷区恵比寿４－１２－１２">
            <input type="hidden" name="coNew_M_CO_C_ADDRESS2" value="">
            <iframe name="getData" src="./setting_public_item_files/no.html" style="display:none"></iframe>
            <iframe name="getData2" src="./setting_public_item_files/no(1).html" style="display:none"></iframe>
            <iframe name="getData3" src="./setting_public_item_files/no(2).html" style="display:none"></iframe>
            <iframe name="getData4" src="./setting_public_item_files/no(3).html" style="display:none"></iframe>
            <iframe name="getData5" src="./setting_public_item_files/no(4).html" style="display:none"></iframe>
            <iframe name="getData6" src="./setting_public_item_files/no(5).html" style="display:none"></iframe>
         </form>
      </div>
   </body>
</html>