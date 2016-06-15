<h2>Copyleaks PHP SDK</h2>
<p>
Copyleaks SDK is a simple framework that allows you to scan text for plagiarism and detect content distribution online, using the <a href="https://copyleaks.com">Copyleaks plagiarism checker cloud</a>.
</p>
<p>
Using Copyleaks SDK you can detect plagiarism in:  
<ul>
<li>Online content and webpages</li>
<li>Local and cloud files (<a href="https://api.copyleaks.com/Documentation/TechnicalSpecifications/#non-textual-formats">see supported files</a>)</li>
<li>Free text</li>
<li>OCR (Optical Character Recognition) - scanning pictures with textual content (<a href="https://api.copyleaks.com/Documentation/TechnicalSpecifications/#ocr-formats">see supported files</a>)</li>
</ul>
</p>
<h3>Integration</h3>
<p>Integrate with the Copyleaks SDK in one of two options:</p>
<ul>
<li>Use the Package Manager - <a href="https://packagist.org/packages/copyleaks/php-plagiarism-checker">Packagist</a> (recommended).
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
 <p>You can generate your personal API key. Do so by entering your <a href="https://api.copyleaks.com/Home/Dashboard">dashboard</a>, and under 'Access Keys' you will be able to see and generate your API keys.</p>
 <p>For more information check out our <a href="https://api.copyleaks.com/Guides/HowToUse">API guide</a>.</p>
<h3>Example</h3>
<p><a href="https://github.com/Copyleaks/PHP-Plagiarism-Checker/blob/master/example.php">Sample.php</a> will show you how to scan for plagiarism the URL: 'https://www.copyleaks.com'. All you have to do is to update the following two lines with your email and API key:
</p>
<pre>
$email = 'Your-Email-Address-Here';
$apiKey = 'Your-API-Key-Here';
</pre>

<p>This example shows how to scan a URL using the line:</p>
<pre> $process  = $clCloud->createByURL('https://www.copyleaks.com',$additionalHeaders); </pre>
<p>You can change 'createByURL' with 'createByFile' to scan local files:</p>
<pre> $process = $clCloud->createByFile('./tests/test.txt',$additionalHeaders); </pre>
<p>or with 'createByOCR to scan local images containing text:</p>
<pre>$process  = $clCloud->createByOCR('./tests/c2253306-637a-44c3-8fe0-e0b5d237da32.jpg','English',$additionalHeaders);</pre>
<h3>Read More</h3>
<ul>
<li><a href="https://api.copyleaks.com/Guides/HowToUse">Copyleaks API guide</a></li>
<li><a href="https://api.copyleaks.com/Documentation">Copyleaks API documentation</a></li>
</ul>

