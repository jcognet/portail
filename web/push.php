<?php
// Modif 5
require_once("../../github_key.php");
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s');
var_dump($_POST);
var_dump($_GET);
var_dump($_REQUEST);
var_dump($_SERVER);
$payload = json_decode(stripslashes($_REQUEST['payload']));
var_dump($payload);

?>