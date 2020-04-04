<?php
   define('__ROOT__', dirname(dirname(__FILE__))); 
   require_once(__ROOT__.'/config/constant.php'); 
   require_once(__ROOT__.'/controller/memberController.php'); 
   $members = new memberController();
   $P_ID = isset($_GET['p_id'])?$_GET['p_id']:'';
   $M_ID = isset($_GET['m_id'])?$_GET['m_id']:'';
   $TG_ID = isset($_GET['tg_id'])?$_GET['tg_id']:'';
   $postid = isset($_GET['postid'])?$_GET['postid']:'';
   $memberDetails = $members->memberDetails($postid, $M_ID, $TG_ID);
   $arrTitle = $members->SelectTitleN($postid,$TG_ID);
   $d_organiztion = $memberDetails->D_ORGANIZATION;
   $d_personal = $memberDetails->D_PERSONAL;
   $d_member = $memberDetails->D_MEMBER;
   $d_affiliation = $memberDetails->D_AFFILIATION;
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <title>個人詳細</title>
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/detail_member.css">
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/PersonalDetail.js"></script>
   </head>
   <?php
       
   ?>
   <body>
      <div class="container">
        <form id="mainForm" name="mainForm">
         <input type="button" class="button_close" value="閉じる" onclick="window.close();">
         &nbsp;&nbsp;&nbsp;&nbsp;
         <br>
         <br>
         <table align="center" border="0" cellspacing="1">
            <tbody>
               <tr>
                  <td colspamn="2">■組織データ</td>
               </tr>
               <tr>
               </tr>
            </tbody>
         </table>
         <table align="center" border="0" cellspacing="1">
            <tbody>
               <?php if(!empty($d_member->G_ID)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織ID')), '組織ID'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->G_ID; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_NAME)): ?>
                  <?php if($d_organiztion->O_G_NAME == 1 || ($d_organiztion->O_G_NAME == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織名')), '組織名'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_NAME; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_NAME_KN)): ?>
                  <?php if($d_organiztion->O_G_NAME_KN == 1 || ($d_organiztion->O_G_NAME_KN == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織フリガナ')), '組織フリガナ'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_NAME_KN; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->S_NAME)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織フリガナ')), '組織フリガナ'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->S_NAME; ?></td>
               </tr>
               <?php endif; ?>
               <?php if($d_organiztion->INDUSTRY_CD != ""): ?>
                  <?php if($d_organiztion->O_INDUSTRY_CD == 1 || ($d_organiztion->O_INDUSTRY_CD == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'業種コード')), '業種コード'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->INDUSTRY_CD; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(empty($d_organiztion->INDUSTRY_NM)): ?>
                  <?php if($d_organiztion->O_INDUSTRY_NM == 1 || ($d_organiztion->O_INDUSTRY_NM == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'業種')), '業種'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->INDUSTRY_NM; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_URL)): ?>
                  <?php if($d_organiztion->O_G_URL == 1 || ($d_organiztion->O_G_URL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織URL')), '組織URL'); ?></td>
                     <td class="DetailValue"><a href="<?php echo $d_organiztion->G_URL; ?>" target="_blank">
                        <?php echo $d_organiztion->G_URL; ?></a>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_P_URL)): ?>
                  <?php if($d_organiztion->O_G_P_URL == 1 || ($d_organiztion->O_G_P_URL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織携帯URL')), '組織携帯URL'); ?></td>
                     <td class="DetailValue"><a href="<?php echo $d_organiztion->G_P_URL; ?>" target="_blank">
                        <?php echo $d_organiztion->G_P_URL; ?></a>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_EMAIL)): ?>
                  <?php if($d_organiztion->O_G_EMAIL == 1 || ($d_organiztion->O_G_EMAIL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織E-MAIL')), '組織E-MAIL'); ?></td>
                     <td class="DetailValue">
                        <a href="mailto:<?php echo $d_organiztion->G_EMAIL; ?>">
                           <?php echo $d_organiztion->G_EMAIL; ?>
                        </a>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_CC_EMAIL)): ?>
                  <?php if($d_organiztion->O_G_CC_EMAIL == 1 || ($d_organiztion->O_G_CC_EMAIL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織追加<br>送信先E-MAIL')), '組織追加<br>送信先E-MAIL'); ?></td>
                     <td class="DetailValue">
                        <a href="mailto:<?php echo $d_organiztion->G_CC_EMAIL; ?>">
                           <?php echo $d_organiztion->G_CC_EMAIL; ?>
                        </a>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_TEL)): ?>
                  <?php if($d_organiztion->O_G_TEL == 1 || ($d_organiztion->O_G_TEL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織TEL')), '組織TEL'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_TEL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_FAX)): ?>
                  <?php if($d_organiztion->O_G_FAX == 1 || ($d_organiztion->O_G_FAX == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織FAX')), '組織FAX'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_FAX; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->FOUND_DATE)): ?>
                  <?php if($d_organiztion->O_FOUND_DATE == 1 || ($d_organiztion->O_FOUND_DATE == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'設立年月日')), '設立年月日'); ?></td>
                     <td class="DetailValue">
                        <?php echo $members->formatDate($d_organiztion->FOUND_DATE,"Y/m/d"); ?>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->SETTLE_MONTH)): ?>
                  <?php if($d_organiztion->O_SETTLE_MONTH == 1 || ($d_organiztion->O_SETTLE_MONTH == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'決算月')), '決算月'); ?></td>
                     <td class="DetailValue"><?php echo $members->gdMonthText($d_organiztion->SETTLE_MONTH); ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->CAPITAL)): ?>
                  <?php if($d_organiztion->O_CAPITAL == 1 || ($d_organiztion->O_CAPITAL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'資本金')), '資本金'); ?></td>
                     <td class="DetailValue"><?php  echo number_format($d_organiztion->CAPITAL); ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->REPRESENTATIVE_NM)): ?>
                  <?php if($d_organiztion->O_REPRESENTATIVE_NM == 1 || ($d_organiztion->O_REPRESENTATIVE_NM == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'代表者')), '代表者'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->REPRESENTATIVE_NM; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->REPRESENTATIVE_KN)): ?>
                  <?php if($d_organiztion->O_REPRESENTATIVE_KN == 1 || ($d_organiztion->O_REPRESENTATIVE_KN == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'代表者フリガナ')), '代表者フリガナ'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->REPRESENTATIVE_KN; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_POST)): ?>
                  <?php if($d_organiztion->O_G_POST == 1 || ($d_organiztion->O_G_POST == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織〒')), '組織〒'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_POST; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_STA)): ?>
                  <?php if($d_organiztion->O_G_STA == 1 || ($d_organiztion->O_G_STA == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織都道府県')), '組織都道府県'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_STA; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_ADR)): ?>
                  <?php if($d_organiztion->O_G_ADR == 1 || ($d_organiztion->O_G_ADR == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織住所１')), '組織住所１'); ?></td>
                     <td class="DetailValue"><?php echo $d_organiztion->G_ADR; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_ADR2)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織住所２')), '組織住所２'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->G_ADR2; ?></td>
               </tr>
               <?php endif; ?>
               <?php if($d_organiztion->G_STA.$d_organiztion->G_ADR2 != ""): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織フリガナ')), '組織フリガナ'); ?></td>
                  <td class="DetailValue"><a href="http://maps.google.co.jp/maps?q=<?php echo $d_organiztion->G_STA.$d_organiztion->G_ADR; ?>" target="_blank">こちら</a></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_IMG)): ?>
                  <?php if($d_organiztion->O_G_IMG == 1 || ($d_organiztion->O_G_IMG == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'掲載画像')), '掲載画像'); ?></td>
                     <td class="DetailValue">
                        <img src="<?php echo $members->MakeGroupImageUrl($members->MakeSmallImageName($d_organiztion->G_ID, $d_organiztion->G_IMG)); ?>" width="80" border="0">
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_APPEAL)): ?>
                  <?php if($d_organiztion->O_G_APPEAL == 1 || ($d_organiztion->O_G_APPEAL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'組織アピール')), '組織アピール'); ?></td>
                     <td class="DetailValue"><textarea style="width:100%; line-height:150%;" cols="68" rows="5" readonly=""><?php echo $d_organiztion->G_APPEAL; ?></textarea>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_KEYWORD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'事務局検索用<br>企業コメント')), '事務局検索用<br>企業コメント'); ?></td>
                  <td class="DetailValue"><textarea style="width:100%; line-height:150%;" cols="68" rows="5" readonly=""><?php echo $d_organiztion->G_KEYWORD; ?></textarea>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->G_LOGO)): ?>
                  <?php if($d_organiztion->O_G_LOGO == 1 || ($d_organiztion->O_G_LOGO == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'ロゴ')), 'ロゴ'); ?></td>
                     <td class="DetailValue">
                        <img src="<?php echo $members->MakeLogoImageUrl($members->MakeSmallImageName($d_organiztion->G_ID, $d_organiztion->G_LOGO)); ?>" width="80" border="0">
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(isset($d_member->DISP_MARKETING)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'マーケティング<br>情報公開')), 'マーケティング<br>情報公開'); ?></td>
                  <td class="DetailValue">
                     <?php 
                        if($d_member->DISP_MARKETING == 1) echo "マーケティング情報を表示する";
                        else
                           echo "マーケティング情報を表示しない";
                     ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->DISP_LIST)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'一般公開会員<br>リスト表示設定')), '一般公開会員<br>リスト表示設定'); ?></td>
                  <td class="DetailValue">
                     <?php 
                        if($d_member->DISP_LIST == 1) echo "一般公開会員リストに表示する";
                        else
                           echo "一般公開会員リストに表示しない";
                     ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->DISP_DETAIL)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会員一覧からの<br>詳細画面表示設定')), '会員一覧からの<br>詳細画面表示設定'); ?></td>
                  <td class="DetailValue">
                     <?php 
                        if($d_member->DISP_DETAIL == 1) echo "詳細表示する";
                        else
                           echo "詳細表示しない";
                     ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->BANK_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'銀行コード')), '銀行コード'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->BANK_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->BRANCH_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'支店コード')), '支店コード'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->BRANCH_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->CONTRACT_TYPE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'科目')), '科目'); ?></td>
                  <td class="DetailValue"><?php echo $members->AccountTypeText($d_member->CONTRACT_TYPE); ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->ACCOUNT_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'口座番号')), '口座番号'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->ACCOUNT_NO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->ACCOUNT_NM)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'口座名義')), '口座名義'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->ACCOUNT_NM; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->CUST_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'顧客番号（全銀）')), '顧客番号（全銀）'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->CUST_NO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->SAVINGS_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'貯金記号')), '貯金記号'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->SAVINGS_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_organiztion->SAVINGS_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'貯金番号')), '貯金番号'); ?></td>
                  <td class="DetailValue"><?php echo $d_organiztion->SAVINGS_NO; ?></td>
               </tr>
               <?php endif; ?>
            </tbody>
         </table>
         <br>
         <br>
         <table align="center" border="0" cellspacing="1">
            <tbody>
               <tr>
                  <td colspamn="2">■個人データ</td>
               </tr>
               <tr>
               </tr>
            </tbody>
         </table>
         <table align="center" border="0" cellspacing="1">
         </table>
         <table align="center" border="0" cellspacing="1">
            <tbody>
               <?php if(!empty($d_personal->P_ID)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人ID')), '個人ID'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->P_ID; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_LNAME) || !empty($d_personal->C_FNAME)): ?>
                  <?php if($d_personal->O_C_FNAME == 1 || ($d_personal->O_C_FNAME == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'氏名')), '氏名'); ?></td>
                     <td class="DetailValue">
                        <?php echo $d_personal->C_FNAME;?>
                        <?php echo $d_personal->C_LNAME;?>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_LNAME_KN) || !empty($d_personal->C_FNAME_KN)): ?>
                  <?php if($d_personal->O_C_LNAME == 1 || ($d_personal->O_C_LNAME == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人フリガナ')), '個人フリガナ'); ?></td>
                     <td class="DetailValue">
                        <?php echo $d_personal->C_FNAME_KN; ?>
                        <?php echo $d_personal->C_LNAME_KN; ?>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_SEX)): ?>
                  <?php if($d_personal->O_C_SEX == 1 || ($d_personal->O_C_SEX == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'性別')), '性別'); ?></td>
                     <td class="DetailValue">
                        <?php
                           echo ($d_personal->C_SEX == "F")?"男":"女";
                        ?>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_URL)): ?>
                  <?php if($d_personal->O_C_URL == 1 || ($d_personal->O_C_URL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人URL')), '個人URL'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_URL; ?>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_EMAIL)): ?>
                  <?php if($d_personal->O_C_EMAIL == 1 || ($d_personal->O_C_EMAIL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人E-MAIL')), '個人E-MAIL'); ?></td>
                     <td class="DetailValue">
                        <a href="mailto:<?php echo $d_personal->C_EMAIL; ?>"><?php echo $d_personal->C_EMAIL; ?></a>
                     </td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_TEL)): ?>
                  <?php if($d_personal->O_C_TEL == 1 || ($d_personal->O_C_TEL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人TEL')), '個人TEL'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_TEL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_FAX)): ?>
                  <?php if($d_personal->O_C_FAX == 1 || ($d_personal->O_C_FAX == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人FAX')), '個人FAX'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_FAX; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_POST)): ?>
                  <?php if($d_personal->O_C_POST == 1 || ($d_personal->O_C_POST == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人〒')), '個人〒'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_POST; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_STA)): ?>
                  <?php if($d_personal->O_C_STA == 1 || ($d_personal->O_C_STA == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人都道府県')), '個人都道府県'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_STA; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_ADR)): ?>
                  <?php if($d_personal->O_C_ADR == 1 || ($d_personal->O_C_ADR == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人住所１')), '個人住所１'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_ADR; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_ADR2)): ?>
                  <?php if($d_personal->O_C_ADR == 1 || ($d_personal->O_C_ADR == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人住所２')), '個人住所２'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->C_ADR2; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_KEYWORD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'事務局検索用コメント')), '事務局検索用コメント'); ?></td>
                  <td class="DetailValue"><textarea style="width:100%; line-height:150%;" cols="68" rows="5" readonly=""><?php echo $d_personal->C_KEYWORD; ?></textarea>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->GROUP_ENABLE_FLG)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'本人が所属組織の<br>変更可能')), '本人が所属組織の<br>変更可能'); ?></td>
                  <td class="DetailValue"><?php echo ($d_personal->GROUP_ENABLE_FLG == 0)?"不可":"可"; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->MEETING_NM_DISP)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会議室氏名表示区分')), '会議室氏名表示区分'); ?></td>
                  <td class="DetailValue"><?php echo ($d_personal->MEETING_NM_DISP == 0)?"ニックネーム":"氏名"; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->HANDLE_NM)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会議室ニックネーム')), '会議室ニックネーム'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->HANDLE_NM; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->MEETING_NM_MK)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会議室公開ネーム表示マーク')), '会議室公開ネーム表示マーク'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->MEETING_NM_MK; ?></td>
               </tr>
               <?php endif; ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'所属組織')), '所属組織'); ?></td>
                  <td class="DetailValue"><?php echo $members->getGroupname($postid, $d_member->TG_ID,$d_member->LG_ID,$d_member->LG_TYPE); ?></td>
               </tr>
               <?php if(!empty($d_affiliation->AFFILIATION_NAME)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'所属')), '所属'); ?></td>
                  <td class="DetailValue"><?php echo $d_affiliation->AFFILIATION_NAME; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_affiliation->OFFICIAL_POSITION)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'役職')), '役職'); ?></td>
                  <td class="DetailValue"><?php echo $d_affiliation->OFFICIAL_POSITION; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->CARD_OPEN)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'名刺情報公開')), '名刺情報公開'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->CARD_OPEN; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->LOGIN_LOCK_FLG)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'携帯自動ログインを<br>停止する')), '携帯自動ログインを<br>停止する'); ?></td>
                  <td class="DetailValue"><?php echo ($d_personal->LOGIN_LOCK_FLG == 0)?"停止しない":"停止する"; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_URL)): ?>
                  <?php if($d_personal->O_P_URL == 1 || ($d_personal->O_P_URL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'URL')), 'URL'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_URL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_EMAIL)): ?>
                  <?php if($d_personal->O_P_EMAIL == 1 || ($d_personal->O_P_EMAIL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'EMAIL')), 'EMAIL'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_EMAIL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_CC_EMAIL)): ?>
                  <?php if($d_personal->O_P_CC_EMAIL == 1 || ($d_personal->O_P_CC_EMAIL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'追加送信先E-MAIL')), '追加送信先E-MAIL'); ?>追加送信先E-MAIL</td>
                     <td class="DetailValue"><?php echo $d_personal->P_CC_EMAIL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_TEL)): ?>
                  <?php if($d_personal->O_P_TEL == 1 || ($d_personal->O_P_TEL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'TEL')), 'TEL'); ?>TEL</td>
                     <td class="DetailValue"><?php echo $d_personal->P_TEL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_FAX)): ?>
                  <?php if($d_personal->O_P_FAX == 1 || ($d_personal->O_P_FAX == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'FAX')), 'FAX'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_FAX; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_PTEL)): ?>
                  <?php if($d_personal->O_P_PTEL == 1 || ($d_personal->O_P_PTEL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'携帯')), '携帯'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_PTEL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_PMAIL)): ?>
                  <?php if($d_personal->O_P_PMAIL == 1 || ($d_personal->O_P_PMAIL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'携帯メールアドレス')), '携帯メールアドレス'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_PMAIL; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_POST)): ?>
                  <?php if($d_personal->O_P_POST == 1 || ($d_personal->O_P_POST == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'郵便番号')), '郵便番号'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_POST; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_STA)): ?>
                  <?php if($d_personal->O_P_STA == 1 || ($d_personal->O_P_STA == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'住所（都道府県）')), '住所（都道府県）'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_STA; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_ADR)): ?>
                  <?php if($d_personal->O_P_ADR == 1 || ($d_personal->O_P_ADR == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'住所１')), '住所１'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_ADR; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_ADR2)): ?>
                  <?php if($d_personal->O_P_ADR == 1 || ($d_personal->O_P_ADR == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'住所２')), '住所２'); ?></td>
                     <td class="DetailValue"><?php echo $d_personal->P_ADR2; ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->P_BIRTH)): ?>
                  <?php if($d_personal->O_P_BIRTH == 1 || ($d_personal->O_P_BIRTH == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'生年月日')), '生年月日'); ?></td>
                     <td class="DetailValue"><?php echo $members->formatDate($d_personal->P_BIRTH,"Y/m/d"); ?></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->PRIVATE_OPEN)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'プライベート情報公開')), 'プライベート情報公開'); ?></td>
                  <td class="DetailValue"><?php echo ($d_personal->PRIVATE_OPEN == 0)?"しない":"する"; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->BANK_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'銀行コード')), '銀行コード'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->BANK_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->BRANCH_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'支店コード')), '支店コード'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->BRANCH_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->CONTRACT_TYPE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'科目')), '科目'); ?></td>
                  <td class="DetailValue"><?php echo $members->AccountTypeText($d_member->CONTRACT_TYPE); ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->ACCOUNT_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'口座番号')), '口座番号'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->ACCOUNT_NO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->ACCOUNT_NM)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'口座名義')), '口座名義'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->ACCOUNT_NM; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->CUST_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'顧客番号')), '顧客番号'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->CUST_NO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->SAVINGS_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'貯金記号')), '貯金記号'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->SAVINGS_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_personal->SAVINGS_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'貯金番号')), '貯金番号'); ?></td>
                  <td class="DetailValue"><?php echo $d_personal->SAVINGS_NO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->CONTRACT_TYPE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'連絡手段')), '連絡手段'); ?></td>
                  <td class="DetailValue">
                     <?php 
                        if($d_member->CONTRACT_TYPE == 1) echo "E-MAIL会員";
                        else if($d_member->CONTRACT_TYPE == 2) echo "FAX会員";
                        else if($d_member->CONTRACT_TYPE == 3) echo "郵送会員";
                        else echo "不明";
                     ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->FAX_TIMEZONE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'ＦＡＸ送信時間帯')), 'ＦＡＸ送信時間帯'); ?></td>
                  <td class="DetailValue"><?php echo $members->getFaxTimezone($d_member->FAX_TIMEZONE,$d_member->FAX_TIME_FROM,$d_member->FAX_TIME_TO); ?></td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->RECEIVE_INFO_FAX)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'ＦＡＸによる会からの<br>案内受取設定')), 'ＦＡＸによる会からの<br>案内受取設定'); ?></td>
                  <td class="DetailValue"><?php echo ($d_member->RECEIVE_INFO_FAX == 1)?"ＦＡＸでの案内を受け取る":"ＦＡＸでの案内を受け取らない"; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->RECEIVE_INFO_MAIL)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'メールによる会からの<br>案内受取設定')), 'メールによる会からの<br>案内受取設定'); ?></td>
                  <td class="DetailValue"><?php echo ($d_member->RECEIVE_INFO_MAIL == 1)?"Ｅメールでの案内を受け取る":"Ｅメールでの案内を受け取らない"; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->X_COMMENT)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'コメント')), 'コメント'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->X_COMMENT; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(isset($d_member->CONTRACT_TYPE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'状態')), '状態'); ?></td>
                  <td class="DetailValue">
                     <?php 
                        if($d_member->CONTRACT_TYPE == 0) echo "非会員";
                        else if($d_member->CONTRACT_TYPE == 1) echo "会員";
                        else if($d_member->CONTRACT_TYPE == 2) echo "保留";
                        else if($d_member->CONTRACT_TYPE == 3) echo "削除状態";
                        else echo "一般";
                     ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->ADMISSION_DATE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'入会年月日')), '入会年月日'); ?></td>
                  <td class="DetailValue"><?php echo $members->formatDate($d_member->ADMISSION_DATE,"Y/m/d"); ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->WITHDRAWAL_DATE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'退会年月日')), '退会年月日'); ?></td>
                  <td class="DetailValue"><?php echo $members->formatDate($d_member->WITHDRAWAL_DATE,"Y/m/d"); ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->CHANGE_DATE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'異動年月日')), '異動年月日'); ?></td>
                  <td class="DetailValue"><?php echo $members->formatDate($d_member->CHANGE_DATE,"Y/m/d"); ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->CHANGE_REASON)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'異動理由')), '異動理由'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->CHANGE_REASON; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->CLAIM_CLS)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'請求区分')), '請求区分'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->CLAIM_CLS; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->FEE_RANK)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会費ランク')), '会費ランク'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->FEE_RANK; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->CLAIM_CYCLE)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'請求サイクル')), '請求サイクル'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->CLAIM_CYCLE; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->SETTLE_MONTH)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会員決算月')), '会員決算月'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->SETTLE_MONTH; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->FEE_MEMO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'会費メモ')), '会費メモ'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->FEE_MEMO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->ENTRUST_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'委託者コード')), '委託者コード'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->ENTRUST_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->BANK_CD)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'銀行コード')), '銀行コード'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->BANK_CD; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->ACCOUNT_NO)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'口座番号')), '口座番号'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->ACCOUNT_NO; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($d_member->ACCOUNT_NM)): ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'口座名義')), '組織フリガナ'); ?></td>
                  <td class="DetailValue"><?php echo $d_member->ACCOUNT_NM; ?></td>
               </tr>
               <?php endif; ?>
               <?php if($d_personal->C_STA.$d_personal->C_ADR != ""): ?>
                  <?php if(($d_personal->O_C_STA == 1 || $d_personal->O_C_ADR == 1) || (($d_personal->O_C_STA == 2 || $d_personal->O_C_ADR == 2) && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人地図')), '個人地図'); ?></td>
                     <td class="DetailValue"><a href="http://maps.google.co.jp/maps?q=<?php echo $d_personal->C_STA.$d_personal->C_ADR; ?>" target="_blank">こちら</a></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <?php if(!empty($d_personal->C_APPEAL)): ?>
                  <?php if($d_personal->O_C_APPEAL == 1 || ($d_personal->O_C_APPEAL == 2 && isset($_SESSION['arrSession']))): ?>
                  <tr>
                     <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'個人アピール')), '個人アピール'); ?></td>
                     <td class="DetailValue"><textarea style="width:100%; line-height:150%;" cols="68" rows="5" readonly=""><?php echo isset($d_personal->C_APPEAL)?$d_personal->C_APPEAL:""; ?></textarea></td>
                  </tr>
                  <?php endif; ?>
               <?php endif; ?>
               <tr>
                  <td class="DetailItem" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->getNewTitle($arrTitle,'所属組織')), '所属組織'); ?></td>
                  <td class="DetailValue"><?php echo $members->getGroupname($postid, $d_member->TG_ID,$d_member->LG_ID,$d_member->LG_TYPE); ?>
                     <input type="hidden" name="selGid" value="<?php echo $d_member->TG_ID; ?>"> 
                  </td>
               </tr>
            </tbody>
         </table>
         <br>
         <br>
      </form>
      </div>
   </body>
</html>
