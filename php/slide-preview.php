<?php
	// $preview = "<html><body><marquee>W A P</marquee></body></html>";
	header("Access-Control-Allow-Origin: *");

	$preview = file_get_contents('http://localhost:1337');
	// $preview = file_get_contents('https://github.com/ozhi');

	// $tuCurl = curl_init(); 
	// curl_setopt($tuCurl, CURLOPT_URL, "http://127.0.0.1"); 
	// curl_setopt($tuCurl, CURLOPT_PORT , 1337); 
	// curl_setopt($tuCurl, CURLOPT_VERBOSE, 0); 
	// curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	// curl_setopt($tuCurl, CURLOPT_CONNECTTIMEOUT, 5);
	// $preview = curl_exec($tuCurl); 
	// curl_close($tuCurl);

    $data = ["preview" => $preview];
    echo json_encode($data);
?>
