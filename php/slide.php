<?php
	include_once 'config/database.php';
    include_once 'models/files.php';

	$database = new Database();
    $db = $database->connect();

    $file = new File($db);
    $file->fileName = $_POST['presentation'];
    $file->read_single();
    $slides = $file->split_slides();
    // var_dump($slides);
    // echo 'slides[id] encoding = ' . mb_detect_encoding($slides[$id]);
    $data = ["slimCode" => $slides[$_POST['slide-id']-1]];
    echo json_encode($data);
?>
