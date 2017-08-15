<?php

use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();

// Enable debug mode
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/Ui/Twig/views',
));

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->get('/', function () use ($app) {
    return $app->redirect('/about-me');
});

$app->get('/about-me', function (Request $request) use ($app) {
    return $app['twig']->render('about-me.html.twig');
});

$app->get('/blog', function (Request $request) use ($app) {
    return $app['twig']->render('blog.html.twig');
});

$app->get('/curriculum', function (Request $request) use ($app) {
    return $app['twig']->render('curriculum.html.twig');
});

$app->get('/contacto', function (Request $request) use ($app) {
    return $app['twig']->render('contacto.html.twig');
});

$app->post('/wh', function (Request $request) use ($app) {
    if (verifyRequest($request)) {
        echo shell_exec( 'cd '.dirname(__FILE__).'/../../ && /usr/bin/git reset --hard HEAD 2>&1 && /usr/bin/git pull 2>&1');
        return $app->json([], 201);
    }
    return $app->json([], 404);
});

$app->extend('twig', function($twig) {
    $twig->addGlobal('title', 'Tiatere');

    return $twig;
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($code == 404) {
        return new Response($app['twig']->render('404.html.twig'), 404);
    }

    return new Response('We are sorry, but something went terribly wrong.', $code);
});


$app->run();

function verifyRequest(Request $request)
{
    list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
    if (!in_array($algo, hash_algos(), true)) {
        throw new \Exception('Hash algorithm '.$algo.' is not supported.');
    }
    if ($hash !== hash_hmac($algo, $request->getContent(), getenv('WEBHOOK_SECRET'))) {
        throw new \Exception('Hook secret does not match.');
    }
    return true;
}