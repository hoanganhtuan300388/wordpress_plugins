<?php
/*
 * Plugin Name: NAKAMA Authentication
 * Author: DynaX
 * Description: なかま２会員認証プラグイン
 */

require('settings/setting.php');
require('common.php');
require_once('config/constant.php');
require('shortcode/login.php');
add_action('init', 'StartCookieAuthentication', 1);
function StartCookieAuthentication() {

}
add_action('init', 'StartSessionAuthentication', 1);
function StartSessionAuthentication() {

  if(!session_id()) {
    session_start();
    $expireAfter = 5;

    if(isset($_SESSION['last_action'])){

      $secondsInactive = time() - $_SESSION['last_action'];

      $expireAfterSeconds = $expireAfter * 60;

      if($secondsInactive >= $expireAfterSeconds){
        session_unset();
        session_destroy();
      }
    }
    $_SESSION['last_action'] = time();
  }
}
add_action("admin_menu", "add_authentication_management_plugin_menu_item");
function add_authentication_management_plugin_menu_item(){
    add_menu_page("会員認証", "会員認証", "manage_options",
        "authen-management-setting", "authentication_management_plugin_settings_page", 'dashicons-businessman', 99);
    add_action( 'admin_init', 'register_nakama_authentication_plugin_settings' );
}
function register_nakama_authentication_plugin_settings() {
  register_setting( 'nakama-authentication-plugin-settings-group', 'nakama-authentication-tg-id' );
  register_setting( 'nakama-authentication-plugin-settings-group', 'nakama-authentication-lg-id' );
  register_setting( 'nakama-authentication-plugin-settings-group', 'nakama-authentication-group-id' );
  register_setting( 'nakama-authentication-plugin-settings-group', 'nakama-authentication-api-key' );
}

// Custom css admin
function authentication_admin_style() {
  wp_enqueue_style('admin-authen-styles', plugin_dir_url( __FILE__ ).'assets/css/admin.css');
  wp_enqueue_script( 'admin_script', plugin_dir_url( __FILE__ ) . 'assets/admin/dual-list-box.js' );
  wp_enqueue_script( 'admin_script_authen', plugin_dir_url( __FILE__ ) . 'assets/admin/authen.js' );
}
add_action('admin_enqueue_scripts', 'authentication_admin_style');
function authentication_management_plugin_settings_page(){
  ?>
    <div class="wrap setting_view_list">
      <h1 class="setting_title">会員認証設定</h1>
      <form method="post" action="options.php">
        <?php settings_fields( 'nakama-authentication-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'nakama-authentication-plugin-settings-group' ); ?>
        <table class="form-table nakama-setting">
            <tr valign="top">
              <th scope="row">団体ID</th>
              <td>
                <input type="text" name="nakama-authentication-group-id" onchange="renPublicPID(event)" value="<?php echo esc_attr( get_option('nakama-authentication-group-id') ); ?>">
                <span class="exam_label">例: cloudnakama</span>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">APIキー</th>
              <td>
                <textarea rows="3" cols="80" name="nakama-authentication-api-key"><?php echo esc_attr( get_option('nakama-authentication-api-key') ); ?></textarea>
                <span class="exam_label">例: Hgfhbd5tG5zzZfJodjNyDEVDkEcFf9HPj1vptBELi7GFVzcM8aaUf9ocCPFbzTzZYTN+y5+SA4XnqNtOAzeQiQ==</span>
              </td>
            </tr>
        </table>
        <?php submit_button('変更保存'); ?>
      </form>
    </div>
  <?php
}

//Check slug exists
function authentication_page_the_slug_exists($post_name) {
  global $wpdb;
  if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
    return true;
  } else {
    return false;
  }
}
// Deactivation plugin
function authentication_deactivation_plugin() {
  wp_delete_post(get_page_by_path('nakama-login')->ID,true);
  wp_delete_post(get_page_by_path('nakama-logout')->ID,true);
  wp_delete_post(get_page_by_path('nakama-forgot')->ID,true);
  wp_delete_post(get_page_by_path('nakama-inquirie')->ID,true);
  wp_delete_post(get_page_by_path('nakama-forgot-confirm')->ID,true);
  wp_delete_post(get_page_by_path('nakama-forgot-complete')->ID,true);
}
register_deactivation_hook( __FILE__, 'authentication_deactivation_plugin' );

// NAKAMA Authentication List
$list_authentication_format_login_title = 'nakama_setting_login';
$list_authentication_format_login_check = get_page_by_title($list_authentication_format_login_title);
$list_authentication_format_login_page = array(
  'post_type' => 'page',
  'post_title' => $list_authentication_format_login_title,
  'post_status' => 'publish',
  'post_name' => 'nakama-login',
  'post_slug' => 'nakama-login'
);
if(!isset($list_authentication_format_login_check->ID) && !authentication_page_the_slug_exists('nakama-login')){
    $list_member_authentication_login_page_id = wp_insert_post($list_authentication_format_login_page);
}

