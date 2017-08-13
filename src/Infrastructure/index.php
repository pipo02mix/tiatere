<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();

// Enable debug mode
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/Ui/Twig/views',
));

$app->get('/', function () use ($app) {
    return $app->redirect('/about-me');
});

$app->get('/about-me', function (Request $request) use ($app) {
    return $app['twig']->render('about-me.html.twig');
});

$app->extend('twig', function($twig) {
    $twig->addGlobal('title', 'Tiatere');

    return $twig;
});

$app->run();