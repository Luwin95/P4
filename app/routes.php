<?php

use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', "WriterBlog\Controller\HomeController::indexAction")
    ->bind('home');

// Chapter individual page
$app->match('/chapter/{id}', "WriterBlog\Controller\HomeController::chapterAction")
    ->bind('chapter');

// Login form
$app->get('/login', "WriterBlog\Controller\HomeController::loginAction")
    ->bind('login');