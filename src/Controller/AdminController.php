<?php

namespace WriterBlog\Controller;

use Silex\Application;
use WriterBlog\Domain\Chapter;
use WriterBlog\Domain\User;
use WriterBlog\Form\Type\UserType;
use WriterBlog\Form\Type\ChapterType;
use Symfony\Component\HttpFoundation\Request;
use WriterBlog\Form\Type\CommentType;
use WriterBlog\Services\UserManagementService;

class AdminController
{
    /**
     * Admin home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) 
    {
        $chapters = $app['dao.chapter']->findAll();
        $comments = $app['dao.comment']->findAll();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin/home.html.twig', array(
            'chapters' => $chapters,
            'comments' => $comments,
            'users' => $users));
    }
    
    /**
     * Admin add chapter page controller.
     *
     * @param Application $app Silex application
     */
    public function addChapterAction(Request $request,Application $app) 
    {
        $chapter = new Chapter();
        $chapterForm = $app['form.factory']->create(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);
        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {
            $app['dao.chapter']->save($chapter);
            $app['session']->getFlashBag()->add('success', 'Ce chapitre a bien été ajouté');
            return $app->redirect($app["url_generator"]->generate("admin"));
        }
        return $app['twig']->render('admin/chapter_form.html.twig', array(
            'title' => 'Ajout d\'un nouveau chapitre',
            'chapterForm' => $chapterForm->createView()));
    }
    
    /**
     * Admin modify chapter page controller.
     *
     * @param Application $app Silex application
     */
    public function modifyChapterAction($id, Request $request,Application $app) 
    {
        $chapter = $app['dao.chapter']->find($id);
        if($chapter->getPublishment()==1)
        {
            $chapter->setPublishment(true);
        }else{
            $chapter->setPublishment(false);
        }
        $chapterForm = $app['form.factory']->create(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);
        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {
            $app['dao.chapter']->save($chapter);
            $app['session']->getFlashBag()->add('success', 'Ce chapitre a bien été modifié');
            return $app->redirect($app["url_generator"]->generate("admin"));
        }
        return $app['twig']->render('admin/chapter_form.html.twig', array(
            'title' => 'Ajout d\'un nouveau chapitre',
            'chapterForm' => $chapterForm->createView()));
    }

    public function deleteChapterAction($id, Request $request, Application $app)
    {
        // Delete all associated comments
        $app['dao.comment']->deleteAllByChapter($id);
        // Delete the chapter
        $app['dao.chapter']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le chapitre a bien été supprimé');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function modifyCommentAction($id, Request $request, Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Ce commentaire a bien été modifié');
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('admin/comment_form.html.twig', array(
            'title' => 'Modification d\'un commentaire',
            'commentForm' => $commentForm->createView()));
    }

    public function deleteCommentAction($id, Application $app)
    {
        $app['dao.comment']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire sélectionné et ses enfants ont bien été supprimés.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function addUserAction( Request $request, Application $app)
    {
        $userManagementService = new UserManagementService();
        $userManagementService->setApplication($app);

        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userManagementService->registerNewUser($user, true);
            return $app->redirect($app["url_generator"]->generate("admin"));
        }
        return $app['twig']->render('admin/user_form.html.twig', array(
            'title' => 'Nouvel utilisateur',
            'userForm' => $userForm->createView()));
    }

    public function modifyUserAction($id, Request $request, Application $app)
    {
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.bcrypt'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été modifié avec succès' );
            return $app->redirect($app["url_generator"]->generate("admin"));
        }
        return $app['twig']->render('admin/user_form.html.twig', array(
            'title' => 'Modification d\'un utilisateur',
            'userForm' => $userForm->createView()));
    }

    public function deleteUserAction($id, Application $app)
    {
        $app['dao.comment']->deleteAllByUser($id);
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur sélectionné a bien été supprimé.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

}
