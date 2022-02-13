<?php
    $data = [
        "the file is here?",
        $_POST,
        $_FILES,
        "---"
    ];

    echo json_encode(print_r($_FILES));

    $filename = $_FILES['file']['tmp_name'];
	$myfile = fopen($filename, "r") or die("Unable to open file!");
	$content = fread($myfile,filesize($filename));

	include_once 'config/database.php';
    include_once 'models/files.php';

    $database = new Database();
    $db = $database->connect();

    $file = new File($db);
    $file->fileName = $_FILES['file']['name'];
    $file->slides = $content;
    $result = $file->create();

	fclose($myfile);
?>
