<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['image']) || empty($data['image'])){
    echo "ERROR_NO_IMAGE";
    exit();
}

$img = $data['image'];

// remove all types
$img = preg_replace('#^data:image/\w+;base64,#i', '', $img);

$img = str_replace(' ', '+', $img);

$data = base64_decode($img);

if(!$data){
    echo "DECODE_FAILED";
    exit();
}

$folder = "assets/uploads/";

if(!file_exists($folder)){
    mkdir($folder,0777,true);
}

$file = "orig_".time().".png";

file_put_contents($folder.$file,$data);

echo "assets/uploads/".$file;