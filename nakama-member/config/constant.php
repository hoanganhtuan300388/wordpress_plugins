<?php
	define("URL_API", "https://dev.nakamacloud.com/nakama2API/");
	define("URL_FILE_REGIST", ABSPATH."wp-content/uploads/nakama-member");
	define("NAK_LOGIN_MEMBER", 1);
	define("NAK_DENKOU_ID", "jeca2");
	define("MUST_START_TAG", "<span style='color: #FF0000'>※</span>");
	define("MUST_END_TAG", "");
	define("ROOT_IMG", home_url()."/usr_data");
	define("MEMBER_ROOT_FILE", "\\\SQL2008R2\\usr_data_nakama2");
	Const SYNC_FLG = True;


	Const NAK_NAKAMA_PATH = "https://wn.cococica.com/nakama";
	Const SYNC_DOMAIN     = "https://wn.cococica.com";
	Const SYNC_URL        = "/omm";
	Const SYNC_PATH       = "\\Nakamaweb2\D\http\oomori";
	Const NAK_ORACLE_TEXT = "\ora.ini";
	Const NAK_ERROR_URL   = "/nakama/message.asp";
	Const NAK_TEMP_URL = "/temp/";
	Const NAK_DEBUG_FLAG = false;
	Const NAK_BODY_PARAM = "leftmargin='5' topmargin='2'";
	Const NAK_DEBUG_TRACE = true;
	Const NAK_MEMBER_TYPE_NOMEMBER = "0";
	Const NAK_MEMBER_TYPE_CURRENT = "1";
	Const NAK_MEMBER_TYPE_SUSPEND = "2";
	Const NAK_MEMBER_TYPE_DELETED = "3";
	Const NAK_LOGIN_STAFF     = 2;
	Const NAK_LOGIN_GROUP     = 3;
	Const NAK_LOGIN_ROOT      = 4;
	Const NAK_LOGIN_G_ROOT    = 5;
	Const NAK_LOGIN_ANONYMOUS = 10;
	Const NAK_ORATYPE_VARCHAR2 = 1;
	Const NAK_ORATYPE_NUMBER = 2;
	Const NAK_ORATYPE_DATE = 12;
	Const NAK_ORATYPE_TIMESTAMP = 187;
	Const NAK_ORATYPE_DATETIME = 9001;
	Const NAK_CLAIM_CLASS_AUTO = "0";
	Const NAK_CLAIM_CLASS_BANK = "1";
	Const NAK_CLAIM_CLASS_POSTAL = "2";
	Const NAK_CLAIM_CLASS_CASH = "3";
	Const NAK_ACCOUNT_TYPE_SAVINGS = "1";
	Const NAK_ACCOUNT_TYPE_CHECKING = "2";
	Const NAK_UPLOAD_FILESIZE = 96000;
	Const NAK_UPLOAD_EXT = "gif,jpg,jepg,html,htm,GIF,JPG,JEPG,HTML,HTM";
	Const NAK_GROUP_IMAGE_PATH = "group/img";
	Const NAK_GROUP_LOGO_PATH = "group/logo";
	Const NAK_PERSONAL_IMAGE_PATH = "personal";
	Const NAK_MEMBER_CARD_PATH = "membercard";
	Const NAK_DISCUSSION_CATEGORY_IMAGE_PATH = "CategoryImg";
	Const TEMP_PATH = "temp";
	Const NAK_TID_MEMBER_LIST = "mm.list";
	Const NAK_TID_FEE_CLAIM = "mm.feeClaim";
	Const NAK_TID_MEMBER_DATA_REG = "mdreg";

	Const DETAIL_MODE_USER = "0";
	Const DETAIL_MODE_MEMBER = "1";
	Const DETAIL_MODE_STAFF = "2";
	Const DETAIL_MODE_GROUP = "3";
	Const DETAIL_MODE_GROUP_MEMBER = "4";
	Const DETAIL_MODE_LOWER_GROUP = "5";
	Const DETAIL_MODE_OFFICAIL = "6";
	Const DETAIL_MODE_FEERECEIPT = "7";
	Const DETAIL_MODE_FEECLAIM = "8";

	Const DETAIL_MODE_READONLY = "100";

	Const DEST_CURRENT = "0";
	Const DEST_REGISTERED = "1";
	Const DEST_NEW = "2";

	Const DEST_AFF_GROUP = "4";
	Const DEST_PRIVATE = "5";
	Const DETAIL_TYPE_NOMAL     = "0";
	Const DETAIL_TYPE_URL       = "1";
	Const DETAIL_TYPE_MAIL      = "2";
	Const DETAIL_TYPE_TEXTAREA  = "3";

	Const TOPGID_MOSTAISAKU   = "mostaisaku";
	Const KEYWORD_INI = "keyword.ini";
	Const TOPGID_JPCSED = "";
	Const ROOT_PATH = "";

	Const MAIL_SUBJECT_REGIST_DEFAULT = "『[@団体名]』会員登録申し込み完了のお知らせ";
	Const MAIL_BODY_REGIST_DEFAULT = "[@組織名]
[@氏名] 様
下記の内容で会員登録のお申込を致しました。
登録の手続きには、お時間をいただく場合がございます。
何卒ご了承ください。
お申込ありがとうございました。
■お申込された内容
[@会員登録内容]";
	Const MAIL_SUBJECT_EDIT_DEFAULT = "『[@団体名]』会員登録申し込み完了のお知らせ";
	Const MAIL_BODY_EDIT_DEFAULT = "[@組織名]
[@氏名] 様
下記の内容で会員登録の内容を変更を致しました。
■変更された内容
[@会員登録内容]";

?>
