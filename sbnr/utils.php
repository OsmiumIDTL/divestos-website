<?php

function genErrorPage($errorCode) { //generate an error page with response code
	http_response_code($errorCode);
	return str_replace("[PLACEHOLDER_ERROR]", $errorCode, file_get_contents("fragments/error.html"));
}

function generateErrorPageBasic($errorCode) {
	http_response_code($errorCode);
	print("DENIED " . $errorCode);
	//print("<!DOCTYPE html><html lang=\"en\"><head><title>REQUEST DENIED</title></head><body><h1>REQUEST DENIED</h1></body></html>");
}

function getContactForm($submitLabel = 'Send', $messageEnabled = false, $messagePlaceholder = '') {
	include "contact.html";
}

function getBaseUrl($whitelisted, $whitelistedHosts) {
	$accessingDomain = noHTML($_SERVER['SERVER_NAME']);
	$accessingDomain = str_replace("&period;", ".", $accessingDomain);
	$baseURL = "";
	if(isset($_SERVER['HTTPS'])) {
		$baseURL .= "https://";
	} else {
		$baseURL .= "http://";
	}
	if($whitelisted) {
		if(in_array($accessingDomain, $whitelistedHosts)) {
			$baseURL .= $accessingDomain;
			return $baseURL;
		}
	} else {
		$baseURL .= $accessingDomain;
		return $baseURL;
	}
	return "invalid://invalid.invalid";
}

//Validates a string
function checkString($input, $minLength = 0, $maxLength = 256, $numPeriods = 1, $numSlashes = 0, $numPeriodsRel = 0) {
	//support already noHTML()'ed strings
	$input = str_replace("&period;", ".", $input);
	$input = str_replace("&sol;", "/", $input);
	//validate
	$ok = !is_null($input)
		&& strlen($input) >= $minLength
		&& strlen($input) <= $maxLength
		&& substr_count($input, '.') <= $numPeriods
		&& substr_count($input, '..') <= $numPeriodsRel
		&& substr_count($input, '/') <= $numSlashes;
	return $ok;
}

//Strip entities to prevent XSS
//Credit: https://paragonie.com/blog/2015/06/preventing-xss-vulnerabilities-in-php-everything-you-need-know
function noHTML($input, $encoding = 'UTF-8') {
	return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
}

//Credit: https://stackoverflow.com/a/7112596
function contains($haystack, $needle) {
	return strpos($haystack, $needle) !== false;
}

//Credit: http://stackoverflow.com/a/10473026
function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

//Credit: http://stackoverflow.com/a/10473026
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
}

//Credit: http://stackoverflow.com/a/4356295
function generateRandomString($length = 4, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

//Credit: https://stackoverflow.com/a/4478788
function getRandomFile($dir) {
    $files = glob($dir . '/*.*');
    $file = array_rand($files);
    return $files[$file];
}

//Credit: https://stackoverflow.com/a/6225706
function minifyWhitespace($buffer) {
	$search = array(
	'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
	'/[^\S ]+\</s',     // strip whitespaces before tags, except space
	'/(\s)+/s',         // shorten multiple whitespace sequences
	'/<!--(.|\s)*?-->/' // remove HTML comments
	);

	$replace = array(
	'>',
	'<',
	'\\1',
	''
	);

	$buffer = preg_replace($search, $replace, $buffer);

	return $buffer;
}

?>
