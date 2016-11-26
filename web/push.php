<?php
// Modif 5
require_once("../../github_key.php");
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s');
$payload = json_decode(stripslashes($_POST['payload']));
var_dump($payload);
var_dump($_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]);
var_dump( hash_hmac( 'sha1', $_POST['payload'], GIT_HUB_KEY, false ));
if( 'sha1=' . hash_hmac( 'sha1', $_POST['payload'], GIT_HUB_KEY, false ) === $_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]) { //
    echo "secre";
}
?>