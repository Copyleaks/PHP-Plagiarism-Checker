<?php

$email = '<Your-email-address-here>';
$apiKey = '<Your-API-Key-Here>';

//dependencies and autoload
include_once( getcwd().'/autoload.php');
use Copyleaks\CopyleaksCloud;
use Copyleaks\CopyleaksProcess;


$clCloud = new CopyleaksCloud();
$clConst = $clCloud->getConstants(); //get all constants


//LOGIN
$response = $clCloud->login($email, $apiKey);

if(!isset($clCloud->loginToken) || !$clCloud->loginToken->validate()){ //validate login token
	throw new Exception("FAILED TO LOGIN");
}

$token = $clCloud->loginToken->token; //get login token
$creditBalance = $clCloud->getCreditBalance(); //get credit balance
// print_r($creditBalance);

//create by document file type or OCR
try{
	//All possible additional headers - only for CreateByFile \ CreateByOCR \ CreateByURL
	$additionalHeaders = array(
								$clConst['SANDBOX_MODE_HEADER'], // Comment this line in production (leave sandbox mode)
								// $clConst['HTTP_CALLBACK'].': http://your.website.com/callback/{PID}',
								// $clConst['EMAIL_CALLBACK'].': myemail@company.com',
								// $clConst['CLIENT_CUSTOM_PREFIX'].'Message: customMsg',
								// $clConst['PARTIAL_SCAN_HEADER']
								);

	$process  = $clCloud->createByURL('https://www.copyleaks.com',$additionalHeaders);
	// $process = $clCloud->createByFile('./tests/test.txt',$additionalHeaders);
	// $process  = $clCloud->createByOCR('./tests/c2253306-637a-44c3-8fe0-e0b5d237da32.jpg','English',$additionalHeaders);
	
	print_r($process);
	

	//create process from create file\ocr response
	$process = new CopyleaksProcess($process['response']['ProcessId'],$process['response']['CreationTimeUTC'],$clCloud->loginToken->authHeader());
	
	//create process by ID
	// $oldProcess = new CopyleaksProcess('11950086-8ded-4ace-85c6-a18d7ef1eb8d','30/05/2016 07:23:43',$clCloud->loginToken->authHeader());

	//print_r($process->getStatus()); //get process status
	//print_r($oldProcess->getResult()); //get process results
	// print_r($oldProcess->getResult()); 
	// print_r($createFileProcess); //print createByFile response
	
	//DELETE process example
	//$deleteProcess = $process->delete();
	// print_r($deleteProcess);

	//get processes list
	$plist = $clCloud->getProcessList();
	// print_r($plist);
	
	//get OCR's supported languages
	$ocrSupportedLanguages = $clCloud->getOCRLanguages();
	// print_r($ocrSupportedLanguages);
	
}catch(Exception $e){
	echo "Caught exception: ". $e->getMessage();
}

?>