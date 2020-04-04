<?php 
	session_start();
	session_destroy();
	$url = home_url();
	wp_redirect($url);
	exit;
?>
