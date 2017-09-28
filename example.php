<?php

$email = '<Your-email-address-here>';
$apiKey = '<Your-API-Key-Here>';

//dependencies and autoload
include_once( getcwd().'/autoload.php');
use Copyleaks\CopyleaksCloud;
use Copyleaks\CopyleaksProcess;

/* CREATE CONFIG INSTANCE */
$config = new \ReflectionClass('Copyleaks\Config');
$clConst = $config->getConstants();

/* 
	CONSTRUCT ACCEPTS 1 PARAMETER (type of product).

	ACCEPTED TYPES: 
	1. publisher 
	2. academy

	CONFIG HAS CONSTANTS FOR ACCEPTED TYPES:
	1. $clConst['E_PRODUCT']['PUBLISHER']
	2. $clConst['E_PRODUCT']['ACADEMY']

	DEFAULT:
	publisher
*/
$clCloud = new CopyleaksCloud($clConst['E_PRODUCT']['PUBLISHER']);

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
	// $process  = $clCloud->createByText('<ENTER YOUR STRING HERE>');
	// $process = $clCloud->createByFile('./tests/test.txt',$additionalHeaders);
	// $process  = $clCloud->createByOCR('./tests/c2253306-637a-44c3-8fe0-e0b5d237da32.jpg','English',$additionalHeaders);
	
	// print_r($process);
	
	/*
		create process from create file\ocr response
		
		Parameters:
		1. processID
		2. Creation time (UTC)
		3. Login header
		4. type of product (publisher\academy)

	*/
	$process = new CopyleaksProcess($process['response']['ProcessId'],
		$process['response']['CreationTimeUTC'],
		$clCloud->loginToken->authHeader(),
		$clConst['E_PRODUCT']['PUBLISHER']);

	echo "<BR/> Process created! (PID = '" . $process->processId . "')";

	echo 'Scanning...this may take a few minutes';

	// Wait for the scan to complete
	while ($process->getStatus() != 'Finished')
	{
	      sleep(3);              
	}

	// Print the results
	print_r($Process->getResult());

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
