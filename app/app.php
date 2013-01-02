<?php

use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\ValidatorServiceProvider;
use Provider\Services\Users\UserProvider;

require_once __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;
//$loader->add("Controllers",__DIR__.'/../src');
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'silex_auth',
        'user' => 'root',
        'password' => 'geeks2012',
    ),
));
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'foo' => array('pattern' => '^/foo'), // Example of an url available as anonymous user
        'login' => array(
            'pattern' => '^/login$',
        ),
        'secured' => array(
            'pattern' => '^.*$',
            'form' => array('login_path' => '/login', 'check_path' => 'login_check'),
            'logout' => array('logout_path' => '/logout'), // url to call for logging out
            'users' => $app->share(function() use ($app) {
                        // Specific class App\User\UserProvider is described below
                        return new UserProvider($app['db']);
                    }),
        ),
    ),
    'security.access_rules' => array(
        // You can rename ROLE_USER as you wish
        array('^/.+$', 'ROLE_USER'),
        array('^/foo$', ''), // This url is available as anonymous user
    )
));
$app->get('/login', function(Request $request) use ($app) {
            return $app['twig']->render('login.twig', array(
                        'error' => $app['security.last_error']($request),
                        'last_username' => $app['session']->get('_security.last_username'),
                    ));
        });
$app->get('/', function(Request $request) use ($app) {
            return $app['twig']->render('index.twig');
        }
);

$app->before(function (Request $request) use ($app) {

            $app->register(new ValidatorServiceProvider());
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
            }
        });
return $app;