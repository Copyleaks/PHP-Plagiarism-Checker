<?php
namespace Copyleaks;

/*
CopyleaksProcess class provides set of public function for user to implement :
	
	1. GET process status (include progress 0-100%)
	2. GET process results
	3. DELETE process

*/
class CopyleaksProcess{
	private $config;
	private $authHeader;
	public $typeOfService;

	public function __construct($processId=0, $creationTime='', $authHeader=array(), $type='',$fileName=''){
		$this->config = new \ReflectionClass('Copyleaks\Config');
		$this->constants = $this->config->getConstants();
		$this->processId = $processId;
		$this->creationTime = $creationTime;
		$this->authHeader = $authHeader;
		$this->progress = 0;
		$this->typeOfService = $type;
		$this->fileName = $fileName;
	}

	public function getStatus(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->typeOfService.'/'.$this->processId.'/status';
		$response = $this->getRequests($_url);
		$this->progress = (int)$response['response']['ProgressPercents'];

		return $this->progress;
	}

	public function getResult(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->typeOfService.'/'.$this->processId.'/result';
		$response = $this->getRequests($_url)['response'];
		$results = array();
		foreach ($response as $result) {
			array_push($results, new ResultRecord($result));
		}
		return $results;
	}
	
	public function getRawText(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->constants['DOWNLOADS_PATH'].'/source-text?pid='.$this->processId;
		$response = $this->getRequests($_url)['response'];
		return $response;
	}
	
	private function getRequests($url=''){
		$_api = new API();

		$_requestHeaders = $_api->manageHeaders(array($this->authHeader));
		$_requestPrepare = $_api->prepareRequest('GET');

		$_url = $url;	
		
		try {
		    
		    $_scc = stream_context_create($_requestPrepare);
		    $_response = @file_get_contents($_url, false, $_scc);
		    
		} catch (Exception $e) {
		    throw new Exception('HTTP request failed.');
		}

		$http_response_header = isset($http_response_header) ? $http_response_header : array();

		$_validatedResponse = $_api->validateParams('GET_PROCESS_STATUS',$http_response_header,$_response);
		//print_r($_validatedResponse);
		return $_validatedResponse;
	}

	public function delete(){
		$_api = new API();

		$_requestHeaders = $_api->manageHeaders(array($this->authHeader));
		$_requestPrepare = $_api->prepareRequest('DELETE');

		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->typeOfService.'/'.$this->processId.'/delete';;	

		try {
		    
		    $_scc = stream_context_create($_requestPrepare);
		    $_response = @file_get_contents($_url, false,$_scc );
		    
		} catch (Exception $e) {
		    throw new Exception('HTTP request failed.');
		}

		$http_response_header = isset($http_response_header) ? $http_response_header : array();

		$_validatedResponse = $_api->validateParams('GET_PROCESS_STATUS',$http_response_header,$_response);

		return $_validatedResponse;
	}

}



?>