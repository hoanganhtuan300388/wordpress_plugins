<?php
   $members = new memberController();
   $path_page = get_page_uri();
   $page_link = $members->getPageSlug('nakama-login');
   if(!isset($_SESSION['arrSession'])){
      ?>
      <script>window.location = "<?php echo $page_link.'page_redirect='.$path_page; ?>"; </script>
      <?php
   }
   
   $MemberOrganization = $members->getMemberOrganization($postid);
   $dataSetting = get_post_meta( $args['id'] );
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="Pragma" content="no-cache">
      <meta http-equiv="Expires" content="-1">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <meta http-equiv="Content-Language" content="ja">
      <meta http-equiv="Content-Style-Type" content="text/css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/member_card.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/smart.css">
      <script language="JavaScript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/common.js"></script>
      <script language="JavaScript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/member_card.js"></script>
      <title><?php echo get_the_title($args['id']); ?></title>
   </head>
   <body>

      <div class="container">
         <form name="mainForm" method="post">
            <table width="100%" border="0" cellspacing="0">
               <tbody>
                  <tr>
                     <td class="TitleItem" width="350" align="left"><b>会員証発行</b></td>
                     <td class="TitleItem2" width="300" align="right">
                        <label>所属組織</label>
                        <select name="disp_g_id" class="select_g_id" style="font-size: 9pt;">
                        <?php
                           if($MemberOrganization->data)

                              foreach ($MemberOrganization->data as $value) {
                              ?>
                                 <option value="<?php echo $value->G_ID; ?>"><?php echo $value->G_NAME; ?></option>
                              <?php
                           }
                        ?>
                        </select>
                     </td>
                  </tr>
               </tbody>
            </table>
            <br><br>
            <table class="style_none" width="100%" border="0" cellpadding="0" cellspacing="0">
               <tbody>
                  <tr>
                     <td><font size="3">① 会員証を表示するページのＵＲＬを下にご記入ください。</font></td>
                  </tr>
                  <tr>
                     <td align="left">
                        <input type="text" name="disp_url" class="input_url" value="">
                        <br>※httpから記述し、貼り付けるHTMLのパスまで記述してください。<br>（例）http://coco.cococica.com/aaa/
                     </td>
                  </tr>
               </tbody>
            </table>
            <br><br>
            <div align="center">
               <input type="button" class="base_button" value="発　行　す　る" onclick="inputCheck();">
            </div>
            <br><br>
            <?php
               if($_POST){
                  $rsMemberShipCard = $members->setMemberShipCard($postid,$_POST['disp_url']);
                  if(isset($rsMemberShipCard->Message)){
                     if($rsMemberShipCard->Message == "URLが無効です。") {
                        ?>
                        <div align="center">
                           <font color="red">有効期限が切れている為、発行する事ができません。</font>
                        </div>
                     <?php
                     }
                  }
                  else {
                     $urlMemberCard = $members->getPageSlug('nakama-member-card')."tgid=".$rsMemberShipCard->TG_ID."&pid=".$rsMemberShipCard->P_ID."&gid=".$rsMemberShipCard->G_ID."&post_id=".$postid;
                     ?>
                     <p class="txt_center title_card">会員証を発行しました</p>
                     <p class="fz-17">②下のＵＲＬをコピーし、上で指定した表示ページ内にリンクを貼り付けると</p>
                     <label class="fz-17">会員証が表示できます。</label>
                     <textarea class="box_url" name="" id=""  rows="5"><?php echo $urlMemberCard; ?></textarea>
                     <div class="txt_center">
                        <a href="<?php echo $urlMemberCard; ?>" target="_blank" class=" link_membercard">発行した会員証の表示の確認</a>
                     </div>
                  <?php
                  }
               }
            ?>
            <br>
         </form>
      <input type="hidden" name="mode" value="">
      <input type="hidden" name="tgid" value="dmshibuya">
      <input type="hidden" name="pid" value="dmshibuyap0001">
      <input type="hidden" name="set_lg_g_id" value="">
      <input type="hidden" name="set_g_id" value="">
      <input type="hidden" name="page_no" value="117">
      </div>
   </body>
</html>
