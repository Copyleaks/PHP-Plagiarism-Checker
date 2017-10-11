<h2>Copyleaks PHP SDK</h2>
<p>
Copyleaks SDK is a simple framework that allows you to scan text for plagiarism and detect content distribution online, using the <a href="https://copyleaks.com">Copyleaks plagiarism checker</a> cloud.
</p>
<p>
Using Copyleaks SDK you can detect plagiarism in:  
<ul>
<li>Online content and webpages</li>
<li>Local and cloud files (<a href="https://api.copyleaks.com/GeneralDocumentation/TechnicalSpecifications#supportedfiletypes">see supported files</a>)</li>
<li>Free text</li>
<li>Images of text(using OCR) - scanning pictures with textual content (<a href="https://api.copyleaks.com/GeneralDocumentation/TechnicalSpecifications#supportedfiletypes">see supported files</a>)</li>
</ul>
</p>
<h3>Installation</h3>
<p>Integrate with the Copyleaks SDK in one of two options:</p>
<ul>
<li><b>Recommended:</b> Use the Package Manager - <a href="https://packagist.org/packages/copyleaks/php-plagiarism-checker">Packagist</a>.
  <br>
  When integrating that way you will automatically be able to update the SDK to its latest version:
<pre>
composer require copyleaks/php-plagiarism-checker @dev
</pre>
Following that, in order to use the SDK, add this to your code:
<pre>
//copyleaks dependencies 
include_once( __DIR__.'/vendor/copyleaks/php-plagiarism-checker/autoload.php');
use Copyleaks\CopyleaksCloud;
use Copyleaks\CopyleaksProcess;
</pre>
</li>
<li>Download the code from this repository and add it to your project.
</ul>
<h3>Register and Get Your API Key</h3>
 <p>To use the Copyleaks API you need to first have a Copyleaks account. The registration to Copyleaks takes a minute and is free of charge. <a href="https://copyleaks.com/Account/Register">Signup</a> and confirm your account to finalize your registration. </p>
 <p>You can generate your personal API key. Do so by entering your dashboard (<a href="https://api.copyleaks.com/businessesapi">Businesses dashboard/</a><a href="https://api.copyleaks.com/academicapi">Academic dashboard/</a><a href="https://api.copyleaks.com/websitesapi">Websites dashboard</a>), and under 'Access Keys' you will be able to see and generate your API keys.</p>
<p>For more information check out our <a href="https://api.copyleaks.com/Guides/HowToUse">API guide</a>.</p>
<h3>Examples</h3>
<p>See <a href="https://github.com/Copyleaks/PHP-Plagiarism-Checker/blob/master/example_asynchronous.php"><code>example_asynchronous.php</code></a> for an example using callbacks and <a href="https://github.com/Copyleaks/PHP-Plagiarism-Checker/blob/master/example_synchronous.php"><code>example_synchronous.php</code></a> for a synced example that update the status programatically.</p>
<h3>Usage</h3>
<p>To scan for plagiarism the URL: 'https://www.copyleaks.com'. All you have to do is to update the following two lines with your email and API key:
</p>
<pre>
$email = 'Your-Email-Address-Here';
$apiKey = 'Your-API-Key-Here';
</pre>
<p>Add this additional header to get a completion callback:</p>
<pre>$additionalHeaders = array($clConst['HTTP_CALLBACK'].': http://your.website.com/callbacks/' </pre>
<p>For testing purposes you can use http://requestb.in</p><BR/>

<p>Create a process using createByUrl:</p>
<pre>$process  = $clCloud->createByURL('https://www.copyleaks.com',$additionalHeaders); </pre>
<p>Available create methods are: <code>createByURL</code>, <code>createByFile</code>, <code>createByFiles</code>, <code>createByOCR</code> and <code>createByText</code>.</p>

<p>If you don't want to use callbacks you can wait for the scan to complete:</p>
<pre>
while ($process->getStatus() != 100){
	sleep(2);              
}
</pre>

<p>And get the results:</p> 
<pre>$results = $process->getResult();
// Print the results
foreach ($results as $result) {
	echo $result;
}
</pre>
<h3>Configuration</h3>
<p>You can set specific headers:</p>
<pre>$additionalHeaders = array(
  $clConst['SANDBOX_MODE_HEADER'], // Sandbox mode - Scan without consuming any credits and get back dummy results
  $clConst['HTTP_CALLBACK'].': http://your.website.com/callbacks/', # For a fast testing of callbacks option we recommend to use http://requestb.in
  $clConst['IN_PROGRESS_RESULT'].': http://your.website.com/callback/results/,
  $clConst['EMAIL_CALLBACK'].': myemail@company.com',
  $clConst['CLIENT_CUSTOM_PREFIX'].'name: some name'
  $clConst['PARTIAL_SCAN_HEADER'],
  $clConst['COMPARE_ONLY'] # Compare files in between - available only on createByFiles
  $clConst['IMPORT_FILE_TO_DATABASE'] # Import your file to our database only
  );</pre>
<p>For more info about the optional headers see <a href="https://api.copyleaks.com/GeneralDocumentation/RequestHeaders">API Request Headers</a>
</p>
<h3>Read More</h3>
<ul>
<li><a href="https://api.copyleaks.com/">API Homepage</a></li>
<li><a href="https://api.copyleaks.com/Guides/HowToUse">Copyleaks API guide</a></li>
<li><a href="https://copyleaks.com/">Copyleaks Homepage</a></li>
</ul>

