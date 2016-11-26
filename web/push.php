<?php
// Modif 4
require_once("../../github_key.php");
ob_start();
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s');
var_dump($_POST['payload']);
$result = ob_get_clean();
file_put_contents ('toto.txt', $result);
?>