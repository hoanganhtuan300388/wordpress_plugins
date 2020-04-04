<?php
function pagination($total,$current_page){
	$per_page = get_option('nakama-member-list-per-page');
	if ($total > $per_page) {
		$total_page = ceil($total/$per_page);
	}
	else $total_page = 1;
	if($current_page > $total_page)
		$current_page = $total_page;
	if($current_page < 1) $current_page = 1;
	return array(
		"total_page" => $total_page,
		"current_page" => $current_page,
	);
}
function common_pagination($total,$current_page, $per_page){
  if ($total > $per_page) {
    $total_page = ceil($total/$per_page);
  }
  else $total_page = 1;
  if($current_page > $total_page)
    $current_page = $total_page;
  if($current_page < 1) $current_page = 1;
  return array(
    "total_page" => $total_page,
    "current_page" => $current_page,
  );
}
function paginationForShortcode($total,$current_page,$per_page){
	if ($total > $per_page) {
		$total_page = ceil($total/$per_page);
	}
	else $total_page = 1;
	if($current_page > $total_page)
		$current_page = $total_page;
	if($current_page < 1) $current_page = 1;
	return array(
		"total_page" => $total_page,
		"current_page" => $current_page,
	);
}

function get_api_common($post_id = '', $url, $body_parameters, $method){
  $group_id = !empty(get_post_meta($post_id, 'member_meta_group_id', true)) ? get_post_meta($post_id, 'member_meta_group_id', true) : get_option('nakama-member-group-id');
  $personal_id =  !empty(get_post_meta($post_id, 'member_meta_p_id', true)) ? get_post_meta($post_id, 'member_meta_p_id', true) : get_option('nakama-member-personal-id');
  $api_key =  !empty(get_post_meta($post_id, 'member_meta_api_key', true)) ? get_post_meta($post_id, 'member_meta_api_key', true) : get_option('nakama-member-api-key');
  $headers = array(
     'Content-Type' => 'application/json; charset=utf-8',
     'NAKAMA-KEY' => $api_key,
     'TG_ID'=> $group_id,
     'P_ID'=> $personal_id,
  );
  $request = wp_remote_post($url, array(
    'headers'     => $headers,
    'body'        => ($body_parameters)?json_encode($body_parameters):"",
    'method'      => $method,
    'data_format' => 'body',
    'timeout'     => 45,
  ));
  if( is_wp_error( $request ) ) {
    return false;
  }
  return json_decode(wp_remote_retrieve_body( $request ));
}

function uploadFile($g_id){
  $session_id = session_id();
  $path = dirname(__FILE__)."/data/imgs/".$session_id."/";
  if(isset($_FILES) && !empty($_FILES)){
    foreach ($_FILES as $key => $item) {
      $file_name = $_FILES[$key]['name'];
      if(!empty($file_name)) {
        $errors= array();
        $file_path = $path.basename($g_id.$file_name);
        $file_size =$_FILES[$key]['size'];
        $file_tmp =$_FILES[$key]['tmp_name'];
        $file_type=$_FILES[$key]['type'];
        $file_ext=strtolower(end(explode('.',$_FILES[$key]['name'])));

        $expensions= array("bmp","gif","jpeg", "jpg", "png", "tif", "ico",
        "doc", "docx",
        "xls", "xlsx",
        "pdf",
        "wmv", "mp4", "mp3", "avi", "mov", "asf", "flv", "mts");
        if(in_array($file_ext,$expensions)=== false){
           $errors[$key]= $file_name." 画像はアップロードできないファイルです。";
        }
        if(empty($errors)==true){
          if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod(dirname(__FILE__)."/data/", 0777);
            chmod(dirname(__FILE__)."/data/imgs/", 0777);
            chmod(dirname(__FILE__)."/data/imgs/".$session_id."/", 0777);
          }
          $_SESSION['temp_path'] = $path;
          move_uploaded_file($file_tmp, $file_path);
        }
        else{
          if(!empty($errors)){
            foreach ($errors as $key => $error) {
              echo $error."<br>";
            }
          }
        }
      }
    }
  }
}

// Function to remove folders and files
function rrmdir_member($dir) {
  if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $file)
      if ($file != "." && $file != "..") rrmdir_member("$dir/$file");
    rmdir($dir);
  }
  else if (file_exists($dir)) unlink($dir);
}

// Function to Copy folders and files
function moveFile($src, $dst) {
  if (is_dir ( $src )) {
    if (!file_exists($dst)) {
      mkdir($dst, 0777, true);
    }
    $files = scandir ( $src );
    foreach ( $files as $file )
      if ($file != "." && $file != "..")
        moveFile ( "$src/$file", "$dst/$file" );
  } else if (file_exists ( $src )){
    copy ( $src, $dst );
  }
  rrmdir_member($src);
}

//Check slug exists
function member_the_slug_exists($post_name) {
  global $wpdb;
  if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
    return true;
  } else {
    return false;
  }
}

function member_create_new_page($title, $slug){
  $title_check = get_page_by_title($title);
  $page = array(
    'post_type' => 'page',
    'post_title' => $title,
    'post_status' => 'publish',
    'post_name' => $slug,
    'post_slug' => $slug
  );
  if(!isset($title_check->ID) && !member_the_slug_exists($slug)){
    return wp_insert_post($page);
  }
}

function buildTree(array $elements, $U_LG_ID = null) {
  $branch = array();
  foreach ($elements as $element) {
     if ($element->U_LG_ID == $U_LG_ID) {
        $children = buildTree($elements, $element->LG_ID);
        if ($children) {
              $element->state = 'closed';
              $element->children = $children;
        }
        $branch[] = $element;
     }
  }
  return $branch;
}
function print_space($num){
  for ($i=1; $i < (int)$num; $i++) {
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
  }
}
?>
