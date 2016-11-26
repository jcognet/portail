<?php
// Modif 5
require_once("../../github_key.php");
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s');
$payload = json_decode(stripslashes($_POST['payload']));
var_dump($payload);
?>