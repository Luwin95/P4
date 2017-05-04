<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 02/05/2017
 * Time: 16:02
 */

namespace WriterBlog\Tests;


use Silex\WebTestCase;
use WriterBlog\Domain\Chapter;
use WriterBlog\Domain\Comment;
use WriterBlog\Domain\User;
use WriterBlog\Services\CommentManagementService;

class CommentManagementServiceTest extends WebTestCase
{
    private $comments;

    /**
     * Set up of the test
     * Generate a Comment tab that will be sorted
     *
     */
    public function setUp()
    {
        $chapter = new Chapter();
        $user = new User();
        $this->comments = array();

        for($i=0;$i<=5;$i++)
        {
            $comment = new Comment();
            $comment->setId($i+1);
            $comment->setContent('Commentaire'.$i);
            $date = date('Y-m-d H:i:s');
            $comment->setDate($date);
            $comment->setChapter($chapter);
            $comment->setAuthor($user);
            $comment->setReported(false);


            $commentChild = new Comment();
            $commentChild->setId($i+2);
            $commentChild->setContent('Commentaire enfant'.$i);
            $date = date('Y-m-d H:i:s');
            $commentChild->setDate($date);
            $commentChild->setChapter($chapter);
            $commentChild->setAuthor($user);
            $commentChild->setParent($comment);
            $commentChild->setReported(false);

            $childChild = new comment();
            $childChild->setId($i+6);
            $childChild->setContent('Commentaire enfant de l\'enfant'.$i);
            $date = date('Y-m-d H:i:s');
            $childChild->setDate($date);
            $childChild->setChapter($chapter);
            $childChild->setAuthor($user);
            $childChild->setParent($commentChild);
            $childChild->setReported(false);

            $this->comments[$i]= $comment;
            $this->comments[$i+1]= $commentChild;
            $this->comments[$i+6]= $childChild;

            $i++;
        }
    }



    public function testCommentTabGenerator()
    {
        $commentManagementService = new CommentManagementService();
        $commentsSorted= $commentManagementService->commentTabGenerator($this->comments);

        foreach($commentsSorted as $key=>$comment)
        {
            $this->checkAllFields($this->comments[$key],$comment);

            foreach($comment->getChildren() as $commentChildren)
            {
                $this->checkAllFields($this->comments[$key+1],$commentChildren);
                foreach($commentChildren->getChildren() as $childChildren)
                {
                    $this->checkAllFields($this->comments[$key+6],$childChildren);
                }
            }

        }
    }

    /**
     * {@inheritDoc}
     */
    public function createApplication()
    {
        $app = new \Silex\Application();

        require __DIR__.'/../../app/config/dev.php';
        require __DIR__.'/../../app/app.php';
        require __DIR__.'/../../app/routes.php';

        // Generate raw exceptions instead of HTML pages if errors occur
        unset($app['exception_handler']);
        // Simulate sessions for testing
        $app['session.test'] = true;
        // Enable anonymous access to admin zone
        $app['security.access_rules'] = array();

        return $app;
    }

    public function checkAllFields(Comment $commentNotSorted, Comment $commentSorted)
    {
        $this->assertEquals($commentNotSorted->getId(),$commentSorted->getId());
        $this->assertEquals($commentNotSorted->getContent(),$commentSorted->getContent());
        $this->assertEquals($commentNotSorted->getDate(),$commentSorted->getDate());
        $this->assertEquals($commentNotSorted->getChapter(),$commentSorted->getChapter());
        $this->assertEquals($commentNotSorted->getAuthor(),$commentSorted->getAuthor());
        $this->assertEquals($commentNotSorted->getParent(),$commentSorted->getParent());
        $this->assertEquals($commentNotSorted->getReported(),$commentSorted->getReported());
    }
}

