<?php
$phpPath = '/usr/local/php7.1/bin/php';
$cmd = $phpPath.' '.__DIR__.'/../bin/console cours:get-periode';
echo $cmd."\n";
exec($cmd);


