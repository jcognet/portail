<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/../app/autoload.php';


$envProd = true;

if (
    (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) ||   # cas du localhost
    isset($_GET['debug']) && $_GET['debug'] == 'proutprout' ||      # cas du debug forcé
    !is_bool(strpos($_SERVER['REQUEST_URI'], '/_wdt/'))     ||      # cas du profiler en bas
    !is_bool(strpos($_SERVER['REQUEST_URI'], '/_profiler/')) ||     # Cas du profiler
    !is_bool(strpos($_SERVER['HTTP_HOST'], 'changesous.dev/'))       # Cas du serveur de dév'

) {
    $envProd = false;
}

if ($envProd) {
    include_once __DIR__ . '/../var/bootstrap.php.cache';
    $kernel = new AppKernel('prod', false);
} else {
    Debug::enable();
    $kernel = new AppKernel('dev', true);
}
$kernel->loadClassCache();


$request  = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
