<?php

namespace WriterBlog\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WriterBlog\Domain\Chapter;

class HomeController
{
    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) 
    {
        $chapters = $app['dao.chapter']->findAllPublished();
        return $app['twig']->render('home/index.html.twig', array('chapters' => $chapters));
    }
}
