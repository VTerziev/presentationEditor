<?php
    include_once 'config/database.php';
    include_once 'models/files.php';

    $database = new Database();
    $db = $database->connect();

	echo json_encode($_POST);
    $file = new File($db);
    $file->fileName = $_POST['presentation'];
    $file->read_single();

    $slides = $file->split_slides();
    $pos = $_POST['slide-id'];
	$slides = array_merge(
            array_slice($slides, 0, $pos),
            "",
            array_slice($slides, $pos)
        );
	$file->merge_slides($slides);
	$file->update();

    $data = ["ok" => "ok"];
    echo json_encode($data);
?>