// Change path page custom template
add_filter( 'page_template', 'list_authentication_format_login_page_template' );
function list_authentication_format_login_page_template( $page_template )
{
    if ( is_page( 'nakama-login' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/login.php';
    }
    return $page_template;
}

// NAKAMA Authentication List
$list_authentication_format_logout_title = 'nakama_setting_logout';
$list_authentication_format_logout_check = get_page_by_title($list_authentication_format_logout_title);
$list_authentication_format_logout_page = array(
  'post_type' => 'page',
  'post_title' => $list_authentication_format_logout_title,
  'post_status' => 'publish',
  'post_name' => 'nakama-logout',
  'post_slug' => 'nakama-logout'
);
if(!isset($list_authentication_format_logout_check->ID) && !authentication_page_the_slug_exists('nakama-logout')){
    $list_member_authentication_logout_page_id = wp_insert_post($list_authentication_format_logout_page);
}

// Change path page custom template
add_filter( 'page_template', 'list_authentication_format_logout_page_template' );
function list_authentication_format_logout_page_template( $page_template )
{
    if ( is_page( 'nakama-logout' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/logout.php';
    }
    return $page_template;
}

function myscript_jquery_authen() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'admin_head' , 'myscript_jquery_authen' );

function myscript_authen() {
?>
<script type="text/javascript">
  (function($) {
    var arrHidden = [
      'nakama_setting_login',
      'nakama_setting_logout',
    ];
    
    $("#pagechecklist-most-recent").css('display', 'none');
    $("#pagechecklist-most-recent li").each(function(index){
      var parent = $(this);
      var text = parent.text();
      arrHidden.forEach(function(e){
        if(text.indexOf(e) != -1){
          parent.remove();
        }
      });
    });
    $("#pagechecklist-most-recent").css('display', 'block');

    $("#pagechecklist li label").each(function(index){
      var parent = $(this);
      var text = parent.text();
      arrHidden.forEach(function(e){
        if(text.indexOf(e) != -1){
          parent.remove();
        }
      });
    });
    $("#page-search-checklist").css('display', 'none');
    $("#quick-search-posttype-page").change(function(){
      $("#page-search-checklist").trigger("updatecomplete");
    });
    $("#page-search-checklist").bind("updatecomplete", function() {
      $("#page-search-checklist li").each(function(index){
        var parent = $(this);
        var text = parent.text();
        arrHidden.forEach(function(e){
          if(text.indexOf(e) != -1){
            parent.remove();
          }
        });
      });
      $("#page-search-checklist").css('display', 'block');
    });
    $("#menu-to-edit li").each(function(i){
      var parent = $(this);
      var text = parent.find(".menu-item-title").text();
      arrHidden.forEach(function(e){
        if(text.indexOf(e) != -1){
          parent.remove();
        }
    });
    });
  })(jQuery);
</script>
<?php
}
add_action( 'admin_footer', 'myscript_authen' );

function authen_exclude_pages_from_menu ($items, $args) {
  $arrSlug = [
    'nakama-login',
    'nakama-logout',
    'nakama-forgot',
    'nakama-inquirie',
    'nakama-forgot-confirm',
    'nakama-forgot-complete'
  ];
  $arrExistSlug = array();
  foreach ($arrSlug as $key => $item) {
    if(get_page_by_path($item)){
      $get_page = get_page_by_path($item)->ID;
      array_push($arrExistSlug, $get_page);
    }
  }
  foreach ($items as $ix => $obj) {
    if (in_array($obj->object_id, $arrExistSlug)) {
        unset ($items[$ix]);
    }
  }
  return $items;
}
add_filter ('wp_nav_menu_objects', 'authen_exclude_pages_from_menu', 10, 2);

add_action('wp_head', 'header_login_container');
function header_login_container(){
  if(!empty($_SESSION['arrSession'])) : ?>
    <div class="fz-14 add_user_name_logined container" style="text-align: right;text-align: right;position: fixed;top: 5px;z-index: 9999;right: 38px;font-weight: 400;">
      <?php echo $_SESSION['login_TG_NAME']; ?>　ようこそ&nbsp;
        <?php echo $_SESSION['arrSession']->GNAME; ?>&nbsp;
        <?php echo $_SESSION['arrSession']->USER_NAME; ?>&nbsp;さん
    / <a href="<?php echo get_permalink(get_page_by_path('nakama-logout')->ID); ?>">ログアウト</a>
    </div>
  <?php endif;
}

//HIDE PAGE IN ADMIN
$arrSlug = get_pages(array('post_status' => 'publish'));
$arrExistSlug = array();
foreach ($arrSlug as $key => $item) {
  if (strpos($item->post_title, 'nakama_setting_') !== false) {
    $get_page = get_page_by_path($item->post_name)->ID;
    array_push($arrExistSlug, $get_page);
  }
}
add_filter( 'parse_query', 'hidden_pages_from_admin_authen' );
function hidden_pages_from_admin_authen($query) {
    global $pagenow, $post_type;
    GLOBAL $arrExistSlug;
    if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
        $query->query_vars['post__not_in'] = $arrExistSlug;
    }
}

add_filter( 'wp_link_query_args', 'authen_custom_link_query' );
function authen_custom_link_query( $query ){
  GLOBAL $arrExistSlug;
  $query['post__not_in'] = $arrExistSlug;  
  return $query; 
}

add_filter( 'page_attributes_dropdown_pages_args', 'authen_hide_attr_page_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'authen_hide_attr_page_parents' );
function authen_hide_attr_page_parents( $args )
{   
  GLOBAL $arrExistSlug;
  $args['exclude_tree'] = $arrExistSlug;
  return $args;
}
