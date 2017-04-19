<?php

namespace WriterBlog\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WriterBlog\Domain\Chapter;
use WriterBlog\Domain\Comment;
use WriterBlog\Form\Type\CommentType;

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
    public function chapterAction($id, Application $app, Request $request)
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

        $commentFormView = null;

        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $comment = new Comment();
            $comment->setChapter($chapter);
            $comment->setAuthor($app['user']);
            $date = date('Y-m-d H:i:s');;
            $comment->setDate($date);
            $commentForm = $app['form.factory']->create(CommentType::class, $comment);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid())
            {
                if(isset($_POST['parent']))
                {
                    $comment->setParent($app['dao.comment']->find($_POST['parent']));
                }
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire a été ajouté avec succès !');
                return $app->redirect($app["url_generator"]->generate("chapter", [
                    "id" => $id
                ]));
                
            }
            $commentFormView = $commentForm->createView();
        }
        return $app['twig']->render('home/chapter.html.twig', array('chapter' => $chapter, 'comments' =>$comments, 'commentForm' => $commentFormView));
    }
    
    public function loginAction(Application $app, Request $request)
    {
        return $app['twig']->render('home/login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
    }
}
