<?php
function service_pagination($total,$current_page, $per_page){
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

function service_get_common_call_api($post_id = '', $url, $body_parameters, $method){
	$group_id = !empty(get_post_meta($post_id, 'service_meta_group_id', true)) ? get_post_meta($post_id, 'service_meta_group_id', true) : get_option('nakama-service-group-id');
	$personal_id = !empty(get_post_meta($post_id, 'service_meta_p_id', true)) ? get_post_meta($post_id, 'service_meta_p_id', true) : get_option('nakama-service-personal-id');
	$api_key = !empty(get_post_meta($post_id, 'service_meta_api_key', true)) ? get_post_meta($post_id, 'service_meta_api_key', true) : get_option('nakama-service-api-key');
	$headers = array(
		 'Content-Type' => 'application/json; charset=utf-8',
		 'NAKAMA-KEY' => $api_key,
		 'TG_ID'=> $group_id,
		 'P_ID'=> $personal_id,
	);
	$request = wp_remote_post($url, array(
		'headers'		 => $headers,
		'body'				=> ($body_parameters)?json_encode($body_parameters, JSON_UNESCAPED_UNICODE):"",
		'method'			=> $method,
		'data_format' => 'body',
		'timeout'		 => 45,
	));
	if( is_wp_error( $request ) ) {
		 return false;
	}
	return json_decode(wp_remote_retrieve_body( $request ));
}

//Check slug exists
function service_the_slug_exists($post_name) {
	global $wpdb;
	if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
		return true;
	} else {
		return false;
	}
}

function service_create_new_page($title, $slug){
	$title_check = get_page_by_title($title);
	$page = array(
		'post_type' => 'page',
		'post_title' => $title,
		'post_status' => 'publish',
		'post_name' => $slug,
		'post_slug' => $slug
	);
	if(!isset($title_check->ID) && !service_the_slug_exists($slug)){
		return wp_insert_post($page);
	}
}

 function getAliasService(){
	 $structure = get_option( 'permalink_structure' );
	 return (empty($structure)) ? "&" : "?";
 }

 function convert_date($the_date){
     $year= substr($the_date,0, 4);
     $mouth = substr($the_date,5,2);
     $day = substr($the_date,8,2);
     return $year."年".$mouth."月".$day."日";
 }
function buildTreeMeetingService(array $elements, $U_LG_ID = null) {
    $branch = array();
    foreach ($elements as $element) {
        if ($element->U_LG_ID == $U_LG_ID) {
            $children = buildTreeMeetingService($elements, $element->LG_ID);
            if ($children) {
                //$element->state = 'closed';
                $element->state = 'opened';
                $element->children = $children;
            }
            $branch[] = $element;
        }
    }
    return $branch;
}

