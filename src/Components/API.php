<?php
namespace Copyleaks;
use Exception;

/*
API class is the builder and helper for http requests towards api.copyleaks.com
*/
class API{
	private $config;
	private $defaultHeaders;
	private $dataForRequest;
	private $headersForRequest;
	private $constants;
	private $currentFile;
	private $ocrLanguage;
	public  $process;
	public  $sandbox;

	public function __construct($dataToBeSent='',$fileType=false){ 
		$this->config = new \ReflectionClass('Copyleaks\Config');
		$this->constants = $this->config->getConstants();
		$this->ocrLanguage = 'English'; //default language 
		$this->sandbox = false;
		$this->dataForRequest = $dataToBeSent;
		
		$this->defaultHeaders = array(
			    'Accept: application/json'
			);

		!$fileType ? $this->defaultHeaders[] = 'Content-type: application/json' : NULL;
		!$fileType ? $this->defaultHeaders[] = sprintf('Content-Length: %d', strlen($this->dataForRequest)) : NULL;
		$this->headersForRequest = array();
	}

	//ADD ADDITIONAL OR CUSTOM HEADERS
	public function manageHeaders($additionalHeaders=array(),$customHeaders=array()){
		$_headers = array();

		$_addionalHeaders = $additionalHeaders; //todo::validation

		if(count($_addionalHeaders) > 0 && is_array($_addionalHeaders))
			$this->headersForRequest = $_headers = array_merge($this->defaultHeaders,$_addionalHeaders);
		else
			$this->headersForRequest = $_headers = $this->defaultHeaders;

		if(count($customHeaders) > 0 && is_array($customHeaders))
			$this->headersForRequest = array_merge($this->headersForRequest,$customHeaders);

		return $this->headersForRequest;
	}
	
	public function setOcrLanguage($lang=''){
		if(!$this->languageSupported($lang))
			throw new Exception("LANGUAGE ".$lang." IS NOT SUPPORTED BY COPYLEAKS OCR");

		$this->ocrLanguage = $lang;
	}

	public function getOCRLanguage(){
		return $this->ocrLanguage;
	}

	//CREATE CONTENT FOR MULTI PART REQUEST
	private function createContentMessage($_file=array(),$_mimeBoundary=""){
		if(count($_file) < 1)
			throw new Exception("createContentMessage FAILED");

		$_eol = "\r\n";
		$_content = "";

		$_content = "--".$_mimeBoundary.$_eol.
					// "Content-Disposition: form-data; name=\"".$this->constants['FORM_FIELD_FILE']."['".$_file['name']."']\"; filename=\"".$_file['name']."\"".$_eol.
					"Content-Disposition: form-data; name=\"".$this->constants['FORM_FIELD_FILE']."\"; filename=\"".$_file['name']."\"".$_eol.
					"Content-Type: ". $this->constants['FILES_EXTENSIONS'][$_file['extension']] .$_eol.
					"Content-Transfer-Encoding: binary".$_eol.$_eol.
					$_file['content'].$_eol;

		
		// signal end of request (note the trailing "--")
		$_content .= "--".$_mimeBoundary."--".$_eol.$_eol;

		return $_content;		
	}

	//Content generator for API requests by type of API method
	public function manageContent($type='',$file='',$loginTokenHeader='',$additionalHeaders=array()){
		$_file = strlen($file)>0? $this->handleFiles($file,$type) : '';
		$_mimeBoundary=md5(time());


		switch ($type) {
			case 'FILE':
			case 'OCR':
				$this->dataForRequest = $this->createContentMessage($this->currentFile,$_mimeBoundary);
				$_headers = array(
								sprintf('Content-Length: %d', strlen($this->dataForRequest)),
								$this->constants['MULTIPART_HEADER'].$_mimeBoundary,
								$loginTokenHeader
								);

				$_requestHeaders = $this->manageHeaders($_headers,$additionalHeaders);
				
				break;
			case 'URL':
				$_headers = array($loginTokenHeader);
				$_requestHeaders = $this->manageHeaders($_headers,$additionalHeaders);
				
				break;
			default:
				throw new Exception("INVALID CONTENT TYPE");
				
		}
	}
	
	public function prepareRequest($method='POST'){
		
		return array(
	                    'http' => array(
	                        'method'  => $method,
	                        'user_agent' => $this->constants['USER_AGENT'],
	                        'header'  => implode("\r\n", $this->headersForRequest),
	                        'content' => $this->dataForRequest,
	                        'ignore_errors' => true
	                    )
	                );
	}

