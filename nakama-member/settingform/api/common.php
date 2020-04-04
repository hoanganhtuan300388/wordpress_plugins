<?php
function member_pagination($total,$current_page){
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

function member_get_api_common($post_id, $url, $body_parameters, $method){
  $group_id = get_option('nakama-member-group-id');
  $personal_id = get_option('nakama-member-personal-id');
  $api_key = get_option('nakama-member-api-key');
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
?>