// // Function to remove folders and files
// function service_rrmdir($dir) {
//	 if (is_dir($dir)) {
//		 $objects = scandir($dir);
//		 foreach ($objects as $object) {
//			 if ($object != "." && $object != "..") {
//				 if (filetype($dir."/".$object) == "dir")
//						service_rrmdir($dir."/".$object);
//				 else unlink	 ($dir."/".$object);
//			 }
//		 }
//		 reset($objects);
//		 rmdir($dir);
//	 }
// }
// // Function to Copy folders and files
// function service_moveFile($src, $dst) {
//	 if (is_dir ( $src )) {
//		 if (!file_exists($dst)) {
//			 mkdir($dst, 0777, true);
//		 }
//		 $files = scandir ( $src );
//		 foreach ( $files as $file )
//			 if ($file != "." && $file != "..")
//				 service_moveFile ( "$src/$file", "$dst/$file" );
//	 } else if (file_exists ( $src )){
//		 copy ( $src, $dst );
//	 }
//	 service_rrmdir($src);
// }
//
// function insert_nakama_service($tg_id, $write_comment_flg, $write_comment_open, $open_kbn) {
//	 global $wpdb;
//	 $table_name = $wpdb->prefix . 'nakama_service';
//	 return $wpdb->insert(
//		 $table_name,
//		 array(
//			 'tg_id' => $tg_id,
//			 'write_comment_flg' => $write_comment_flg,
//			 'write_comment_open' => $write_comment_open,
//			 'open_kbn' => $open_kbn,
//		 )
//	 );
// }
//
// function update_nakama_service($tg_id, $write_comment_flg, $write_comment_open, $open_kbn) {
//	 global $wpdb;
//	 $table_name = $wpdb->prefix . 'nakama_service';
//	 return $wpdb->update(
//		 $table_name,
//		 array(
//			 'write_comment_flg' => $write_comment_flg,
//			 'write_comment_open' => $write_comment_open,
//			 'open_kbn' => $open_kbn,
//		 ),
//		 array( 'tg_id' => $tg_id )
//	 );
// }
//
// function service_check_tg_id_exists($tg_id) {
//	 global $wpdb;
//	 $table_name = $wpdb->prefix . 'nakama_service';
//	 if($wpdb->get_row("SELECT tg_id FROM $table_name WHERE tg_id = '" . $tg_id . "'", 'ARRAY_A')) {
//		 return true;
//	 } else {
//		 return false;
//	 }
// }
//
// function set_data_nakama_service($tg_id, $write_comment_flg, $write_comment_open, $open_kbn){
//	 if(!empty($tg_id)){
//		 if(!service_check_tg_id_exists($tg_id)){
//			 return insert_nakama_service($tg_id, $write_comment_flg, $write_comment_open, $open_kbn);
//		 }else{
//			 return update_nakama_service($tg_id, $write_comment_flg, $write_comment_open, $open_kbn);
//		 }
//	 }
// }
//
// function get_data_nakama_service($tg_id){
//	 global $wpdb;
//	 $table_name = $wpdb->prefix . 'nakama_service';
//	 if(service_check_tg_id_exists($tg_id)){
//		 $data = $wpdb->get_row( "SELECT * FROM $table_name WHERE tg_id = '" . $tg_id . "'", 'OBJECT');
//		 return $data;
//	 }
//	 return false;
// }
//
// function Nvl($a, $b){
//	 echo (!empty($a)) ?	$a : $b;
// }
//

