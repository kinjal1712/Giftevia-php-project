<?php

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['image'])){
    echo "";
    exit();
}

$img = $data['image'];

$img = str_replace("data:image/png;base64,", "", $img);
$img = str_replace(" ", "+", $img);

$data = base64_decode($img);

$folder = "assets/uploads/";

if(!file_exists($folder)){
    mkdir($folder,0777,true);
}

$file = "design_".time().".png";

file_put_contents($folder.$file,$data);

echo $file;

?>