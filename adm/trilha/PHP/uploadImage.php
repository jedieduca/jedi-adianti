<?php

	header("Access-Control-Allow-Origin: *");

    $image = file_get_contents($_GET['imagem']);
    //list($width, $height, $type, $attr) = getimagesize($image);

    $type = end(explode(".", $_GET['imagem']));
    if ($type=='jpg')
      header('Content-type: image/jpeg;');
    else if ($type=='png')
      header('Content-type: image/png;');
    header("Content-Length: " . strlen($image));
    
    echo $image;
?>