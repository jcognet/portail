<?php
require_once("../../github_key.php");
ob_start();
var_dump($_POST);
$result = ob_get_clean();
file_put_contents ($result, 'toto.txt');
?>