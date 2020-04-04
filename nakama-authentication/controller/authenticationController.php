<?php
   final class authenticationController{
      /*
      * GMO Z.Com Runsystem
      * 2019/01/03
      * FUNC: login member
      */
      public static function logins($arrBody) {
         $url = URL_API_AU.'Personal/LogIn';
         $login = authen_get_api_common($url, $arrBody, "POST");
         return $login;
      }
      public static function getPageSlug($slug){
         $page_id = get_page_by_path($slug);
         $page_link = get_page_link($page_id); 
         return $page_link;
      }

      public function getGroupname($TG_ID,$LG_ID,$LG_TYPE){
         $url = URL_API_AU.'Member/getGroupname';
         $arrBody = array(
             "TG_ID" => $TG_ID,
             "LG_ID" => $LG_ID,
             "LG_TYPE" => $LG_TYPE,
         );
         $getGroupname = authen_get_api_common($url, $arrBody, "POST");
         return !empty($getGroupname->TG_NAME)?$getGroupname->TG_NAME:"";
       }
   }
?>
