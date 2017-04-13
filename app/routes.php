<?php

use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', "WriterBlog\Controller\HomeController::indexAction")
->bind('home');
