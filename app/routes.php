<?php

use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', "WriterBlog\Controller\HomeController::indexAction")
    ->bind('home');

// Chapter individual page
$app->get('/chapter/{id}', "WriterBlog\Controller\HomeController::chapterAction")
    ->bind('chapter');
