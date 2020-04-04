<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/memberController.php');
$members = new memberController();
$dicName = isset($_GET['dicName']) ? ($_GET['dicName']) : "";
$post_id = ($_GET['post_id']) ? ($_GET['post_id']) : "";
$eleName = ($_GET['eleName']) ? ($_GET['eleName']) : "";
$tg_id = !empty(get_post_meta($post_id, 'member_meta_group_id', true)) ? get_post_meta($post_id, 'member_meta_group_id', true) : get_option('nakama-member-group-id');
$per_page = !empty(get_option('nakama-member-general-per-page')) ? get_option('nakama-member-general-per-page') : 100;
$current_page = !empty($_POST['current_page_list']) ? $_POST['current_page_list'] : "1";
$page_no = $current_page - 1;
$rs = $members->getSearchDictionary($post_id, $dicName, $tg_id);
$count = isset($rs->count) ? $rs->count : '';
$pagination = $members->paginatesCategorys($count, $current_page, $per_page);
?>
<html lang="ja">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <title>新規会員登録</title>
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/smart.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/regist.css">
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/common.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/sedai_link.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/inputcheck.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/jquery-1.6.3.min.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/autoKana.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/regist_new.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $(".next_list").click(function() {
        var num_page = $(this).children('.pagination_page').val();
        $("input[name='current_page_list']").val(num_page);
        document.mainForm.submit();
        return false;
      });
      $(".prev_list").click(function() {
        var num_page = $(this).children('.pagination_page_prev').val();
        $("input[name='current_page_list']").val(num_page);
        document.mainForm.submit();
        return false;
      });
    });
  </script>
  <script type="text/javascript">
    function movePage(page_no) {
      document.mainForm.page_no.value = page_no;
      document.mainForm.submit();
    }

    function OnSel(element, value) {
      window.opener.document.mainForm.elements[element].value = value;
      window.close();
    }
  </script>
</head>

<body onload="OnLoad();">
  <form id="mainForm" name="mainForm" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td align="left"><input type="button" value="閉じる" onclick="JavaScript:window.close();"></td>
        </tr>
      </tbody>
    </table>
    <table cols="2" rows="1" cellpadding="2" cellspacing="1">
      <tbody>
        <tr>
          <td align="left" class="RegFeeTitle" nowrap=""><b>対象辞書</b></td>
          <td align="left" nowrap=""><b>：<?php echo $dicName; ?></b></td>
        </tr>
      </tbody>
    </table>
    <table border="0" width="100%">
      <tbody>
        <tr class="ListOperation">
          <td>【該当件数：<?php echo $rs->count; ?>件】</td>
          <td align="right"><?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1) { ?>
              <a class="prev_list" href="#"> <?php } ?>
              <input type="hidden" value="<?php echo $pagination['current_page'] - 1; ?>" class="pagination_page_prev">
              <span>&lt;&lt;前ページ</span>
              <?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1) {
                ?></a><?php
                              } ?>
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] < $pagination['total_page']) { ?>
              <a class="next_list" href="#"> <?php } ?>
              <input type="hidden" value="<?php echo $pagination['current_page'] + 1; ?>" class="pagination_page">
              <span>次ページ&gt;&gt;</span>
              <?php if ($pagination['total_page'] > 1  && $pagination['current_page'] < $pagination['total_page']) { ?></a> <?php } ?>
          </td>
        </tr>
      </tbody>
    </table>
    <table width="100%" border="0" cellspacing="1" celpadding="0" bordercolor="#C0C0C0">
      <tbody>
        <tr class="ListHeader">
          <td align="center" width="40">
            <div style="white-space: nowrap;">No</div>
          </td>
          <td align="center" width="40">
            <div style="white-space: nowrap;">選択</div>
          </td>
          <td align="center" width="">
            <div style="white-space: nowrap;">名称</div>
          </td>
        </tr>
        <?php
        if(!empty($rs->data)) {
        foreach ($rs->data as $key => $item) {
          $key = $key + 1;
          ?>
          <tr class="ListRow<?php echo ($key % 2 == 0) ? '2' : '1'; ?>">
            <td align="center"><?php echo $item->key; ?></td>
            <td align="center"><a href="JavaScript:OnSel('<?php echo $eleName; ?>','<?php echo $item->value; ?>');">選択</a></td>
            <td><?php echo $item->value; ?></td>
          </tr>
        <?php } }?>
      </tbody>
    </table>
    <table border="0" width="100%">
      <tbody>
        <tr class="ListOperation">
          <td align="right">
            <?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1) { ?>
              <a class="prev_list" href="#"> <?php } ?>
              <input type="hidden" value="<?php echo $pagination['current_page'] - 1; ?>" class="pagination_page_prev">
              <span>&lt;&lt;前ページ</span>
              <?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1) {
                ?></a><?php
                              } ?>
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] < $pagination['total_page']) { ?>
              <a class="next_list" href="#"> <?php } ?>
              <input type="hidden" value="<?php echo $pagination['current_page'] + 1; ?>" class="pagination_page">
              <span>次ページ&gt;&gt;</span>
              <?php if ($pagination['total_page'] > 1  && $pagination['current_page'] < $pagination['total_page']) { ?></a> <?php } ?>
          </td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="mode" value="select">
    <input type="hidden" name="page_no" value="1">
    <input type="hidden" name="current_page_list" value="<?php echo $current_page; ?>">
  </form>
</body>

</html>