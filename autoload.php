<?php
namespace Copyleaks;

// spl_autoload_register(__NAMESPACE__ . '\load');
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

include_once( __DIR__.'/src/Components/CopyleaksCloud.php');
include_once( __DIR__.'/src/Components/LoginToken.php');
include_once( __DIR__.'/src/Components/CopyleaksProcess.php');
include_once( __DIR__.'/src/Components/API.php');

include_once( __DIR__.'/src/Models/Products.php');
include_once( __DIR__.'/src/Models/ResultRecord.php');
include_once( __DIR__.'/src/Models/ErrorHandler.php');

include_once( __DIR__.'/src/Helpers/Config.php');
?>