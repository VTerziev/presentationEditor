<?php
	include_once 'config/database.php';
    include_once 'models/files.php';

    $database = new Database();
    $db = $database->connect();

    echo json_encode($_POST);

    $file = new File($db);
    $file->fileName = 'css-1';
    $file->read_single();
    echo (sizeof($file->split_slides()));
?>
