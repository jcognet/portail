<?php
// Modif 4
require_once("../../github_key.php");
ob_start();
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s');
var_dump($_POST);
var_dump($_GET);
$result = ob_get_clean();
file_put_contents ('toto.txt', $result);
// Protection git hub
$github_ips = array('207.97.227.253', '50.57.128.197', '108.171.174.178', '50.57.231.61', '192.30.252.42');
if (in_array($_SERVER['REMOTE_ADDR'], $github_ips)) {
    echo "ok";
}else{
    echo "Mauvais serveur : ".$_SERVER['REMOTE_ADDR'];
}
?>