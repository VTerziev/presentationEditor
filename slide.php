<?php


    $text = "= slide 'Използване на CSS' do
    p Има 3 начина на използване на CSS:
    list:
    като стойност на style атрибута на html елемент (very bad) <xmp><a href=\"//google.com\" style=\"color:red\">Click Here</a></xmp>
    като добавим style tag в HTML-a (обикновено в head-а) (not good) <xmp><style> a { color: red } </style></xmp>
    като го линк-нете като външен файл (good) <xmp><link href=\"style.css\" rel=\"stylesheet\"></xmp>
    ";

    $data = ["slimCode" => $text];
    echo json_encode($data);
?>
