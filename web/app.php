<?php
use Symfony\Component\HttpFoundation\Request;


/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

$envProd = true;


if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ||(isset($_GET['debug']) && $_GET['debug']=='proutprout') ) {
    $envProd = false;
}
if($envProd ) {
    $kernel = new AppKernel('prod', false);
}else{
    $kernel = new AppKernel('dev', true);
}
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);



// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
