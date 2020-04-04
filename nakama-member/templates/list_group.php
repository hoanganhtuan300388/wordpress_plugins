<?php
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once(__ROOT__.'/config/constant.php'); 
    require_once(__ROOT__.'/controller/memberController.php'); 
    $members = new memberController();
    $path_page = "nakama-member-list-group";
    $page_login = $members->getPageSlug('nakama-login');
   if(!isset($_SESSION['arrSession'])){
      wp_redirect($page_login.'page_redirect='.$path_page);
      exit();
   }
   else {
      $post_id = isset($_GET['post_id'])?$_GET['post_id']:'';
      $groups = $members->getGroupsOfUser($post_id);
?>
<?php get_header(); ?>
<!DOCTYPE html PUBLIC">
<html lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title>利用者データ一覧</title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/magazine.css">
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/jquery-1.6.3.min.js"></script>
  </head>
<body onunload="OnUnload();" onload="OnLoad();">
   <div class="container">
      <div class="top-edit">
         <h3 class="DispTitle">
            利用者データ一覧
         </h3>
      </div>
      <br>
      <article class="member-list-group" style="margin: 100px 0px;">
         <div class="content">
            <p class="text-center">複数の利用者データが登録されています。<br>
            変更するデータを選択してください。</p>
         </div>
         <!-- List group -->
         <form name="mainForm" id="mainForm">
            <table style="width: 100%">
               <tbody>
                  <tr class="ListHeader">
                     <td width="5%"></td>
                     <td width="40%">グループ名</td>
                     <td width="30%">所属組織</td>
                  </tr>

                  <?php foreach ($groups->data as $key => $value): ?>
                    <tr class="ListRow<?php echo $key%2 ? '2' : '1'; ?>">
                       <td nowrap="" width="5%"><a href="javascript:void(0)" onclick="MoveGroup('<?php echo $value->M_ID ?>','<?php echo $value->LG_ID ?>','<?php echo $value->LG_NAME ?>')">選択</a></td>
                       <td nowrap="" width="40%"><?php echo $value->LG_NAME; ?></td>
                       <td nowrap="" width="30%"><?php echo $value->TG_NAME; ?></td>
                    </tr>
                  <?php endforeach ?>
                  
               </tbody>
            </table>
         </form>
         <br>
         <center>
            <input type="button" value="閉じる" onclick="goHome();">
         </center>
      </article>
   </div>
   <script>
      function MoveGroup(M_ID,LG_ID,LG_NAME){

         $.ajax({
            url: '<?php echo get_home_url(); ?>/wp-admin/admin-ajax.php',
            type : "POST",
            data: {
               action: 'moveGroup',
               M_ID: M_ID,
               LG_ID: LG_ID,
               LG_NAME: LG_NAME,
               post_id: <?php echo $post_id; ?>
            },
            success: function(response) {
               window.location.href = "<?php echo get_page_link(get_page_by_path($_SESSION['path_page_edit'])); ?>";
            },
            error: function() {}
         });
      }
      function goHome(){
         window.location = '<?php echo $_SESSION['url_org']; ?>'
      }
   </script>
</body>
</html>
<?php } ?><?php get_footer(); ?>