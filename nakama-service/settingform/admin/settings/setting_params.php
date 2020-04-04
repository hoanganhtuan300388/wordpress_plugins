<?php
$service = new serviceController();
$post = get_post();


$tg_id = !is_null(get_post_meta($post->ID, 'nakama_service_param_tg_id', true)) ? get_post_meta($post->ID, 'nakama_service_param_tg_id', true) : "";
$service_info = !is_null(get_post_meta($post->ID, 'nakama_service_param_service_info', true)) ? get_post_meta($post->ID, 'nakama_service_param_service_info', true) : "";
$contact_mail = !is_null(get_post_meta($post->ID, 'nakama_service_param_contact_mail', true)) ? get_post_meta($post->ID, 'nakama_service_param_contact_mail', true) : "";
$list_type = !is_null(get_post_meta($post->ID, 'nakama_service_param_list_type', true)) ? get_post_meta($post->ID, 'nakama_service_param_list_type', true) : "";
$lg_write = !is_null(get_post_meta($post->ID, 'lg_login', true)) ? get_post_meta($post->ID, 'lg_login', true) : "";
$lg_g_id = !is_null(get_post_meta($post->ID, 'set_lg_g_id', true)) ? get_post_meta($post->ID, 'set_lg_g_id', true) : "";
$lg_g_is_root = !empty(get_post_meta($post->ID, 'nakama_service_param_lg_g_is_root', true)) ? get_post_meta($post->ID, 'nakama_service_param_lg_g_is_root', true) : "";
$lg_type = (isset($_SESSION['arrSession'])) ? $_SESSION['arrSession']->LG_TYPE : '0';

//カテゴリ（サービス情報名）のプルダウンデータを取得
$params = array();
$params["TG_ID"] = $tg_id;
$params['LG_TYPE'] = $lg_type;

$category_list = $service->getServiceNameList($post->ID, $tg_id);
$getGroupTree = $service->getGroupTree($post->ID, $tg_id, $lg_type);
$arrGroup = array();
if(isset($getGroupTree->data)){
    foreach ($getGroupTree->data as $key => $item) {
        $item->id = $item->LG_ID;
        $item->text = $item->LG_NAME;
        array_push($arrGroup, $item);
    }
}
$tree = buildTreeMeetingService($arrGroup);
$json_tree = json_encode($tree, JSON_UNESCAPED_UNICODE);

?>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname(dirname(dirname( __FILE__ ))) ); ?>assets/combotree/easyui.css">
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname(dirname(dirname( __FILE__ ))) ); ?>assets/combotree/jquery.easyui.min.js"></script>
<div class="setting_view_list window_close_class">
	<input type="hidden" id="nakama_service_param_tg_id" name="nakama_service_param_tg_id" value="<?php echo esc_attr( get_option('nakama-service-group-id') ); ?>">
	<div id="param">
		<table class="setting-table-param w-100">
			<tr>
				<td>サービス情報</td>
				<td>
					<select class="mt-5" name="nakama_service_param_service_info" id="nakama_service_param_service_info_id" onchange="onchange_service_info_id(event)">
                        <option value="">全サービス（サービス指定なし）</option>
						<?php foreach ($category_list as $category) { ?>
							<option value="<?= $category->key ?>" <?= $service_info === $category->key ? "selected" : "" ; ?>><?= $category->value ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
            <tr>
                <td>グループID</td>
                <td>
                    <input id="groupTreeServiceSelect" style="width:400px">
                    <input type="hidden" name="nakama_service_param_lg_g_is_root" value="<?php echo $lg_g_is_root; ?>">
                    <input type="hidden" name="nakama_service_param_lg_g_id"  id="nakama_service_param_lg_g_id" value="<?php echo $lg_g_id; ?>">
                    <br><br>
                    <label>
                        <input type="radio" name="nakama_service_param_lg_write" value="0" <?php echo ($lg_write == 0)?'checked':''?>>
                        指定グループのみ
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="nakama_service_param_lg_write" value="1" <?php echo ($lg_write == 1)?'checked':''?>>
                        指定グループ以下
                    </label>
                </td>
            </tr>
			<tr>
				<td>通知先メールアドレス</td>
				<td>
					<input type="mail" name="nakama_service_param_contact_mail" value="<?= $contact_mail !== "" ? $contact_mail : "" ; ?>" style="width: 70%;">
				</td>
			</tr>
			<tr>
				<td>一覧種類</td>
				<td>
<!--					<label>-->
<!--						<input type="radio" name="nakama_service_param_list_type" value="0" --><?//= $list_type === "0" ? "checked" : "" ; ?><!--
						横並び-->
<!--					</label>-->
					<label style="margin-right: 10px">
						<input type="radio" name="nakama_service_param_list_type" value="1" <?= $list_type === "1" ? "checked" : "" ; ?>>
						一覧
					</label>
                    <label  style="margin-right: 10px">
                       	<input type="radio" name="nakama_service_param_list_type" value="0" <?= $list_type === "0" ? "checked" : "" ; ?>>
						横並び
                    </label>
                    <label>
                        <input type="radio" name="nakama_service_param_list_type" value="2" <?= $list_type === "2" ? "checked" : "" ; ?>>
                        掲載申込のみ
                    </label>
				</td>
			</tr>
		</table>
	</div>
</div>
<script>
    jQuery(document).ready(function($){
        var data = <?php echo $json_tree; ?>

            $('#groupTreeServiceSelect').combotree({
                data: data,
                onClick: function(node){
                    if (node.U_LG_ID == null) {
                        $('input[name="nakama_service_param_lg_g_is_root"]').val("root");
                    } else {
                        $('input[name="nakama_service_param_lg_g_is_root"]').val('child');
                    }
                    $('input[name="nakama_service_param_lg_g_id"]').val(node.id);
                }
            });

        var value = '<?php echo $lg_g_id; ?>';
        var t = $('#groupTreeServiceSelect').combotree('tree');
        var node = t.tree('find',value);
        if (node){
            $('#groupTreeServiceSelect').combotree('setValue', value);
            $('input[name="nakama_service_param_lg_g_id"]').val(value);
        }

    });
</script>
