<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
});
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider(), array(
    'validator.validator_service_ids' => array(
        'validator.unique' => 'validator.unique',
    )
));
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return new WriterBlog\DAO\UserDAO($app['db']);
            },
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/writerblog.log',
    'monolog.name' => 'WriterBlog',
    'monolog.level' => $app['monolog.level']
));

// Register services
$app['dao.chapter'] = function ($app) {
    $chapterDAO = new WriterBlog\DAO\ChapterDAO($app['db']);
    return $chapterDAO;
};

$app['dao.user'] = function ($app) {
    $userDAO = new WriterBlog\DAO\UserDAO($app['db']);
    return $userDAO;
};

$app['dao.comment'] = function ($app) {
    $commentDAO = new WriterBlog\DAO\CommentDAO($app['db']);
    $commentDAO->setChapterDAO($app['dao.chapter']);
    $commentDAO->setUserDAO($app['dao.user']);
    //$commentDAO->setCommentDAO($app['dao.comment']);
    return $commentDAO;
};

$app['validator.unique'] = function ($app) {
    $validator =  new WriterBlog\Form\Validator\UniqueValidator();
    $validator->setUserDAO($app['dao.user']);

    return $validator;
};