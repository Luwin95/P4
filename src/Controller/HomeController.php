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
        $comments = $app['dao.comment']->findAllByChapter($id);
        $commentsSorted = [];
        foreach($comments as $comment)
        {
            $commentsSorted[$comment->getId()] = $comment;
        }
        foreach($comments as $key => $comment)
        {
            if($comment->getParent()!=Null)
            {
                $commentsSorted[$comment->getParent()->getId()]->addChildren($comment);
                unset($comments[$key]);
            }
        }
        return $app['twig']->render('home/chapter.html.twig', array('chapter' => $chapter, 'comments' =>$comments));
    }
}