//
// function lightning_bread_crumb_service() {
// 	global $wp_query;
// 	$postType = lightning_get_post_type();
// 	$page_for_posts = lightning_get_page_for_posts();
// 	$microdata_li				= ' itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"';
// 	$microdata_li_a			= ' itemprop="item"';
// 	$microdata_li_a_span = ' itemprop="name"';
//
// 	$panListHtml = '<!-- [ .breadSection ] -->
//	 <div class="section breadSection">
//	 <div class="container">
//	 <div class="row">
//	 <ol class="breadcrumb" itemtype="http://schema.org/BreadcrumbList">';
//
// 	$panListHtml .= '<li id="panHome"' . $microdata_li . '><a' . $microdata_li_a . ' href="' . home_url( '/' ) . '"><span' . $microdata_li_a_span . '><i class="fa fa-home"></i> HOME</span></a></li>';
//
// 	/* Post type
// 	/*-------------------------------*/
//
// 	if ( is_archive() || ( is_single() && ! is_attachment() ) ) {
//
// 		if ( $postType['slug'] == 'post' || is_category() || is_tag() ) { /* including single-post */
// 			if ( $page_for_posts['post_top_use'] ) {
// 				if ( ! is_home() ) {
// 					$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . esc_url( $postType['url'] ) . '"><span' . $microdata_li_a_span . '>' . $postType['name'] . '</span></a></li>';
// 				} else {
// 					$panListHtml .= '<li><span>' . the_title( '', '', false ) . '</span></li>';
// 				}
// 			}
// 		} else {
// 			if ( is_single() || is_year() || is_month() || is_day() || is_tax() || is_author() ) {
// 				$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . esc_url( $postType['url'] ) . '"><span' . $microdata_li_a_span . '>' . $postType['name'] . '</span></a></li>';
// 			} else {
// 				$panListHtml .= '<li><span>' . $postType['name'] . '</span></li>';
// 			}
// 		}
// 	}
//
// 	if ( is_home() ) {
//
// 		/*
// 		When use to post top page
// 		When "is_page()" that post top is don't display.
// 		*/
// 		if ( isset( $postType['name'] ) && $postType['name'] ) {
// 			$panListHtml .= '<li><span>' . $postType['name'] . '</span></li>';
// 		}
// 	} elseif ( is_category() ) {
//
// 		/* Category
// 		/*-------------------------------*/
//
// 		// Get category information & insert to $cat
// 		$cat = get_queried_object();
//
// 		// parent != 0	>>>	Parent exist
// 		if ( $cat->parent != 0 ) :
// 			// 祖先のカテゴリー情報を逆順で取得
// 			$ancestors = array_reverse( get_ancestors( $cat->cat_ID, 'category' ) );
// 			// 祖先階層の配列回数分ループ
// 			foreach ( $ancestors as $ancestor ) :
// 				$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . get_category_link( $ancestor ) . '"><span' . $microdata_li_a_span . '>' . esc_html( get_cat_name( $ancestor ) ) . '</span></a></li>';
// 			endforeach;
// 			endif;
// 		$panListHtml .= '<li><span>' . $cat->cat_name . '</span></li>';
//
// 	} elseif ( is_tag() ) {
//
// 		/* Tag
// 		/*-------------------------------*/
//
// 		$tagTitle		 = single_tag_title( '', false );
// 		$panListHtml .= '<li><span>' . $tagTitle . '</span></li>';
//
// 	} elseif ( is_tax() ) {
//
// 		/* term
// 		/*-------------------------------*/
//
// 		$now_term				= $wp_query->queried_object->term_id;
// 		$now_term_parent = $wp_query->queried_object->parent;
// 		$now_taxonomy		= $wp_query->queried_object->taxonomy;
//
// 		// parent が !0 の場合 = 親カテゴリーが存在する場合
// 		if ( $now_term_parent != 0 ) :
// 			// 祖先のカテゴリー情報を逆順で取得
// 			$ancestors = array_reverse( get_ancestors( $now_term, $now_taxonomy ) );
// 			// 祖先階層の配列回数分ループ
// 			foreach ( $ancestors as $ancestor ) :
// 				$pan_term		 = get_term( $ancestor, $now_taxonomy );
// 				$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . get_term_link( $ancestor, $now_taxonomy ) . '"><span' . $microdata_li_a_span . '>' . esc_html( $pan_term->name ) . '</a></li>';
// 			endforeach;
// 		endif;
//
// 		$panListHtml .= '<li><span>' . esc_html( single_cat_title( '', '', false ) ) . '</span></li>';
//
// 	} elseif ( is_author() ) {
//
// 		/* Author
// 		/*-------------------------------*/
//
// 		$userObj			= get_queried_object();
// 		$panListHtml .= '<li><span>' . esc_html( $userObj->display_name ) . '</span></li>';
//
// 	} elseif ( is_archive() && ( ! is_category() || ! is_tax() ) ) {
//
// 		/* Year / Monthly / Dayly
// 		/*-------------------------------*/
//
// 		if ( is_year() || is_month() || is_day() ) {
// 			$panListHtml .= '<li><span>' . esc_html( get_the_archive_title() ) . '</span></li>';
// 		}
// 	} elseif ( is_single() ) {
//
// 		/* Single
// 		/*-------------------------------*/
//
// 		// Case of post
//
// 		if ( $postType['slug'] == 'post' ) {
// 			$category = get_the_category();
// 			if ( $category ) {
// 				// get parent category info
// 				$parents = array_reverse( get_ancestors( $category[0]->term_id, 'category', 'taxonomy' ) );
// 				array_push( $parents, $category[0]->term_id );
// 				foreach ( $parents as $parent_term_id ) {
// 					$parent_obj	 = get_term( $parent_term_id, 'category' );
// 					$term_url		 = get_term_link( $parent_obj->term_id, $parent_obj->taxonomy );
// 					$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . $term_url . '"><span' . $microdata_li_a_span . '>' . esc_html( $parent_obj->name ) . '</span></a></li>';
// 				}
// 			}
//
// 			// Case of custom post type
//
// 		} else {
// 			$taxonomies = get_the_taxonomies();
//
// 			// To avoid WooCommerce default tax
// 			foreach ( $taxonomies as $key => $value ) {
// 				if ( $key != 'product_type' ) {
// 					$taxonomy = $key;
// 					break;
// 				}
// 			}
//
// 			if ( $taxonomies ) :
// 				$terms = get_the_terms( get_the_ID(), $taxonomy );
//
// 				//keeps only the first term (categ)
// 				$term = reset( $terms );
// 				if ( 0 != $term->parent ) {
//
// 					// Get term ancestors info
// 					$ancestors = array_reverse( get_ancestors( $term->term_id, $taxonomy ) );
// 					// Print loop term ancestors
// 					foreach ( $ancestors as $ancestor ) :
// 						$pan_term		 = get_term( $ancestor, $taxonomy );
// 						$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . get_term_link( $ancestor, $taxonomy ) . '"><span' . $microdata_li_a_span . '>' . esc_html( $pan_term->name ) . '</span></a></li>';
// 					endforeach;
// 				}
// 				$term_url		 = get_term_link( $term->term_id, $taxonomy );
// 				$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . $term_url . '"><span' . $microdata_li_a_span . '>' . esc_html( $term->name ) . '</span></a></li>';
// 				endif;
//
// 		}
//
// 			$panListHtml .= '<li><span>' . get_the_title() . '</span></li>';
//
// 	} elseif ( is_page() ) {
//
// 		/* Page
// 		/*-------------------------------*/
//
// 		$post = $wp_query->get_queried_object();
// 		if ( $post->post_parent == 0 ) {
// 			$panListHtml .= '<li><span>' . get_the_title() . '</span></li>';
// 		} else {
// 			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
// 			array_push( $ancestors, $post->ID );
// 			foreach ( $ancestors as $ancestor ) {
// 				if ( $ancestor != end( $ancestors ) ) {
// 					$panListHtml .= '<li' . $microdata_li . '><a' . $microdata_li_a . ' href="' . get_permalink( $ancestor ) . '"><span' . $microdata_li_a_span . '>' . strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) . '</span></a></li>';
// 				} else {
// 					$panListHtml .= '<li><span>' . strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) . '</span></li>';
// 				}
// 			}
// 		}
// 	} elseif ( is_404() ) {
//
// 		/* 404
// 		/*-------------------------------*/
//
// 		$panListHtml .= '<li><span>' . __( 'Not found', 'lightning' ) . '</span></li>';
//
// 	} elseif ( is_search() ) {
//
// 		/* Search result
// 		/*-------------------------------*/
//
// 		$panListHtml .= '<li><span>' . sprintf( __( 'Search Results for : %s', 'lightning' ), get_search_query() ) . '</span></li>';
//
// 	} elseif ( is_attachment() ) {
//
// 		/* Attachment
// 		/*-------------------------------*/
//
// 		$panListHtml .= '<li><span>' . the_title( '', '', false ) . '</span></li>';
//
// 	}
// 	$panListHtml .= '</ol>
//	 </div>
//	 </div>
//	 </div>
//	 <!-- [ /.breadSection ] -->';
// 	return $panListHtml;
// }
//
// function buildTreeService(array $elements, $U_LG_ID = null) {
//	 $branch = array();
//	 foreach ($elements as $element) {
//			if ($element->U_LG_ID == $U_LG_ID) {
//				 $children = buildTreeService($elements, $element->LG_ID);
//				 if ($children) {
//							 $element->state = 'closed';
//							 $element->children = $children;
//				 }
//				 $branch[] = $element;
//			}
//	 }
//	 return $branch;
// }
//
// function checkImageFile($name_type){
// 	$arrImg = array("bmp","gif","jpeg", "jpg", "png", "tif", "ico");
// 	if(!empty($name_type)){
// 		$get_type_file = explode('.', $name_type);
// 		$type_file = strtolower($get_type_file[count($get_type_file) - 1]);
// 	}
// 	if(in_array($type_file, $arrImg)){
// 		return 1;
// 	}
// 	return 0;
// }
//
// function getSrcFile($name){
// 	return SERVICE_ROOT_IMG."/data/imgs/".$name;
// }
