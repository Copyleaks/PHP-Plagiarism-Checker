<?php
namespace Copyleaks;

class Config{
	const HTTP_SUCCESS = 500;
	const UNDEFINED_COPYLEAKS_HEADER_ERROR_CODE = 9999;
    const REQUEST_TIMEOUT = 30000; //30 seconds
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_MULTIPART = 'multipart/form-data';
    const ACCEPTED_LANGUAGE_HEADER = 'Accept-Language';
    const CONTENT_TYPE_HEADER = 'Content-Type';
    const AUTHORIZATION_HEADER = 'Authorization: Bearer';
    const COPYLEAKS_ERROR_HEADER = 'Copyleaks-Error-Code';
    const COPYLEAKS_HEADER_PREFIX = "copyleaks-";
    const CLIENT_CUSTOM_PREFIX = "copyleaks-client-custom-";
    const EMAIL_CALLBACK = 'copyleaks-email-callback';
    const HTTP_CALLBACK = 'copyleaks-http-callback';
    const SANDBOX_MODE_HEADER = 'copyleaks-sandbox-mode';
    const PARTIAL_SCAN_HEADER = 'copyleaks-allow-partial-scan';
    const RESPONSE_CODE = 'reponse_code';
    const MAX_FILE_SIZE_BYTES = 25 * 1024 * 1024; //25MB
    const SERVICE_ENTRY_POINT = 'https://api.copyleaks.com/';
    const SERVICE_VERSION = 'v1';
    const USER_AGENT = 'CopyleaksPHPSDK/1.0';
    const DATE_FORMAT = 'dd/MM/yyyy HH:mm:ss';
    const SERVICE_PAGE = 'publisher';
    const E_PRODUCT = array('PUBLISHER'=>'publisher','ACADEMY'=>'academic');
    const COPYLEAKS_INTERNAL_ERROR = 'Sorry we have some internal issues, please try again shortly.';

    const MULTIPART_BOUNDARY = '--------------------------';
    const MULTIPART_HEADER   = 'Content-Type: multipart/form-data; boundary=';
    const FORM_FIELD_FILE 	 = 'file';

    const FILES_EXTENSIONS = array(
	    							'pdf' => 'application/pdf', 
	    							'doc' => 'application/msword',
	    							'docx'	  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	    							'txt'	  => 'text/plain',
	    							'rtf'	  => 'application/rtf', //application/x-rtf,text/richtext
	    							'png' => 'image/png',
									'jpg' => 'image/jpeg', //image/pjpeg
									'jpeg' => 'image/jpeg',
									'gif' => 'image/gif',
									'bmp' => 'image/bmp' //image/x-windows-bmp
	    							);

    const ALLOWED_EXTENSIONS = array(
    								'pdf',
    								'doc',
    								'docx',
    								'txt',
    								'rtf',
    								'png',
    								'jpg',
    								'jpeg',
    								'gif',
    								'bmp'
    								);
    const OCR_EXTENSIONS = array(
    							'png' => 'image/png',
								'jpg' => 'image/jpeg', //image/pjpeg
								'jpeg' => 'image/jpeg',
								'gif' => 'image/gif',
								'bmp' => 'image/bmp' //image/x-windows-bmp
    							);

    const OCR_LANGUGAES = array(
						    	'Afrikaans'=> 'Afrikaans',
								'Albanian'=> 'Albanian',
								'Basque'=> 'Basque',
								'Brazilian'=> 'Brazilian',
								'Bulgarian'=> 'Bulgarian',
								'Byelorussian'=> 'Byelorussian',
								'Catalan'=> 'Catalan',
								'Chinese_Simplified'=> 'Chinese Simplified',
								'Chinese_Traditional'=> 'Chinese Traditional',
								'Croatian'=> 'Croatian',
								'Czech'=> 'Czech',
								'Danish'=> 'Danish',
								'Dutch'=> 'Dutch',
								'English'=> 'English',
								'Esperanto'=> 'Esperanto',
								'Estonian'=> 'Estonian',
								'Finnish'=> 'Finnish',
								'French'=> 'French',
								'Galician'=> 'Galician',
								'German'=> 'German',
								'Greek'=> 'Greek',
								'Hungarian'=> 'Hungarian',
								'Icelandic'=> 'Icelandic',
								'Indonesian'=> 'Indonesian',
								'Italian'=> 'Italian',
								'Japanese'=> 'Japanese',
								'Korean'=> 'Korean',
								'Latin'=> 'Latin',
								'Latvian'=> 'Latvian',
								'Lithuanian'=> 'Lithuanian',
								'Macedonian'=> 'Macedonian',
								'Malay'=> 'Malay',
								'Moldavian'=> 'Moldavian',
								'Norwegian'=> 'Norwegian',
								'Polish'=> 'Polish',
								'Portuguese'=> 'Portuguese',
								'Romanian'=> 'Romanian',
								'Russian'=> 'Russian',
								'Serbian'=> 'Serbian',
								'Slovak'=> 'Slovak',
								'Slovenian'=> 'Slovenian',
								'Spanish'=> 'Spanish',
								'Swedish'=> 'Swedish',
								'Tagalog'=> 'Tagalog',
								'Turkish'=> 'Turkish',
								'Ukrainian'=> 'Ukrainian'
						    	);
}

?>