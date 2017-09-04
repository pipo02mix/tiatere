<?php

namespace Tiatere\Infrastructure;

use Silex\Provider\FormServiceProvider;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Tiatere\Application\ContactCommand;
use Tiatere\Application\ContactRequest;
use Tiatere\Application\GetBlogEntryBySlug;
use Tiatere\Application\GetLastBlogEntries;

require_once __DIR__.'/../../../vendor/autoload.php';

$app = new \Silex\Application();

// Enable debug mode
$app['debug'] = true;

$app['blog_repository'] = new \Tiatere\Infrastructure\Domain\MediumBlogRepository();

$app->register(new \Silex\Provider\SwiftmailerServiceProvider());

$app->register(new \Silex\Provider\ValidatorServiceProvider());

$app->register(new \Silex\Provider\LocaleServiceProvider());

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/Ui/Twig/views',
));

$app->register(new FormServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider(), array(
  'translation.class_path'    => __DIR__.'/../lib/vendor/symfony/src',
  'locale' => 'es'
));

$app['twig'] = $app->extend('twig', function($twig, $app) {
    $twig->addExtension(new \Twig_Extensions_Extension_Text());
    $twig->addFilter(new \Twig_SimpleFilter('striptags', function ($string) {
        return strip_tags($string);
    }));
    return $twig;
});

$app['event_dispatcher'] = function ($app) {
    return new EventDispatcher();
};

$app->before(function (Request $request) use ($app) {
    $app['event_dispatcher']->addListener('contact.requested', function (Event $event) use ($app) {
        $message = (new \Swift_Message())
          ->setSubject('Tiatere Feedback '.$event->fullname())
          ->setFrom(array('noreply@tiatere.es'))
          ->setTo(array('info@tiatere.es'))
          ->setBody($event->query());

        $app['mailer']->send($message);
    });

    $app['request'] = $request;

    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->get('/{_locale}', function () use ($app) {
    return $app->redirect('/'.$app['locale'].'/about-me');
})->value('_locale', 'es');

$app->get('/{_locale}/about-me', function () use ($app) {
    return $app['twig']->render('about-me.html.twig');
});

$app->get('/{_locale}/blog', function () use ($app) {
    $entries = (new GetLastBlogEntries($app['blog_repository']))->execute(3);
    return $app['twig']->render('blog.html.twig', ['entries' => $entries]);
});

$app->get('/{_locale}/blog/{slug}', function ($slug) use ($app) {
    $entry = (new GetBlogEntryBySlug($app['blog_repository']))->execute($slug);
    return $app['twig']->render('blog_entry.html.twig', ['entry' => $entry]);
});

$app->get('/{_locale}/curriculum', function (Request $request) use ($app) {
    return $app['twig']->render('curriculum.html.twig');
});

$app->match('/{_locale}/contact', function (Request $request) use ($app) {
    $data = array(
      'persona' => '',
      'email' => '',
      'consulta' => '',
    );

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
      ->add('persona', TextType::class)
      ->add('email')
      ->add('consulta', TextareaType::class)
      ->add('submit', SubmitType::class)
      ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
        $commandHandler = new ContactRequest(
          $app['validator'],
          $app['event_dispatcher']);

        $commandHandler->execute(
          new ContactCommand(
              $data['persona'],
              $data['email'],
              $data['consulta']
          )
        );

        return $app->redirect('/contact-done');
    }

    return $app['twig']->render('contact.html.twig', array('form' => $form->createView()));
});

$app->get('/{_locale}/contact-done', function (Request $request) use ($app) {

    return $app['twig']->render('contact_done.html.twig');
});

$app->post('/wh', function (Request $request) use ($app) {
    if (verifyRequest($request)) {
        echo shell_exec( 'cd '.dirname(__FILE__).'/../../ && /usr/bin/git reset --hard HEAD 2>&1 && /usr/bin/git pull 2>&1');
        return $app->json([], 201);
    }
    return $app->json([], 404);
});

$app->extend('twig', function($twig) {
    $twig->addGlobal('title', 'Tiatere - Un desarrollador web full-stack con afición por hacer las cosas bien');

    return $twig;
});

$app->extend('translator', function($translator) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/Ui/Resources/locales/en.yaml', 'en');
    $translator->addResource('yaml', __DIR__.'/Ui/Resources/locales/es.yaml', 'es');

    return $translator;
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
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