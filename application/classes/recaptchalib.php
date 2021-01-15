<?php

function test_recaptcha($response, $remote_ip) {
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array('secret' => RECAPTCHA_SECRET, 'response' => $response, "remoteip" => $remote_ip);

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	$json = json_decode ($result);
	
	#var_dump_pre ($json);
	if ($result === false) {
		return false;
	}

	if (isset ($json->success) && $json->success === true) {
		return true;
	}

	return false;
}

?>
