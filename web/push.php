<?php
// Modif 5
require_once("../../github_key.php");
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s')."<br/>";
$post_data = file_get_contents('php://input');
$signature = 'sha1='hash_hmac('sha1', $post_data, GIT_HUB_KEY);
if($signature == $_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]){
    echo "ok";
}
/*
var_dump($signature);
var_dump($_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]);
var_dump($post_data);
var_dump(json_decode( $post_data))*/
?>