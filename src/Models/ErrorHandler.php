<?php
namespace Copyleaks;

class ErrorHandler{
	private $type;
	private $code;
	private $msg;
	private $errorHeaders;
	
	public function __construct($errCode=-1,$content='',$fileName=''){ 
		$this->config = new \ReflectionClass('Copyleaks\Config');
		$this->constants = $this->config->getConstants();
		// CR : check if this is being used.
		if( $fileName != ''){
			$this->$fileName = $fileName;
		}
		$this->fileName = $fileName;
		if($errCode === -1){
			$this->code = $errCode;
			$this->msg = $this->internalError();
		}else{
			$this->code = $errCode;
			$this->msg  = isset($content['Message']) ? $content['Message'] : $content;
		}
		
	}

	private function internalError(){
		return $this->constants['COPYLEAKS_INTERNAL_ERROR'];
	}

	public function getArray(){
		return array('code' => $this->code,'msg'=>$this->msg);
	}

}

?>