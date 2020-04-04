<?php
function authen_get_api_common($url, $body_parameters, $method){
	$headers = array(
	   'Content-Type' => 'application/json; charset=utf-8',
	   'TG_ID'=> get_option('nakama-authentication-group-id'),
	   'NAKAMA-KEY' => get_option('nakama-authentication-api-key'),
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
function getAliasAuthen(){
  $structure = get_option( 'permalink_structure' );
  return (empty($structure)) ? "&" : "?";
}
?>
