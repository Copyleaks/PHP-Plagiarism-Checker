<?php
namespace Copyleaks;

use Copyleaks\LoginToken;
use Copyleaks\CopyleaksProcess;
use Exception;

/*
CopyleaksCloud provides set of public function for user to implement :
	
	1. POST Login
	2. GET credit balance
	3. GET process list
	4. POST create file
	5. POST create file by OCR
	6. POST create by URL
	7. POST create by Text
*/
class CopyleaksCloud{
	public $loginToken;
	public $typeOfService;
	private $config;
	private $errorHandler;
	private $processList;
	private $constants;

	public function __construct($type='publisher'){
		// $this->loginToken = new LoginToken();
		$this->process = new CopyleaksProcess();
		$this->config = new \ReflectionClass('Copyleaks\Config');
		$this->constants = $this->config->getConstants();
		$this->typeOfService = $type;
	}

	public function login($email='',$apikey=''){
		//hardcoded json type
		$_loginData = json_encode(array("Email" => $email, "ApiKey" => $apikey));   
		$_api = new API($_loginData);

		$_requestHeaders = $_api->manageHeaders();
		$_requestPrepare = $_api->prepareRequest();

		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/account/login-api';
		
		try {
		    
		    $_scc = stream_context_create($_requestPrepare);
		    $_response = @file_get_contents($_url, false,$_scc );
		    
		} catch (Exception $e) {
		    throw new Exception('HTTP request failed.');
		}

		$http_response_header = isset($http_response_header) ? $http_response_header : array();

		$_validatedResponse = $_api->validateParams('API_LOGIN',$http_response_header,$_response);

		
		if(isset($_validatedResponse['response']['access_token'])){
			$this->loginToken = new LoginToken($_validatedResponse['response']);
		}

		return $_validatedResponse;
	}

	public function getCreditBalance(){

		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/account/count-credits';		

		return $this->getRequests($_url);
	}

	public function getProcessList(){ 
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/'.$this->typeOfService.'/list';		

		return $this->getRequests($_url);
	}

	private function getRequests($url=''){
		$_api = new API();
		$_requestHeaders = $_api->manageHeaders(array($this->loginToken->authHeader()));
		$_requestPrepare = $_api->prepareRequest('GET');

		$_url = $url;		

		try {
		    
		    $_scc = stream_context_create($_requestPrepare);
		    $_response = @file_get_contents($_url, false,$_scc );
		    
		} catch (Exception $e) {
		    throw new Exception('HTTP request failed.');
		}

		$http_response_header = isset($http_response_header) ? $http_response_header : array();

		$_validatedResponse = $_api->validateParams('API_COUNT_CREDITS',$http_response_header,$_response);

		return $_validatedResponse;
	}

	/* SCAN BY OCR (IMAGE FILE) */
	public function createByOCR($file='',$language='English',$customHeaders=array()){
		$_api = new API('',true);
		$_api->setOcrLanguage($language);

		return $this->createByType($file,$_api,'OCR',$customHeaders);
	}
	
	/* SCAN BY FILE */
	public function createByFile($file='',$customHeaders=array()){
		$_api = new API('',true);

		return $this->createByType($file,$_api,'FILE',$customHeaders);
	}

	/* SCAN BY URL */
	public function createByURL($url='',$customHeaders=array()){
		if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
		    throw new Exception("INVALID URL");
		
		$_content = json_encode(array('Url'=>$url));
		$_api = new API($_content);

		return $this->createByType('',$_api,'URL',$customHeaders);
	}

	/* SCAN BY TEXT(STRING) */
	public function createByText($text='',$customHeaders=array()){
		$_api = new API($text);

		return $this->createByType('',$_api,'TEXT',$customHeaders);
	}
	
	private function createByType($file='',$api=NULL,$type='',$additionalHeaders=array()){
		$_api = isset($api) ? $api : new API('',true);
		
		switch ($type) {
			case 'OCR':
				$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/'.$this->typeOfService.'/create-by-file-ocr?language='.$_api->getOCRLanguage();		
				break;
			
			case 'URL':
				$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
				.'/'.$this->typeOfService.'/create-by-url';
				break;
			case 'FILE':
				$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/'.$this->typeOfService.'/create-by-file';	
				break;
			case 'TEXT':
				$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/'.$this->typeOfService.'/create-by-text';	
				break;
			default:
				throw new Exception("INVALID CREATE BY TYPE");
				
		}
		
		$_api->manageContent($type,$file,$this->loginToken->authHeader(),$additionalHeaders);

		$_requestPrepare = $_api->prepareRequest();

		try {
		    
		    $_scc = stream_context_create($_requestPrepare);
		    $_response = @file_get_contents($_url, false,$_scc );
		    
		} catch (Exception $e) {
		    throw new Exception('HTTP request failed.');
		}

		$http_response_header = isset($http_response_header) ? $http_response_header : array();

		$_validatedResponse = $_api->validateParams('FILE',$http_response_header,$_response);

		return $_validatedResponse;
	}

	public function getOCRLanguages(){
		return $this->constants['OCR_LANGUGAES'];
	}

	public function getConstants(){
		return $this->constants;
	}

}

?>