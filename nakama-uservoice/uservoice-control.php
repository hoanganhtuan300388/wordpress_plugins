<?php
/*
 * Plugin Name: NAKAMA Uservoice
 * Author: DynaX
 * Description: なかま2お客様の声
 */
require( 'common.php' );
require( 'settings/setting_api.php' );
require( 'config/constant.php' );
require( 'controller/uservoiceController.php' );
require( 'settings/class_page_template.php' );
require( 'settingform/index.php' );


add_action( "admin_menu", "add_uservoice_management_plugin_menu_item" );
function add_uservoice_management_plugin_menu_item() {
    add_menu_page( "お客様の声", "お客様の声", "manage_options", "uservoice-management-plugin", "uservoice_management_plugin_settings_page", 'dashicons-format-status', 100 );
    add_submenu_page( 'uservoice-management-plugin', 'API設定', 'API設定', 'manage_options', 'uservoice-management-plugin' );
    add_submenu_page( 'uservoice-management-plugin', 'フォーム設定', 'フォーム設定', 'manage_options', 'edit.php?post_type=setting_uservoice', false );
    add_action( 'admin_init', 'register_nakama_uservoice_plugin_settings' );
}


add_filter( 'nav_menu_link_attributes', 'nakama_uservoice_menu_window_open', 10, 3 );
function nakama_uservoice_menu_window_open( $atts, $item, $args ) {
    $page_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
    $page = get_post( $page_id );

    if ( !empty( $page ) && !empty ( $page->post_content ) ) {
        if ( has_shortcode( $page->post_content, 'customer-voice' ) ) {
            $atts['onclick'] = 'window.open("' . $item->url . '", "お客様の声投稿", "width=1000,height=700,resizable=yes,scrollbars=yes,left=" + (window.screen.width-1000)/2 + ",top=" + (window.screen.height-700)/2)';
            $atts['href'] = '#';
        }
    }

    return $atts;
}


function register_nakama_uservoice_plugin_settings() {
    register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-group-id' );
    register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-personal-id' );
    register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-api-key' );
    //register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-general-per-page' );
    register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-inquiry-types' );
    register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-functions' );
    register_setting( 'nakama-uservoice-plugin-settings-group', 'nakama-uservoice-file-types' );
}


function uservoice_admin_style() {
    wp_enqueue_style( 'admin_uservoice_styles', plugin_dir_url( __FILE__ ) . 'settingform/admin/assets/css/style.css' );
    wp_enqueue_script( 'admin_uservoice_script', plugin_dir_url( __FILE__ ) . 'settingform/admin/assets/js/admin_uservoice.js','','',true );
}
add_action( 'admin_enqueue_scripts', 'uservoice_admin_style' );


