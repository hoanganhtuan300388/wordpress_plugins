<?php
  $members = new memberController();
  $dataSetting = get_post_meta( $args['id'] );
  $tg_id = $dataSetting['nak_member_magazine_tg_id'][0];
  $g_id = $dataSetting['nak_member_magazine_g_id'][0];
  $type_magazine = $dataSetting['nakama_member_type_magazine'][0];

  $page_link = $members->getPageSlug('nakama-login');
  $path_page = get_page_uri();
  if($type_magazine == 1)
    include 'reg_magazine.php';
  else
    include 'del_magazine.php';
