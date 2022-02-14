<?php
    include_once 'config/database.php';
    include_once 'models/files.php';

    $database = new Database();
    $db = $database->connect();

    $file = new File($db);
    $file->fileName = $_POST['presentation'];
    $file->read_single();

	$data = ["presentation" => $file->content];
    echo json_encode($data);

?>
