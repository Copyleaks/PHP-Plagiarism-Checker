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
try{
	$response = $clCloud->login($email, $apiKey);	
}catch(Exception $e){
	echo "<Br/>Caught exception: ". $e->getMessage();
	die();
}


//validate login token
if(!isset($clCloud->loginToken) || !$clCloud->loginToken->validate()){ 
	echo "<Br/>FALSE LOGIN CREDS";
	die();
}

$plist=array();
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
								$clConst['PARTIAL_SCAN_HEADER']
								);

	$urlToScan = "https://www.copyleaks.com";
	echo '<Br/>Creating new scan-process (' . $urlToScan . ')...';
	$process  = $clCloud->createByURL($urlToScan,$additionalHeaders);
	// $process = $clCloud->createByFile('./tests/test.txt',$additionalHeaders);
	// $process  = $clCloud->createByOCR('./tests/c2253306-637a-44c3-8fe0-e0b5d237da32.jpg','English',$additionalHeaders);
	
	// print_r($process);
	
	//create process from create file\ocr response
	$process = new CopyleaksProcess($process['response']['ProcessId'],
		$process['response']['CreationTimeUTC'],
		$clCloud->loginToken->authHeader());

	echo "<BR/> Process created! (PID = '" . $process->processId . "')";
	
	//create process by ID
	// $oldProcess = new CopyleaksProcess('YOUR PID (GUID) HERE','30/05/2016 07:23:43',$clCloud->loginToken->authHeader());

	//print_r($process->getStatus()); //get process status
	//print_r($oldProcess->getResult()); //get process results
	// print_r($oldProcess->getResult()); 
	// print_r($createFileProcess); //print createByFile response
	
	//DELETE process example
	// echo '<Br/>delete process';
	//$deleteProcess = $process->delete();
	// print_r($deleteProcess);

	//get processes list
	$plist = $clCloud->getProcessList();
	// print_r($plist);
	


	//get OCR's supported languages
	$ocrSupportedLanguages = $clCloud->getOCRLanguages();
	// print_r($ocrSupportedLanguages);
	
}catch(Exception $e){

	echo "<br/>Caught exception: ". $e->getMessage();
}

//build table from PHP array
function build_table($array){
    // start table
    $html = '<table>';
    // header row
    $html .= '<tr>';
    foreach($array[0] as $key=>$value){
            $html .= '<th>' . $key . '</th>';
        }
    $html .= '</tr>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
        	$value2 = is_array($value2) ? json_encode($value2) : $value2;
            $html .= '<td>' . @$value2 . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

//print process list as HTML table
if(isset($plist,$plist['response']) && count($plist['response'])>0)
	echo build_table($plist['response']);

?>