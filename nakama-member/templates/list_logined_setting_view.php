<?php
  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/config/constant.php'); 
  require_once(__ROOT__.'/controller/memberController.php');
  $members = new memberController();
  $arrHide = [];
  $arrHeader = [];
  $arrShow = [];
  $path_list = isset($_GET['path_list'])?$_GET['path_list']:"nakama-list-member";
  $path_page = "nakama-logined-setting-view";
  $page_link = $members->getPageSlug('nakama-login');
  $postid = $_REQUEST['postid'];
  $infoUser = $_SESSION['arrSession'];
  $pIdInfo = $infoUser->P_ID;
  $per_page = get_post_meta($postid, 'nak-member-per-page', true);

  if(!get_option($pIdInfo.$postid.'nakama-member-list-logined-per-page')){
    add_option($pIdInfo.$postid."nakama-member-list-logined-per-page", $per_page);
  }
  if(!isset($_SESSION['arrSession'])){
    wp_redirect($page_link.'page_redirect='.$path_page);
    exit();
  }

  $arrHeader = $_SESSION['list_arr_header'];
  $arrPostShow = [];
  $arrPostHide = [];

  $arrShow = isset($_SESSION['list_arr_header_show']) ? $_SESSION['list_arr_header_show'] : array();
  $arrHide = isset($_SESSION['list_arr_header_hidden']) ? $_SESSION['list_arr_header_hidden'] : array();

  if($_POST){
    update_option( $pIdInfo.'nakama-member-list-logined-per-page', $_POST[$pIdInfo.'nakama-member-list-logined-per-page']);

    // update form show
    $arrShow = $_POST['nak-member-key-list-show'];
    foreach ($arrShow as $key => $value) {
      if($members->getItemColumn($value, $arrHeader)){
        array_push($arrPostShow, array(
            'column_id'=>$value, 
            'column_name'=>$members->getItemColumn($value, $arrHeader))
        );  
      }
    }
    $arrayMerge = [];
    $arrayMerge['list_item'] = $arrPostShow;
    $arrayMerge['sort_item'] = '';

    update_option( $pIdInfo.$postid."_setting_list_show", json_encode($arrayMerge, JSON_UNESCAPED_UNICODE));

    // update form hide
    $arrHide = $_POST['nak-member-key-list-hidden'];
    foreach ($arrHide as $key => $v) {
      if($members->getItemColumn($v, $arrHeader)){
        array_push($arrPostHide, array(
            'column_id'=>$v, 
            'column_name'=>$members->getItemColumn($v, $arrHeader))
        );  
      }
    }
    $arrayMergeHide = [];
    $arrayMergeHide['list_item'] = $arrPostHide;
    $arrayMergeHide['sort_item'] = '';
    
    update_option($pIdInfo.$postid."_setting_list_hide", json_encode($arrayMergeHide, JSON_UNESCAPED_UNICODE));
    $_SESSION['list_arr_header_show'] = $arrShow;
    $_SESSION['list_arr_header_hidden'] = $arrHide;
    wp_redirect($path_list);
    exit();
  }
  
  //add meta post
  $vMetaPost = [];
  $vMetaPostHide = [];
  $vMetaPost['list_item'] = $arrHeader;
  $vMetaPost['sort_item'] = '';

  $vMetaPostHide['list_item'] = [];
  if(!get_option($pIdInfo.$postid.'_setting_list_show')){
    add_option($pIdInfo.$postid."_setting_list_show", json_encode($vMetaPost, JSON_UNESCAPED_UNICODE));
  }
  if(!get_option($pIdInfo.$postid.'_setting_list_hide')){
    add_option($pIdInfo.$postid."_setting_list_hide", json_encode($vMetaPostHide, JSON_UNESCAPED_UNICODE));
  }

  $arrValueMeta = get_option($pIdInfo.$postid."_setting_list_show");
  $arrValueMetaHide = get_option($pIdInfo.$postid."_setting_list_hide");
  
  $arrValueMeta = json_decode($arrValueMeta);
  $arrValueMetaHide = json_decode($arrValueMetaHide);
  $arrListShow = $arrValueMeta->list_item;
  $arrListHide = $arrValueMetaHide->list_item;
