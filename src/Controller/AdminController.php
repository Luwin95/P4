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
        //Récupération de tous les chapitres, commentaires et utilisateurs  et transmission à la vue pour affichage dans un tableau
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
        //Création d'un formulaire pour un nouveau chapitre et transmission de ce dernier à la vue
        $chapter = new Chapter();
        $chapterForm = $app['form.factory']->create(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);
        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {

            /*A la soumission du formulaire et si le chapitre est valide,
             on sauvegarde en base le chapitre et on affiche un message de succès sur la page d'accueil de l'admin*/
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
        //On récupère le chapitre a modifié en base
        $chapter = $app['dao.chapter']->find($id);
        //On modifie la valeur de publication en booléen (nécessaire pour le bon affichage de la case à cocher)
        if($chapter->getPublishment()==1)
        {
            $chapter->setPublishment(true);
        }else{
            $chapter->setPublishment(false);
        }
        //On créé un formulaire auquel on fourni les données du chapitre a modifié
        $chapterForm = $app['form.factory']->create(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);
        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {
            /*A la soumission du formulaire et si le chapitre est valide,
             on sauvegarde en base le chapitre et on affiche un message de succès sur la page d'accueil de l'admin*/
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
        // On supprime tous les commentaires liés au chapitre
        $app['dao.comment']->deleteAllByChapter($id);
        // On supprime le chapitre sélectionné
        $app['dao.chapter']->delete($id);
        // On affiche un message de succès sur la page d'accueil de l'admin et on redirige l'utilisateur vers cette page
        $app['session']->getFlashBag()->add('success', 'Le chapitre a bien été supprimé');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function modifyCommentAction($id, Request $request, Application $app)
    {
        //On récupère en base le commentaire à modifié et on créé un formulaire auquel on fourni les données de ce commentaire
        $comment = $app['dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /*A la soumission du formulaire et si le chapitre est valide,
             on sauvegarde en base le commentaire et on affiche un message de succès sur la page d'accueil de l'admin*/
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
        //On supprime le commentaire (ces enfants seront également supprimés
        $app['dao.comment']->delete($id);
        //On affiche un message de succés et on redirige sur la page d'accueil de l'admin
        $app['session']->getFlashBag()->add('success', 'Le commentaire sélectionné et ses enfants ont bien été supprimés.');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function addUserAction( Request $request, Application $app)
    {
        //On appel le service de gestion des utilisateurs et on instancie les paramètres nécessaires à son fonctionnement.
        $userManagementService = new UserManagementService();
        $userManagementService->setApplication($app);

        //On créé un nouvel utilisateur et un formulaire lié au données de ce nouvel utilisateur
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            /*A la soumission du formulaire et si l'utilisateur est valide,
             on appel le service de gestion des utilisateurs qui va sauvegarder en base l'utilisateur
            et on affiche un message de succès sur la page d'accueil de l'admin*/
            $userManagementService->registerNewUser($user, true);
            return $app->redirect($app["url_generator"]->generate("admin"));
        }
        return $app['twig']->render('admin/user_form.html.twig', array(
            'title' => 'Nouvel utilisateur',
            'userForm' => $userForm->createView()));
    }

    public function modifyUserAction($id, Request $request, Application $app)
    {
        // On récupère en base l'utilisateur a modifié et on créé un formulaire associé à ses données
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            //Si le formulaire est soumis et valide
            $plainPassword = $user->getPassword();
            //On crypte le mot de passe grâce au sel et à l'encoder bcrypt
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            //On associe le mot de passe encrypté à l'utilisateur
            $user->setPassword($password);
            // On sauvegarde en base l'utilisateur et on affiche un message de succès sur la page d'accueil de l'admin
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
        //On supprime tous les commentaires associés à l'utilisateur
        $app['dao.comment']->deleteAllByUser($id);
        //On supprime l'utilisateur
        $app['dao.user']->delete($id);
        //On affiche un message de succès sur la page d'accueil de l'admin.
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur sélectionné a bien été supprimé.');
        return $app->redirect($app['url_generator']->generate('admin'));
    }
}
