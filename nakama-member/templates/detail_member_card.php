<?php
    define('__ROOT__', dirname(dirname(__FILE__)));
    require_once(__ROOT__.'/config/constant.php');
    require_once(__ROOT__.'/controller/memberController.php');
    $members = new memberController();
    $TG_ID = isset($_GET['tgid'])?$_GET['tgid']:"";
    $P_ID = isset($_GET['pid'])?$_GET['pid']:"";
    $G_ID = isset($_GET['gid'])?$_GET['gid']:"";
    $post_id = isset($_GET['post_id'])?$_GET['post_id']:"";

    $rsMemberCard = $members->getMemberShipCard($post_id,$TG_ID,$G_ID,$P_ID);
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <meta name="robots" content="none">
      <meta name="robots" content="noindex,nofollow">
      <meta name="robots" content="noarchive">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/css/smart.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/css/detail_member_card.css">
      <title>会員証発行</title>
   </head>
   <body class="<?php echo (!$rsMemberCard->Message)?"bg_page":""; ?>">
      <div class="container">
      <center>
         <form name="mainForm" method="post">
            <div align="right">
               <a href="javascript:window.close();">
               <b>閉じる</b>
               </a>
            </div>
            <br>
            <?php if(isset($rsMemberCard->Message)) { ?>
            <div id="err_msg" align="center">
               <font color="red">有効期限が切れているか会員証が発行されていません。<br>
                お手数ですが会員証発行を行ってください。</font>
            </div>
            <?php } else {?>
            <div id="card" style="">
               <br><br><br>
               <table border="0" class="info_card" align="center">
                  <tbody>
                     <tr>
                        <td>
                           <table border="0" width="100%">
                              <tbody>
                                 <tr>
                                    <td style="border:none;">
                                       <table border="0" width="100%">
                                          <tbody>
                                             <tr>
                                                <td style="border:none;">&nbsp;
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;"></td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;" align="center">
                                                   <span style="font-size:20pt;font-family:HGP創英角ｺﾞｼｯｸUB;color:#808080;text-align:center;"><?php echo ($rsMemberCard->P_ID != null)?$rsMemberCard->TG_NAME:""; ?><br>会員証</span>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">&nbsp;<br><br><br></td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">
                                                   　　<font size="3" color="#505050">会員番号 ： <?php echo ($rsMemberCard->P_ID != null)?$rsMemberCard->P_ID:""; ?></font>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">
                                                   　　<b><font size="4">法人名 ： </font><font size="4"><?php echo ($rsMemberCard->G_NAME != null)?$rsMemberCard->G_NAME:""; ?></font></b>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">
                                                   　　<font size="3" color="#505050">住所 ： <?php echo ($rsMemberCard->ADDRESS != null)?$rsMemberCard->ADDRESS:""; ?></font>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">
                                                   　　<font size="3" color="#505050">発行日 ： <?php echo ($rsMemberCard->ISSUE_DATE != null)?$members->convertDates($rsMemberCard->ISSUE_DATE,"Y","年").$members->convertDates($rsMemberCard->ISSUE_DATE,"m","月").$members->convertDates($rsMemberCard->ISSUE_DATE,"d","日"):"";?></font>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">
                                                   　　<font size="3" color="#505050">有効期限 ： <?php echo ($rsMemberCard->EXPIRE_DATE != null)?$members->convertDates($rsMemberCard->EXPIRE_DATE,"Y","年").$members->convertDates($rsMemberCard->EXPIRE_DATE,"m","月").$members->convertDates($rsMemberCard->EXPIRE_DATE,"d","日"):"";?></font>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;"><br><br>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">
                                                   　　<font size="2" color="#505050">当会員は、<?php echo ($rsMemberCard->P_ID != null)?$rsMemberCard->TG_NAME:""; ?>の会員であることを証します。</font>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none; padding-right:60px;" align="right">
                                                   <br><br><br>
                                                   <font size="3" color="#505050"><b><?php echo ($rsMemberCard->P_ID != null)?$rsMemberCard->TG_NAME:""; ?></b>&nbsp;&nbsp;</font><br>
                                                   <font size="3" color="#505050"><b><?php echo ($rsMemberCard->P_ID != null)?$rsMemberCard->REPRESENTATIVE_NM:""; ?></b>&nbsp;&nbsp;</font>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td style="border:none;">&nbsp;
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <?php } ?>
         </form>
      </center>
      </div>
   </body>
</html>