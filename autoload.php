<?php
namespace Copyleaks;

// spl_autoload_register(__NAMESPACE__ . '\load');
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

include_once( getcwd().'/Components/CopyleaksCloud.php');
include_once( getcwd().'/Components/LoginToken.php');
include_once( getcwd().'/Components/CopyleaksProcess.php');
include_once( getcwd().'/Components/API.php');

include_once( getcwd().'/Helpers/Config.php');
include_once( getcwd().'/Helpers/ErrorHandler.php');



?>