// NAKAMA uservoice confirm
$uservoice_confirm = uservoice_create_new_page( 'nakama_setting_uservoice Confirm', 'nakama-confirm-uservoice' );
add_filter( 'page_template', 'confirm_uservoice_page_template' );
function confirm_uservoice_page_template( $page_template )
{
    if ( is_page( 'nakama-confirm-uservoice' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/confirm_user_voice.php';
    }

    return $page_template;
}


// NAKAMA uservoice complete
$uservoice_complete = uservoice_create_new_page( 'nakama_setting_uservoice Complete', 'nakama-complete-uservoice' );
add_filter( 'page_template', 'complete_uservoice_page_template' );
function complete_uservoice_page_template( $page_template )
{
    if ( is_page( 'nakama-complete-uservoice' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/complete_user_voice.php';
    }

    return $page_template;
}


// Deactivation plugin
function uservoice_deactivation_plugin() {
    wp_delete_post( get_page_by_path( 'nakama-confirm-uservoice' )->ID,true );
    wp_delete_post( get_page_by_path( 'nakama-complete-uservoice' )->ID,true );
}
register_deactivation_hook( __FILE__, 'uservoice_deactivation_plugin' );


// Activation plugin
function uservoice_activation_plugin() {
    $file_types = get_option( 'nakama-uservoice-file-types' );
    $default = array(
        array( 'id' => 1, 'name' => '画像ファイル', 'types' => array() ),
        array( 'id' => 2, 'name' => 'WORDファイル', 'types' => array() ),
        array( 'id' => 3, 'name' => 'EXCELファイル', 'types' => array() ),
        array( 'id' => 4, 'name' => 'PPTファイル', 'types' => array() ),
        array( 'id' => 5, 'name' => 'PDFファイル', 'types' => array() ),
        array( 'id' => 6, 'name' => '動画ファイル', 'types' => array() ),
        array( 'id' => 7, 'name' => 'テキストファイル', 'types' => array() )
    );

    if ( empty( $file_types ) || !is_array( $file_types ) ) {
        update_option( 'nakama-uservoice-file-types', $default );
    }
}
register_activation_hook( __FILE__, 'uservoice_activation_plugin' );


function uservoice_exclude_pages_from_menu ( $items, $args ) {
    $arrSlug = [
        'nakama-confirm-uservoice',
        'nakama-complete-uservoice',
    ];
    $arrExistSlug = array();

    foreach ( $arrSlug as $key => $item ) {
        $get_page = get_page_by_path( $item )->ID;
        array_push( $arrExistSlug, $get_page );
    }

    foreach ( $items as $ix => $obj ) {
        if ( in_array( $obj->object_id, $arrExistSlug ) ) {
            unset( $items[$ix] );
        }
    }

    return $items;
}
add_filter( 'wp_nav_menu_objects', 'uservoice_exclude_pages_from_menu', 10, 2 );

//HIDE PAGE IN MENU
function myscript_uservoice() { ?>
    <script type="text/javascript">
        (function($) {
            var arrHidden = [
                'nakama_setting_uservoice Confirm',
                'nakama_setting_uservoice Complete',
            ];
            $("#pagechecklist-most-recent").css('display', 'none');
            $("#pagechecklist-most-recent").css('display', 'block');

            $("body").on('DOMSubtreeModified', "#posttype-page", function() {
                $("#pagechecklist-most-recent li").each(function(index){
                    var parent = $(this);
                    var text = parent.text();
                    arrHidden.forEach(function(e){
                        if(text.toLowerCase().indexOf(e.toLowerCase()) != -1){
                            parent.remove();
                        }
                    });
                });

                $("#pagechecklist li label").each(function (index) {
                    var parent = $(this);
                    var text = parent.text();
                    arrHidden.forEach(function (e) {
                        if (text.toLowerCase().indexOf(e.toLowerCase()) != -1) {
                            parent.remove();
                        }
                    });
                });
            });
            $("#page-uservoice-checklist").css('display', 'none');
            $("#quick-uservoice-posttype-page").change(function(){
                $("#page-uservoice-checklist").trigger("updatecomplete");
            });
            $("#page-uservoice-checklist").bind("updatecomplete", function() {
                $("#page-uservoice-checklist li").each(function(index){
                    var parent = $(this);
                    var text = parent.text();
                    arrHidden.forEach(function(e){
                        if(text.indexOf(e) != -1){
                            parent.remove();
                        }
                    });
                });
                $("#page-uservoice-checklist").css('display', 'block');
            });
            $("#menu-to-edit").css('display', 'none');
            $("#menu-to-edit li").each(function(i){
                var parent = $(this);
                var text = parent.find(".menu-item-title").text();
                arrHidden.forEach(function(e){
                    if(text.indexOf(e) != -1){
                        parent.remove();
                    }
                });
            });
            $("#menu-to-edit").css('display', 'block');
            var post_type = $("input[name=post_type]").val();
            if(post_type == "setting_uservoice"){
                $("#toplevel_page_uservoice-management-plugin").addClass("wp-has-current-submenu");
                $("#toplevel_page_uservoice-management-plugin > a").addClass("wp-has-current-submenu");
            }
        })(jQuery);
    </script>
<?php }
add_action( 'admin_footer', 'myscript_uservoice' );


//HIDE PAGE IN ADMIN
$arrSlug = get_pages( array( 'post_status' => 'publish' ) );
$arrExistSlug = array();
foreach ( $arrSlug as $key => $item ) {
    if ( strpos( $item->post_title, 'nakama_setting_' ) !== false ) {
        $get_page = get_page_by_path( $item->post_name )->ID;
        array_push( $arrExistSlug, $get_page );
    }
}


add_filter( 'parse_query', 'hidden_pages_from_admin_uservoice' );
function hidden_pages_from_admin_uservoice( $query ) {
    global $pagenow, $post_type;
    GLOBAL $arrExistSlug;
    if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'page' ) {
        $query->query_vars['post__not_in'] = $arrExistSlug;
    }
}


add_filter( 'wp_link_query_args', 'uservoice_custom_link_query' );
function uservoice_custom_link_query( $query ) {
    GLOBAL $arrExistSlug;
    $query['post__not_in'] = $arrExistSlug;

    return $query;
}

add_filter( 'page_attributes_dropdown_pages_args', 'uservoice_hide_attr_page_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'uservoice_hide_attr_page_parents' );
function uservoice_hide_attr_page_parents( $args )
{
    GLOBAL $arrExistSlug;
    $args['exclude_tree'] = $arrExistSlug;

    return $args;
}