<?php
	include_once 'config/database.php';
    include_once 'models/files.php';

    $id = 1;

	$database = new Database();
    $db = $database->connect();

    // echo json_encode($_POST);

    $file = new File($db);
    $file->fileName = 'css-1';
    $file->read_single();
    $slides = $file->split_slides();
    // var_dump($slides);
    // echo 'slides[id] encoding = ' . mb_detect_encoding($slides[$id]);
    $data = ["slimCode" => $slides[$id]];
    echo json_encode($data);
?>