	//parse headers from string to array
	private function parseHeaders( $headers=array() ){
	    $head = array();
	    foreach( $headers as $k=>$v )
	    {
	        $t = explode( ':', $v, 2 );
	        if( isset( $t[1] ) )
	            $head[ trim($t[0]) ] = trim( $t[1] );
	        else
	        {
	            $head[] = $v;
	            if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
	                $head['reponse_code'] = intval($out[1]);
	        }
	    }
	    return $head;
	}

	private function handleFiles($file='',$type=''){
		$_fileSize=0;
		$_fileName='';
		$_fileExtension='';
		$_fileBinary = '';

		if (file_exists($file)) {
			$_fileName = basename($file);
			$_fileSize = filesize($file);
			$_fileExtension = pathinfo($_fileName, PATHINFO_EXTENSION);	
			$_fileBinary= @file_get_contents($file);

		}else
			throw new Exception("FILE NOT EXISTS ");
		

		switch ($type) {
			case 'FILE':
				if(!$this->fileExtensionAllowed($_fileExtension))
					throw new Exception("EXTENSION ".$_fileExtension." IS NOT SUPPORTED FOR FILE SCAN");
				break;
			case 'OCR':
				if(!$this->ocrExtensionAllowed($_fileExtension))
					throw new Exception("EXTENSION ".$_fileExtension." IS NOT SUPPORTED FOR OCR SCAN");
				break;
			default:
				# code...
				break;
		}

		if($_fileSize == 0 || $_fileSize >= $this->constants['MAX_FILE_SIZE_BYTES'])
			throw new Exception("FILE IS TOO LARGE > 25MB ");
		

		if(!$this->extensionAllowed($_fileExtension))
			throw new Exception("FILE EXTENSION NOT SUPPORTED ");
		

		$this->currentFile = array('name' => $_fileName, 'size'=>$_fileSize, 'extension'=>$_fileExtension, 'content'=> $_fileBinary );

		return $this->currentFile;

	}

	public function validateParams($type='',$params=array(),$content=''){
		$_test = '';
		$_content = isJson($content) ? json_decode($content,true) : $content;
		switch ($type) {
			case 'API_LOGIN':
			case 'API_COUNT_CREDITS':
			case 'FILE':
			case 'GET_PROCESS_STATUS':
			case 'CREATE_BY_URL':
				
				if(count($params) == 0){
					throw new Exception("Error: no response headers - wrong implementation");
				}

				$_test = $this->parseHeaders($params);
				$_responseCode = $_test[$this->constants['RESPONSE_CODE']];
				$_foundError = false;

				if(isset($_test[$this->constants['COPYLEAKS_ERROR_HEADER']])){
					$_errCode = $_test[$this->constants['COPYLEAKS_ERROR_HEADER']];
					$_error = new ErrorHandler($_errCode,$_content);

					$_foundError = true;
					
					throw new Exception("Error code: ".$_errCode.", ".$_content['Message']);
					
				}
				
				if($_responseCode !== 200 ){
					$_foundError = true;
					// print_r($_content);die();
					if(isset($_content['Message']))
						throw new Exception("Response code: ".$_responseCode.", ".$_content['Message']);
					else{

						throw new Exception("Response code: ".$_responseCode.", ".$_content);
					}
				}
				
				return array(
								'has_error'=>$_foundError,
								'response'=>$_content,
								'response_code' => $_responseCode
							);
				// break;
			
			default:
				throw new Exception('INCORRECT VALIDATION TYPE');
		}
	}

	//check if file's extension supported by Copyleaks
	private function extensionAllowed($extension=''){
		return in_array($extension, $this->constants['ALLOWED_EXTENSIONS']);
	}

	//check if file's extension supported by File upload
	private function fileExtensionAllowed($extension=''){
		return in_array($extension, array_keys($this->constants['FILES_EXTENSIONS']));
	}

	//check if file's extension supported by OCR
	private function ocrExtensionAllowed($extension=''){
		return in_array($extension, array_keys($this->constants['OCR_EXTENSIONS']));
	}

	//check if languages code supported for OCR
	private function languageSupported($lang=''){
		return in_array($lang, array_keys($this->constants['OCR_LANGUGAES']));
	}

	public function processCreated($test = array()){
		return isset($test,$test['response']['ProcessId']);
	}
}

?>