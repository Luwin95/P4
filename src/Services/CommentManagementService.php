<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 27/04/2017
 * Time: 14:40
 */

namespace WriterBlog\Services;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WriterBlog\Domain\Chapter;
use WriterBlog\Domain\Comment;
use WriterBlog\Form\Type\CommentType;

class CommentManagementService
{
    private $app;

    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    public function commentTabGenerator(array $comments)
    {
        //Fonction triant d'abord les commentaires par id puis hiérarchiquement
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
        return $comments;
    }

    public function registerNewComment(Chapter $chapter)
    {
        //Fonction instanciant et paramétrant un nouveau commentaire à sauvegarder
        $comment = new Comment();
        $comment->setChapter($chapter);
        $comment->setAuthor($this->app['user']);
        $comment->setReported(false);
        $date = date('Y-m-d H:i:s');;
        $comment->setDate($date);
        return $comment;
    }

    public function reportComment($id)
    {
        //Fonction gérant la signalisation de commentaire (signalement si le commentaire n'est pas déjà signalé)
        $commentReported = $this->app['dao.comment']->find($id);
        if(!$commentReported->getReported()) {
            $commentReported->setReported(true);
            $this->app['dao.comment']->save($commentReported);
        }
    }

}