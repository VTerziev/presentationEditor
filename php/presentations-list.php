<?php
    // header('Access-Control-Allow-Origin: *');
    // header('Content-Type: application/json');

    include_once 'config/database.php';
    include_once 'models/files.php';

    $database = new Database();
    $db = $database->connect();

    $file = new File($db);

    $result = $file->read();
    $num = $result->rowCount(); 

    if($num > 0) {
      $data = array();
      
      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($data, $FileName);
      }
    }
    echo json_encode($data);
?>
