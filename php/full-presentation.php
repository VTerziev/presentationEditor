<?php
    include_once 'config/database.php';
    include_once 'models/files.php';

    $database = new Database();
    $db = $database->connect();

    // $file = new File($db);
    // $file->fileName = $_POST['presentation'];
    // $file->read_single();

	// echo json_encode($_POST);

	$data = ["presentation" => "= slide 'Използване на CSS' do
    p Има 3 начина на използване на CSS:
    list:
    като стойност на style атрибута на html елемент (very bad) <xmp><a href=\"//google.com\" style=\"color:red\">Click Here</a></xmp>
    като добавим style tag в HTML-a (обикновено в head-а) (not good) <xmp><style> a { color: red } </style></xmp>
    като го линк-нете като външен файл (good) <xmp><link href=\"style.css\" rel=\"stylesheet\"></xmp>
    "];
    echo json_encode($data);

?>
