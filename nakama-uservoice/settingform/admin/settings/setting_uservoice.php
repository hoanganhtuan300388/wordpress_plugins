<?php
$uservoice = new uservoiceController();
$post = get_post();
$tg_id = get_post_meta( $post->ID, 'top_g_id', true );

$meetingList = $uservoice->getMDiscussionList( $post->ID, $tg_id );
$categoryList = array();
if ( !empty( $dis_id ) ) {
    $categoryList = $uservoice->getMDiscussionAuthList( $post->ID, $tg_id, $dis_id );
}
$inquiryTypeList = get_option( 'nakama-uservoice-inquiry-types' );
$functionList = get_option( 'nakama-uservoice-functions' );
$fileTypeList = get_option( 'nakama-uservoice-file-types' );
?>
<div class="setting_view_list">
    <input type="hidden" id="nakama_uservoice_param_tg_id" name="nakama_uservoice_param_tg_id" value="<?php echo esc_attr( $tg_id ); ?>">
    <table class="setting-table-param member_posttype" style="margin-bottom:20px;">
        <tr>
            <td><?php echo '会議室ID'; ?></td>
            <td>
                <select class="mt-5" style="width: 80%" name="nakama_uservoice_param_meeting" id="nakama_uservoice_param_meeting">
                    <option value="">--------</option>
                    <?php if ( !empty( $meetingList->data ) ) { ?>
                        <?php foreach ( $meetingList->data as $key => $item ) { ?>
                            <option value="<?php echo $item->DIS_ID; ?>" <?php echo ( $dis_id == $item->DIS_ID ) ? 'selected' : ''; ?>><?php echo $item->DIS_NM; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <span class="img_theme_loading" style="display: none;">
                    <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/loadding.gif'; ?>">
                </span>
            </td>
        </tr>
        <tr>
            <td><?php echo 'カテゴリー'; ?></td>
            <td>
                <select class="mt-5" style="width: 80%" name="nakama_uservoice_param_category" id="nakama_uservoice_param_category">
                    <option value="">--------</option>
                    <?php if ( !empty( $categoryList->data ) ) { ?>
                        <?php foreach ( $categoryList->data as $key => $item ) { ?>
                            <option value="<?php echo $item->CATEGORY; ?>" <?php echo ( $category == $item->CATEGORY ) ? 'selected' : ''; ?>><?php echo $item->CATEGORY; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo '問い合わせ種類'; ?></td>
            <td>
                <div class="item-list-input">
                    <input type="text" name="nakama_uservoice_inquiry_type_name" id="nakama_uservoice_inquiry_type_name">
                    <input type="hidden" name="nakama_uservoice_inquiry_type_id" id="nakama_uservoice_inquiry_type_id">
                    <button type="button" class="components-button" id="btn-save-inquiry-type">
                        <?php echo '設定'; ?>
                    </button>
                    <span class="img_theme_loading_inquiry_type" style="display: none;">
                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/loadding.gif'; ?>">
                    </span>
                </div>
                <div class="item-type-list-display">
                    <?php if ( !empty ( $inquiryTypeList ) && is_array( $inquiryTypeList ) ) { ?>
                        <?php foreach ( $inquiryTypeList as $index => $inq ) { ?>
                            <div class="item-list-display-item" id="item-list-display-item-<?php echo $inq['id']; ?>">
                                <input class="chk-uservoice-item" name="nakama_uservoice_inquiry_type[]" id="nakama_uservoice_inquiry_type_<?php echo $inq['id']; ?>" value="<?php echo $inq['id']; ?>" <?php if ( in_array( $inq['id'], $inquiry_type ) ) { echo 'checked="checked"'; } ?> type="checkbox">
                                <label for="nakama_uservoice_inquiry_type_<?php echo $inq['id']; ?>" id="nakama_uservoice_lbl_inquiry_type_<?php echo $inq['id']; ?>"><?php echo $inq['name']; ?></label>
                                <div class="item-display-action">
                                    <a href="javascript:void(0)" onclick="deleteInquiryType(<?php echo $inq['id']; ?>)">
                                        <?php echo '削除'; ?>
                                    </a>&nbsp;|&nbsp;
                                    <a href="javascript:void(0)" onclick="editInquiryType(<?php echo $inq['id']; ?>, '<?php echo $inq['name']; ?>')">
                                        <?php echo '編集'; ?>
                                    </a>&nbsp;|&nbsp;
                                    <a href="javascript:void(0)" onclick="editInquiryTypeUp(<?php echo $inq['id']; ?>)">
                                        <?php echo '↑'; ?>
                                    </a>&nbsp;|&nbsp;
                                    <a href="javascript:void(0)" onclick="editInquiryTypeDown(<?php echo $inq['id']; ?>)">
                                        <?php echo '↓'; ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo '機能'; ?></td>
            <td>
                <div class="item-list-input">
                    <input type="text" name="nakama_uservoice_function_name" id="nakama_uservoice_function_name">
                    <input type="hidden" name="nakama_uservoice_function_id" id="nakama_uservoice_function_id">
                    <button type="button" class="components-button" id="btn-save-function">
                        <?php echo '設定'; ?>
                    </button>
                    <span class="img_theme_loading_function" style="display: none;">
                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/loadding.gif'; ?>">
                    </span>
                </div>
                <div class="item-function-list-display">
                    <?php if ( !empty( $functionList ) && is_array( $functionList ) ) { ?>
                        <?php foreach ( $functionList as $index => $func ) { ?>
                            <div class="item-list-display-item" id="item-function-list-display-item-<?php echo $func['id']; ?>">
                                <input class="chk-uservoice-item" name="nakama_uservoice_function[]" id="nakama_uservoice_function_<?php echo $func['id']; ?>" value="<?php echo $func['id']; ?>" <?php if ( in_array( $func['id'], $function ) ) { echo 'checked="checked"'; } ?> type="checkbox">
                                <label for="nakama_uservoice_function_<?php echo $func['id']; ?>" id="nakama_uservoice_lbl_function_<?php echo $func['id']; ?>"><?php echo $func['name']; ?></label>
                                <div class="item-display-action">
                                    <a href="javascript:void(0)" onclick="deleteFunction(<?php echo $func['id']; ?>)">
                                        <?php echo '削除'; ?>
                                    </a>&nbsp;|&nbsp;
                                    <a href="javascript:void(0)" onclick="editFunction(<?php echo $func['id']; ?>, '<?php echo $func['name']; ?>')">
                                        <?php echo '編集'; ?>
                                    </a>&nbsp;|&nbsp;
                                    <a href="javascript:void(0)" onclick="editFunctionUp(<?php echo $func['id']; ?>)">
                                        <?php echo '↑'; ?>
                                    </a>&nbsp;|&nbsp;
                                    <a href="javascript:void(0)" onclick="editFunctionDown(<?php echo $func['id']; ?>)">
                                        <?php echo '↓'; ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo 'アップロードファイルの種類'; ?></td>
            <td>
                <div class="item-list-input">
                    <select class="mt-5" style="width: 80%" name="nakama_uservoice_file_type_cate" id="nakama_uservoice_file_type_cate">
                        <option value="">--------</option>
                        <?php if ( !empty( $fileTypeList ) ) { ?>
                            <?php foreach ( $fileTypeList as $key => $cate ) { ?>
                                <option value="<?php echo $cate['id']; ?>"><?php echo $cate['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <br />
                    <input type="text" name="nakama_uservoice_file_type_name" id="nakama_uservoice_file_type_name">
                    <input type="hidden" name="nakama_uservoice_file_type_id" id="nakama_uservoice_file_type_id">
                    <button type="button" class="components-button" id="btn-save-file-type">
                        <?php echo '設定'; ?>
                    </button>
                    <span class="img_theme_loading_file_type" style="display: none;">
                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/loadding.gif'; ?>">
                    </span>
                </div>
                <div class="item-type-list-file-type">
                    <?php if ( !empty( $fileTypeList ) && is_array( $fileTypeList ) ) { ?>
                        <?php foreach ( $fileTypeList as $index => $category ) { ?>
                            <div class="item-list-display-item" id="item-category-file-type-list-display-item-<?php echo $category['id']; ?>" style="<?php if ( count( $category['types'] ) <= 0 ) { ?>display: none<?php } ?>">
                                <label for="nakama_uservoice_category_file_type_<?php echo $category['id']; ?>" id="nakama_uservoice_lbl_category_file_type_<?php echo $category['id']; ?>"><?php echo $category['name']; ?></label>
                                <?php foreach ( $category['types'] as $file ) { ?>
                                    <div class="item-list-display-item" id="item-file-type-list-display-item-<?php echo $file['id']; ?>">
                                        &nbsp;&nbsp;&nbsp;
                                        <input class="chk-uservoice-item" name="nakama_uservoice_file_type[]" id="nakama_uservoice_file_type_<?php echo $file['id']; ?>" value="<?php echo $file['id']; ?>" <?php if ( in_array( $file['id'], $file_type ) ) { echo 'checked="checked"'; } ?> type="checkbox">
                                        <label for="nakama_uservoice_file_type_<?php echo $file['id']; ?>" id="nakama_uservoice_lbl_file_type_<?php echo $file['id']; ?>"><?php echo $file['name']; ?></label>
                                        <div class="item-display-action">
                                            <a href="javascript:void(0)" onclick="deleteFileType(<?php echo $file['id']; ?>)">
                                                <?php echo '削除'; ?>
                                            </a>&nbsp;|&nbsp;
                                            <a href="javascript:void(0)" onclick="editFileType(<?php echo $file['id']; ?>, '<?php echo $file['name']; ?>', <?php echo $category['id']; ?>)">
                                                <?php echo '編集'; ?>
                                            </a>&nbsp;|&nbsp;
                                            <a href="javascript:void(0)" onclick="editFileTypeUp(<?php echo $file['id']; ?>, <?php echo $category['id']; ?>)">
                                                <?php echo '↑'; ?>
                                            </a>&nbsp;|&nbsp;
                                            <a href="javascript:void(0)" onclick="editFileTypeDown(<?php echo $file['id']; ?>, <?php echo $category['id']; ?>)">
                                                <?php echo '↓'; ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
</div>