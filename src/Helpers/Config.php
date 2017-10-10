<?php
namespace Copyleaks;

class Config{
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
    const HTTP_CALLBACK = 'copyleaks-http-completion-callback';
	const IN_PROGRESS_RESULT = 'copyleaks-in-progress-new-result';
    const SANDBOX_MODE_HEADER = 'copyleaks-sandbox-mode';
    const PARTIAL_SCAN_HEADER = 'copyleaks-allow-partial-scan';
	const COMPARE_ONLY = 'copyleaks-compare-documents-for-similarity';
	const DOWNLOADS_PATH = 'downloads';
	const MISC_PATH = 'miscellaneous';
    const RESPONSE_CODE = 'reponse_code';
    const MAX_FILE_SIZE_BYTES = 25 * 1024 * 1024; //25MB
    const SERVICE_ENTRY_POINT = 'https://api.copyleaks.com/';
    const SERVICE_VERSION = 'v1';
	const SERVICE_VERSION_V2 = 'v2';
    const USER_AGENT = 'CopyleaksPHPSDK/1.0';
    const DATE_FORMAT = 'dd/MM/yyyy HH:mm:ss';
    //const SERVICE_PAGE = 'publisher';
    const COPYLEAKS_INTERNAL_ERROR = 'Sorry we have some internal issues, please try again shortly.';

    const MULTIPART_BOUNDARY = '--------------------------';
    const MULTIPART_HEADER   = 'Content-Type: multipart/form-data; boundary=';
    const FORM_FIELD_FILE 	 = 'file';
}

?>