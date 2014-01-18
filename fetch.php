<?php
	function dd($vars){
		var_dump($vars);
		die();
	}

	require 'vendor/autoload.php';
	
	Guzzle\Http\StaticClient::mount();
	$line = $_GET['line'];
	$apiUrl = "http://tts-api.com/tts.mp3?q=";
	$response = Guzzle::get($apiUrl . $line. "&return_url=1");
	$mp3url =  $response->getBody();
	$client = new Guzzle\Http\Client();
	$uniqueID = uniqid();
	$mp3 = $client->get($mp3url)
            ->setResponseBody("mp3/".$uniqueID.".mp3")
            ->send();
    echo $uniqueID.".mp3";
?>