<?php
require_once("../../github_key.php");
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s') . "\n\n";
$post_data = file_get_contents('php://input');
$signature = 'sha1=' . hash_hmac('sha1', $post_data, GIT_HUB_KEY);

if ($signature == $_SERVER['HTTP_X_HUB_SIGNATURE']) {
    $jsonDecode = json_decode($post_data);
    if ($jsonDecode->ref == "refs/heads/dev") {
        echo "Lancement de la commande de mise en production pour la branche développement";
        exec('../sh/dev_mep.sh');
    }
}
?>