?>
<html lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title>会員一覧</title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/front_setting.css">
  </head>
  <body>
    <div class="container">
      <div class="wrap setting_view_list">
        <form method="post" action="">
          <h1 class="setting_title">表示設定</h1>
          <div class="per_page">
            <label>一覧表示件数 :</label>
            <select name="<?php echo $pIdInfo; ?>nakama-member-list-logined-per-page" class="">
              <?php
                $current_per_page = get_option($pIdInfo.$postid."nakama-member-list-logined-per-page");
                for($i = 10; $i <= 100; $i += 10) { ?>
                  <option value="<?php echo $i; ?>" <?php echo ($i == $current_per_page)?"selected":""?>><?php echo $i; ?></option>
              <?php } ?>
            </select>
          </div>
          <h1 class="setting_title">表示項目</h1>
          <div class="jp-multiselect">
            <div class="from-panel col-5-dual left">
              <h4>使用できる項目</h4>
              <select id="unselected" name="nak-member-key-list-hidden[]" class="unselected form-control" style="height: 250px; width: 100%; overflow-y: auto;" multiple="multiple">
                <?php foreach ($arrListHide as $item) { 
                ?>
                  <option value="<?php echo $item->column_id; ?>"><?php echo $item->column_name; ?></option>
                <?php 
                } ?>
              </select>
            </div>
            <div class="move-panel col-2-dual mb-30 left center-block" style="margin-top: 80px">
              <ul >
                <li>
                  <button type="button" class="btn-move-selected-right btn btn-default col-md-8 col-md-offset-2 btn_r" style="margin-bottom: 10px;"></button>
                </li>
                <li>
                  <button type="button" class="btn-move-selected-left btn btn-default col-md-8 col-md-offset-2 btn_l"></button>
                </li>
              </ul>
            </div>
            <div class="to-panel col-5-dual left">
              <h4>現在の項目</h4>
              <select id="selected" name="nak-member-key-list-show[]" class="selected form-control" style="height: 250px; width: 100%; overflow-y: auto;" multiple="multiple">
                <?php 
                foreach ($arrListShow as $item) { 
                ?>
                  <option value="<?php echo $item->column_id; ?>"><?php echo $item->column_name; ?></option>
                <?php
                } ?>
              </select>
            </div>
            <div class="control-panel col-2-dual left center-block" style="margin-top: 80px">
              <ul>
                <li>
                  <button type="button" class="btn-up btn btn-default col-md-8 col-md-offset-2 btn_up" style="margin-bottom: 10px;"></button>
                </li>
                <li>
                  <button type="button" class="btn-down btn btn-default col-md-8 col-md-offset-2 btn_down"></button>
                </li>
              </ul>
            </div>
          </div>
          <div style="clear: both;"></div>
          <table align="center" class="btn_controll">
            <tbody>
              <tr>
                <td align="center">
                  <input type="button" class="base_button" value="戻　る" onclick="onBack();">
                  <input type="submit" class="base_button" value="適　用">
                </td>
              </tr>
            </tbody>
          </table>
        </form>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>settingform/admin/assets/js/jquery.multi-selection.v1.js"></script>
        <script type="text/javascript">
          $(".jp-multiselect").jQueryMultiSelection({
            htmlMoveSelectedRight   : "追加 →",
            htmlMoveSelectedLeft    : "← 削除",
            htmlMoveUp              : "↑上に移動",
            htmlMoveDown              : "↓下に移動",
          });

          $(".jp-multiselect").closest('form').submit(function() {
            $("#selected").find('option').prop('selected', true);
            $("#unselected").find('option').prop('selected', true);
          });
          function onBack(){
            window.location.href = "<?php echo $path_list; ?>";
          }
        </script>

        <script>
          function myFunction(x) {
            if (x.matches) { // If media query matches
              $(".jp-multiselect").jQueryMultiSelection({
              htmlMoveSelectedRight   : "追加 ↓",
              htmlMoveSelectedLeft    : "↑ 削除",
              htmlMoveUp              : "↑上に移動",
              htmlMoveDown              : "↓下に移動",
            });
            } 
          }

          var x = window.matchMedia("(max-width: 600px)")
          myFunction(x) // Call listener function at run time
          x.addListener(myFunction) // Attach listener function on state changes
        </script>



      </div>
    </div>
  </body>
</html>
