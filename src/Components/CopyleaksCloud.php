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

	public function __construct($email, $apikey, $product,$token=null){
		$this->config = new \ReflectionClass('Copyleaks\Config');
		$this->constants = $this->config->getConstants();
		$this->typeOfService = $product;
		if ( $token == null)
			$this->login($email, $apikey);
		else 
			$this->loginToken = $token;
	}

	public function login($email, $apikey){
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

	/* SCAN IMAGE USING OCR */
	public function createByOCR($file='',$language='English',$customHeaders=array()){
		$_api = new API('',true);
		$_api->setOcrLanguage($language);
        $response = $this->createByType(array($file), $_api, 'OCR', $customHeaders);
		$process = new CopyleaksProcess($response['response']['ProcessId'],
										$response['response']['CreationTimeUTC'],
										$this->loginToken->authHeader(),
										$this->typeOfService);
		return $process;
	}

	/* SCAN A FILE */
	public function createByFile($file='',$customHeaders=array()){
		$_api = new API('',true);
		$response = $this->createByType(array($file), $_api, 'FILE', $customHeaders);
		$process = new CopyleaksProcess($response['response']['ProcessId'],
										$response['response']['CreationTimeUTC'],
										$this->loginToken->authHeader(),
										$this->typeOfService);
		return $process;
	}

	/* Create Multiple Processes By Multiple Files */
	public function createByFiles($files_array = array(), $customHeaders = array()){
		$_api = new API('',true);
		$response = $this->createByType($files_array, $_api, 'FILES', $customHeaders);
		$response_success = $response['response']['Success'];
		$success_processes = array();
		foreach ($response_success as $process_response){
			array_push($success_processes, new CopyleaksProcess($process_response['ProcessId'],
																$process_response['CreationTimeUTC'],
																$this->loginToken->authHeader(),
																$this->typeOfService,
																$process_response['Filename']));
		}
		$response_errors = $response['response']['Errors'];
		$errors = array();
		foreach ($response_errors as $process_response){
			array_push($errors, new Errorhandler((int)$process_response['ErrorCode'],
													  $process_response['ErrorMessage'],
													  $process_response['Filename']));
		}
		return array($success_processes, $errors);
	}

	/* SCAN A URL */
	public function createByURL($url='', $customHeaders=array()){
		if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
		    throw new Exception("INVALID URL");

		$_content = json_encode(array('Url'=>$url));
		$_api = new API($_content);
		$response = $this->createByType(array(), $_api, 'URL', $customHeaders);
		$process = new CopyleaksProcess($response['response']['ProcessId'],
										$response['response']['CreationTimeUTC'],
										$this->loginToken->authHeader(),
										$this->typeOfService);
		return $process;
	}

	/* SCAN RAW TEXT(STRING) */
	public function createByText($text = '',$customHeaders = array()){
		$_api = new API($text);

		$response = $this->createByType(array(), $_api, 'TEXT', $customHeaders);
        $process = new CopyleaksProcess($response['response']['ProcessId'],
                                $response['response']['CreationTimeUTC'],
                                $this->loginToken->authHeader(),
                                $this->typeOfService);
		return $process;
	}

	private function createByType($files = array(), $api = NULL, $type = '', $additionalHeaders = array()){
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
			case 'FILES':
				$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION_V2'] // Currently supported only in V2
					.'/'.$this->typeOfService.'/create-by-file';
				break;
			case 'TEXT':
				$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/'.$this->typeOfService.'/create-by-text';
				break;
			default:
				throw new Exception("INVALID CREATE BY TYPE");

		}

		$_api->manageContent($type, $files, $this->loginToken->authHeader(), $additionalHeaders);

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

	# Get result raw text
	public function getResultRawText($result){
		$response = $this->getRequests($result->CachedVersion)['response'];
		return $response;
	}

	# Get the comparison report by indexes of a result against yout source
	public function getResultComparisonReport($result){
		$response = $this->getRequests($result->ComparisonReport)['response'];
		return $response;
	}

	# Get a list of supported file types - https://api.copyleaks.com/GeneralDocumentation/SupportedFileTypes
	public function getSupportedFileTypes(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->constants['MISC_PATH'].'/supported-file-types';
		$response = $this->getRequests($_url)['response'];
		return $response;
	}

	# Get a list of supported ocr langauges - https://api.copyleaks.com/GeneralDocumentation/OcrLanguagesList
	public function getSupportedOCRLanguages(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->constants['MISC_PATH'].'/ocr-languages-list';
		$response = $this->getRequests($_url)['response'];
		return $response;
	}

	public function getConstants(){
		return $this->constants;
	}

	public function getSharingKey($pid){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION']
					.'/'.$this->typeOfService ."/" . $pid . "/readonly/create-if-not-exists" ;

		return $this->getRequests($_url);
	}
}

?>