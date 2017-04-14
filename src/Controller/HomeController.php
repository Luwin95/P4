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

    /**
     * Chapter individual page controller.
     *
     * @param Application $app Silex application
     */
    public function chapterAction($id, Application $app)
    {
        $chapter = $app['dao.chapter']->find($id);
        return $app['twig']->render('home/chapter.html.twig', array('chapter' => $chapter));
    }
}
