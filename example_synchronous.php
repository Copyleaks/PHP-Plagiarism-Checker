<?php
//dependencies and autoload
include_once( getcwd().'/autoload.php');
use Copyleaks\CopyleaksCloud;
use Copyleaks\CopyleaksProcess;
use Copyleaks\Products;

/* CREATE CONFIG INSTANCE */
$config = new \ReflectionClass('Copyleaks\Config');
$clConst = $config->getConstants();

/* 
	CONSTRUCT ACCEPTS 3 PARAMETER (email, api_key, type of product).

	AVAILABLE PRODUCTS: 
	1. Businesses - Products::Businesses - https://api.copyleaks.com/businessesdocumentation
	2. Education - Products::Education - https://api.copyleaks.com/academicdocumentation
	3. Websites - Products::Websites - https://api.copyleaks.com/websitesdocumentation

*/

// Use the email that you used to register to Copyleaks.
// If you don't have an account yet register on https://copyleaks.com/account/register
// Your API-KEY is available at the dashboards on https://api.copyleaks.com/. Choose the dashboard of the product that you would like to use.
$email = '<Your-email-address-here>';
$apiKey = '<Your-API-Key-Here>';

// Login to Copyleaks Cloud
try{
	$clCloud = new CopyleaksCloud($email, $apiKey, Products::Businesses);
}catch(Exception $e){
	echo "<Br/>Failed to connect to Copyleaks Cloud with exception: ". $e->getMessage();
	die();
}

//validate login token
if(!isset($clCloud->loginToken) || !$clCloud->loginToken->validate()){ 
	echo "<Br/><strong>Bad login credentials</strong>";
	die();
}

# Get your credit balance
//$creditBalance = $clCloud->getCreditBalance(); //get credit balance
// print_r($creditBalance);

try{
	// For more information about the optional headers please visit: https://api.copyleaks.com/GeneralDocumentation/RequestHeaders
	$additionalHeaders = array($clConst['SANDBOX_MODE_HEADER'], // Sandbox mode - Scan without consuming any credits and get back dummy results
								//$clConst['HTTP_CALLBACK'].': http://your.website.com/callbacks/', # For a fast testing of callbacks option we recommend to use http://requestb.in
								//$clConst['IN_PROGRESS_RESULT'].': http://your.website.com/callback/results/,
								//$clConst['EMAIL_CALLBACK'].': myemail@company.com',
								//$clConst['CLIENT_CUSTOM_PREFIX'].'name: some name'
								//$clConst['PARTIAL_SCAN_HEADER'],
								//$clConst['COMPARE_ONLY'] # Compare files in between - available only on createByFiles
								//$clConst['IMPORT_FILE_TO_DATABASE'] # Import your file to our database only
								);
	
	
	// Create process using one of the following option.
	$process  = $clCloud->createByURL("https://www.copyleaks.com", $additionalHeaders);
	// $process  = $clCloud->createByText('<ENTER YOUR STRING HERE>');
	//$process = $clCloud->createByFile(filePath, $additionalHeaders);
	//$processes = $clCloud->createByFiles(array(firstFile,
	//										     secondFile),
	//									 $additionalHeaders); // Array with 2 elements - the first([0]) is the successfully created processes
															  //						 the second([1]) is the error happend
	//$process  = $clCloud->createByOCR(imagePath,'English',$additionalHeaders);
	
	echo "<BR/><strong>Process created!</strong> (PID = '" . $process->processId . "')";

	echo '<BR/><BR/><strong>Processing Started</strong>';

	// Wait for the scan to complete
	while ($process->getStatus() != 100)
	{
	    sleep(2);              
	}

	echo '<BR/><BR/><strong>Finished Processing</strong>';
	
	echo "<BR/><BR/><strong>Results:</strong>";
	$results = $process->getResult();
	// Print the results
	foreach ($results as $result) {
		echo $result;
	}
	
	// Get the source text, result text and the comparison report between them.
	//echo "<BR/><BR/><strong>Cached Version:</strong> ".$process->getRawText()."<BR/>";
	//echo "<BR/><strong>Result Raw Text:</strong> ".$clCloud->getResultRawText($results[0])."<BR/>";
	//echo "<BR/><strong>Comparison Report:</strong><BR/>";
	//print_r($clCloud->getResultComparisonReport($results[0]));

	//DELETE process example
	//echo '<Br/>delete process';
	//$deletedProcess = $process->delete();
	//print_r($deletedProcess);

	//get processes list
	//$process_list = $clCloud->getProcessList();
	//print_r($process_list);
	
	//Get supported file types
	//$supportedFileTypes = $clCloud->getSupportedFileTypes();
	//echo "<BR/><BR/><strong>Supported File Types:</strong><BR/>";
	//print_r($supportedFileTypes);
	
	//Get OCR's(Images of text) supported languages
	//$ocrSupportedLanguages = $clCloud->getSupportedOCRLanguages();
	//echo "<BR/><BR/><strong>Supported OCR(Images of text only) Languages:</strong><BR/>";
	//print_r($ocrSupportedLanguages);
	
}catch(Exception $e){

	echo "<br/>Failed with exception: ". $e->getMessage();
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
