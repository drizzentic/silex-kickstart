<?php

use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\ValidatorServiceProvider;
use Provider\Services\UserProvider;
require_once __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;
//$loader->add("Controllers",__DIR__.'/../src');
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'silex_auth',
        'user' => 'root',
        'password' => '',
    ),
));
$app->register(new Silex\Provider\SecurityServiceProvider());
$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/',
        'http' => true,
        'users' => $app->share(function () use ($app) {
                    return new UserProvider($app['db']);
                }),
    ),
);

$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN', 'https'),
    array('^.*$', 'ROLE_USER'),
);
$app->before(function (Request $request) use ($app) {

            $app->register(new ValidatorServiceProvider());
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
            }
        });
$app->mount('/', new Provider\Controllers\Starter\StarterController());
return $app;