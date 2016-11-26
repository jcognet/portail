<?php
// Modif 2
require_once("../../github_key.php");
ob_start();
var_dump($_SERVER);
$result = ob_get_clean();
file_put_contents ('toto.txt', $result);
?>