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

	public function __construct($processId=0,$creationTime='',$authHeader=array()){
		$this->config = new \ReflectionClass('Copyleaks\Config');
		$this->constants = $this->config->getConstants();
		$this->processId = $processId;
		$this->creationTime = $creationTime;
		$this->authHeader = $authHeader;
	}

	public function getStatus(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->constants['SERVICE_PAGE'].'/'.$this->processId.'/status';

		return $this->getRequests($_url);
	}

	public function getResult(){
		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->constants['SERVICE_PAGE'].'/'.$this->processId.'/result';
		
		return $this->getRequests($_url);
	}

	private function getRequests($url=''){
		$_api = new API();

		$_requestHeaders = $_api->manageHeaders(array($this->authHeader));
		$_requestPrepare = $_api->prepareRequest('GET');

		$_url = $url;	
		
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

	public function delete(){
		$_api = new API();

		$_requestHeaders = $_api->manageHeaders(array($this->authHeader));
		$_requestPrepare = $_api->prepareRequest('DELETE');

		$_url = $this->constants['SERVICE_ENTRY_POINT'].$this->constants['SERVICE_VERSION'].'/'.$this->constants['SERVICE_PAGE'].'/'.$this->processId.'/delete';;	

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