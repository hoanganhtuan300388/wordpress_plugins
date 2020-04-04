<?php
final class serviceController{

	//引数数値チェック
	public function checkReqParamNumeric($param){
		if(is_numeric($param) === false){
			//不正な呼び出し
			return false;
		}
	}
	private function makeUriParams($body_parameters){
		$request_params = "";
			if (isset($body_parameters)){
				foreach ($body_parameters as $key => $value) {
				if($request_params !== ""){
					$request_params .= "&";
				}
				$request_params .= "$key=$value";
			}
		}
		
		return $request_params;
	}

	//掲載サービス一覧データを取得する
	public function getServiceList($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceList";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}


	public static function lists($post_id,$tg_id,$per_page,$PattenNo) {
		$current_page = (get_query_var('page') == 0)?1:get_query_var('page');
		$page_no = $current_page - 1;
		$p_id = SERVICE_PREFIX_P_ID.FUNC_LIST_SERVICE.$tg_id."_".$PattenNo;
		$arrBody = array(
			"TG_ID" => $tg_id,
			"SVC_REQ_P_ID" => $p_id,
			"SVC_ID" => 1 ,
		);
		$url = SERVICE_URL.'Service/GetApplyServieList?page_no='.$page_no.'&per_page='.$per_page;
		$list_service = service_get_common_call_api($post_id, $url, $arrBody, "POST");
		return $list_service;
	}

	public function paginates($list_service, $per_page, $current_page){
		$service_count ='';
		if (!empty($list_service->count)) {
			$service_count = $list_service->count;
		}
		$pagination = service_pagination($service_count,$current_page,$per_page);
		return $pagination;
	}
	//カテゴリ（サービス情報名）のプルダウンデータを取得する
	public function getServiceNameList($post_id, $tg_id){
		$url = SERVICE_URL."Service/GetServiceNameList?TG_ID=$tg_id" ;
		$response = service_get_common_call_api($post_id, $url, array(), 'GET');
		return $response;
	}

	public function getGroupTree($post_id, $tg_id, $lg_type){
		$url = SERVICE_URL."Service/GetGroupTree";
		$body_parameters = array(
			"TG_ID" => $tg_id,
			"LG_TYPE" => $lg_type
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	public function GetDetailDataSettingCategory($post_id, $tg_id, $dis_id){
		$url = SERVICE_URL."Service/GetDetailDataSettingCategory?TG_ID=$tg_id&DIS_ID=$dis_id";
		$response = service_get_common_call_api($post_id, $url, array(), 'GET');
		return $response;
	}

	public function AddDiscussion($post_id, $arrBody){
		$url = SERVICE_URL."Service/AddDiscussion";
		$body_parameters = array(
			'TG_ID' => $arrBody['TG_ID'],
			'P_ID' => (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID:"",
			'DIS_ID' => $arrBody['DIS_ID'],
			'DIS_NM' => $arrBody['DIS_NM'],
			'LG_TYPE' => (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->LG_TYPE : '0',
			'LG_WRITE' => $arrBody['LG_WRITE'],
			'LG_ID' => $arrBody['LG_ID'],
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	public function getYearRange(){
         $years = range(date('Y')+EVENT_YEAR_RANGE, date('Y')-EVENT_YEAR_RANGE + 1);
         return $years;
    }

	//サービス詳細画面表示データを取得する
	public function getServiceDetail($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceDetail?" . $this->makeUriParams($body_parameters);
		$response = service_get_common_call_api($post_id, $url, array(), 'POST');
		return $response;
	}

	//申請中のサービス一覧データを取得する
	public function getApplyServiceList($post_id, $uri_parameters, $body_parameters){
		$url = SERVICE_URL ."Service/GetApplyServiceList?" . $this->makeUriParams($uri_parameters);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービスデータ削除
	public function delService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/DelService";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//選択サービスデータを取得する
	public function getServiceSelect($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceSelect";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//申請者情報を取得する
	public function getServiceApplicant($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceApplicant";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//申請サービス情報データを取得する
	public function getApplyService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetApplyService";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//申請サービス情報掲載項目データを取得する
	public function getApplyServiceItem($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetApplyServiceItem";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//申請サービス情報掲載項目データ（任意項目）を取得する
	public function getApplyServiceItemFree($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetApplyServiceItemFree";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載TEMPデータを削除する
	public function delServiceTemp($post_id, $body_parameters){
		$url = SERVICE_URL."Service/DelServiceTemp";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//変更可能なサービスデータを取得する
	public function getChangeableService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetChangeableService" . $this->makeUriParams($body_parameters);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//会員サービス設定マスタを取得する
	public function getMService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetMService";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//会員サービス設定項目マスタを取得する
	public function getMServiceItem($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetMServiceItem";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載TEMPデータを取得する
	public function getServiceTemp($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceTemp";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"],
			"FIELD_NM" => $body_parameters["FIELD_NM"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}

	//なかま項目初期表示用データの取得
	public function getNakamaInit($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetNakamaInit";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"P_ID" => $body_parameters["P_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}

	//なかま組織データ取得(※未使用だが念のため作成)
	public function getOrganization($tg_id, $body_parameters){
		$post_id = "";
		$url = SERVICE_URL."Service/GetOrganization";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"P_ID" => $body_parameters["P_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}

	//なかま組織個人関連データ取得(※未使用だが念のため作成)
	public function getAffiliation($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetAffiliation";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"P_ID" => $body_parameters["P_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}


	//なかま個人データ取得(※未使用だが念のため作成)
	public function getPersonal($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetPersonal";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"P_ID" => $body_parameters["P_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}

	//サービス掲載データ登録
	public function addService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/AddService";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載項目データ登録
	public function addServiceItem($post_id, $body_parameters){
		$url = SERVICE_URL."Service/AddServiceItem";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"],
			"SVC_INFO_NO" => $body_parameters["SVC_INFO_NO"],
			"U_USER" => $body_parameters['U_USER'],
			"R_USER" => $body_parameters['R_USER'],
			"ServiceItemList" => $body_parameters['ServiceItemList'],
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載項目任意データ登録
	public function addServiceItemFree($post_id, $body_parameters){
		$url = SERVICE_URL."Service/AddServiceItemFree";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載データ更新
	public function upService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/UpService";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載項目データ更新
	public function upServiceItem($post_id, $body_parameters){
		$url = SERVICE_URL."Service/UpServiceItem";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載項目任意データ更新
	public function upServiceItemFree($post_id, $body_parameters){
		$url = SERVICE_URL."Service/UpServiceItemFree";
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載番号採番
	public function getServiceMaxInfoNo($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceMaxInfoNo";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}

	//サービス問合せ先掲載画像保管番号取得
	public function getServiceMaxImgNo($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceMaxImgNo";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'GET');
		return $response;
	}

	//サービス掲載項目不要データ削除
	public function delServiceUnUseItem($post_id, $body_parameters){
		$url = SERVICE_URL."Service/DelServiceUnUseItem";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"],
			"SVC_INFO_NO" => $body_parameters["SVC_INFO_NO"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	//サービス掲載データコピー
	public function copyService($post_id, $body_parameters){
		$url = SERVICE_URL."Service/CopyService";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"],
			"SVC_INFO_NO" => $body_parameters["SVC_INFO_NO"],
			"CHG_SVC_ID" => $body_parameters["CHG_SVC_ID"],
			"CHG_SVC_INFO_NO" => $body_parameters["CHG_SVC_INFO_NO"],
			"DENIAL_REASON" => $body_parameters["DENIAL_REASON"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}
	//サービス掲載項目データコピー
	public function copyServiceItem($post_id, $body_parameters){
		$url = SERVICE_URL."Service/CopyServiceItem";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"],
			"SVC_INFO_NO" => $body_parameters["SVC_INFO_NO"],
			"CHG_SVC_ID" => $body_parameters["CHG_SVC_ID"],
			"CHG_SVC_INFO_NO" => $body_parameters["CHG_SVC_INFO_NO"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}
	//サービス名の取得
	public function getServiceName($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetServiceName";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"],
			"SVC_ID" => $body_parameters["SVC_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}
	//送信先の取得
	public function getToMail($post_id, $body_parameters){
		$url = SERVICE_URL."Service/GetToMail";
		$body_parameters = array(
			"TG_ID" => $body_parameters["TG_ID"]
		);
		$response = service_get_common_call_api($post_id, $url, $body_parameters, 'POST');
		return $response;
	}

	public static function getPatternNoPosttype($post_id){
		$numberCountPost = 0;
		$countPost = (array)wp_count_posts('setting_service', '');
		if($countPost){
			$numberCountPost = $countPost['publish'] + $countPost['draft'] + $countPost['future'] + $countPost['trash'] + $countPost['pending'] + $countPost['private'] + $countPost['inherit'];
		}
		$pattern_no_post_type = get_post_meta($post_id, 'pattern_no_post_type', true);
		if($pattern_no_post_type){
			return $pattern_no_post_type;
		}
		else{
			return $numberCountPost+1;
		}
	}

	public static function logins($post_id, $arrBody) {
		$url = URL_API.'Personal/LogIn';
		$login = service_get_common_call_api($post_id, $url, $arrBody, "POST");
		return $login;
	}

	public function uploadTopImage( $post_id, $file_upload )
	{
		$file_is_image = false;

		if( ! empty( $file_upload['type'] ) && strpos( $file_upload['type'], 'image/' ) !== false ) {
			$file_is_image = true;
		}

		$data = array(
			'file_upload_name' => $file_upload['name'],
			'file_upload_tmp' => base64_encode( file_get_contents( $file_upload['tmp_name'] ) ),
			'file_is_image' => $file_is_image
		);
		$url = SERVICE_URL_UPLOAD . "/Shared/UploadTopImage";
		$response = service_get_common_call_api( $post_id, $url, $data, 'POST' );
		return $response;
	}

	public function rotationTopImage( $post_id, $file_rotation, $type, $file_dir, $is_not_resize )
	{
		$data = array(
			'file_rotation' => $file_rotation,
			'type' => $type,
			'file_dir' => $file_dir,
			'is_not_resize' => $is_not_resize
		);
		$url = SERVICE_URL_UPLOAD . "Shared/RotationTopImage";
		$response = service_get_common_call_api( $post_id, $url, $data, 'POST' );
		return $response;
	}

	function resizeTopImage( $post_id, $file_resize, $width, $height, $file_dir, $is_not_resize )
	{
		$data = array(
			'file_resize' => $file_resize,
			'width' => $width,
			'height' => $height,
            'file_dir' => $file_dir,
            'is_not_resize' => $is_not_resize
		);
		$url = SERVICE_URL_UPLOAD . "Shared/ResizeTopImage";
		$response = service_get_common_call_api( $post_id, $url, $data, 'POST' );
		return $response;
	}

	public function resetTopImage( $post_id, $file_reset, $file_dir )
	{
		$data = array(
			'file_reset' => $file_reset,
            'file_dir' => $file_dir
		);
		$url = SERVICE_URL_UPLOAD . "Shared/ResetTopImage";
		$response = service_get_common_call_api( $post_id, $url, $data, 'POST' );
		return $response;
	}

	public function getUrlImage($reimg, $file_dir, $is_resize = true)
	{
        if ($is_resize == true) {
            $url = str_replace('/public', '', SERVICE_URL_UPLOAD) . "usr_data{$file_dir}/resize/{$reimg}";
        } else {
            $url = str_replace('/public', '', SERVICE_URL_UPLOAD) . "usr_data{$file_dir}/{$reimg}";
        }

        return $url;
	}

	public function htmlToString($html) {
		return htmlspecialchars(str_replace('\"', '"', $html), ENT_QUOTES);
	}

	public function getServiceDisplayFile($img_name, $reimg_name, $file_dir)
	{
		$result = '#';
		if (!empty($img_name)) {
			$img = $this->getUrlImage($img_name, $file_dir,false);
			if (!empty($reimg_name)) {
				$img = $this->getUrlImage($img_name, $file_dir);
			}

			if ($this->checkIsImage($img)) {
				$result = $img;
			} else {
				$info = pathinfo($img);
				$ext = $info['extension'];

				if (in_array($ext, array('doc', 'docx'))) {
					$file = 'word.gif';
				} else if (in_array($ext, array('xls', 'xlsx'))) {
					$file = 'excel.gif';
				} else if (in_array($ext, array('ppt', 'pptx'))) {
					$file = 'ppt.gif';
				} else if (in_array($ext, array('csv'))) {
					$file = 'csv.gif';
				} else if (in_array($ext, array('pdf'))) {
					$file = 'pdf.gif';
				} else if (in_array($ext, array('wmv', 'mp4', 'mp3', 'avi', 'mov', 'asf', 'flv', 'mts'))) {
					$file = 'douga.png';
				} else if (in_array($ext, array('txt'))) {
					$file = 'text.gif';
				} else if (in_array($ext, array('zip'))) {
					$file = 'zip.s.gif';
				} else {
					$file = 'file.gif';
				}

				$result = plugins_url('nakama-service/assets/img/composition/') . $file;
			}
		}

		return $result;
	}
	public function checkIsImage($img_url) {
		$img_exts = array("gif", "jpg", "jpeg", "png", "bmp");
		$url_ext = pathinfo($img_url, PATHINFO_EXTENSION);
		return @is_array(getimagesize($img_url)) || in_array(strtolower($url_ext), $img_exts);
	}

	public function getLogo() {
		$logo = '';
		$dom_str = file_get_contents(home_url('/'));
		$dom_doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_doc->loadHTML($dom_str);
		$xpath = new DOMXPath($dom_doc);
		$header = $xpath->query('//header')->item(0);
		if (!empty($header)) {
			$img = $xpath->query('//img', $header)->item(0);
			if (!empty($img)) {
				$logo = $dom_doc->saveHTML($img);
			}
		}
		return $logo;
	}

	public function getEnv() {
		return strpos(get_site_url(), SERVICE_TEST_SITE_URL) ? SERVICE_APP_ENV_TEST : SERVICE_APP_ENV_PROC;
	}

}
?>
