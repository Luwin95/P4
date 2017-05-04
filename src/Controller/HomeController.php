<?php

namespace WriterBlog\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WriterBlog\Domain\Chapter;
use WriterBlog\Domain\Comment;
use WriterBlog\Domain\User;
use WriterBlog\Form\Type\CommentType;
use WriterBlog\Form\Type\UserSigninType;

use WriterBlog\Services\CommentManagementService;
use WriterBlog\Services\UserManagementService;

class HomeController
{
    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) 
    {
        //Récupération de tous les chapitres publié et transmission à la vue
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
        //Récupération du chapitre sélectionné et de ses commentaires
        $chapter = $app['dao.chapter']->find($id);
        $comments = $app['dao.comment']->findAllByChapter($id);

        //Création d'une instance du service de gestion des commentaires et appel du générateur de tableau
        $commentManagementService = new CommentManagementService();
        $commentManagementService->setApplication($app);
        $commentsSorted = $commentManagementService->commentTabGenerator($comments);

        $commentFormView = null;
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            //Création d'un nouvveau commentaire et d'un formulaire associé à ce commentaire
            $comment = $commentManagementService->registerNewComment($chapter);
            $commentForm = $app['form.factory']->create(CommentType::class, $comment);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid())
            {
                //Si le formulaire est soumis et valide
                if(isset($_POST['parent']))
                {
                    //Si le commentaire est un enfant on lui assigne son parent
                    $comment->setParent($app['dao.comment']->find($_POST['parent']));
                }
                //On sauvegarde le commentaire en base et on affiche un message de succès sur la page (que l'on recharge)
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire a été ajouté avec succès !');
                return $app->redirect($app["url_generator"]->generate("chapter", [
                    "id" => $id
                ]));
            }
            $commentFormView = $commentForm->createView();
        }
        if(isset($_POST['report']))
        {
            //Si un commentaire est signalé on appel la fonction de signalement des commentaires et on affiche un message de succès sur la page
            $commentManagementService->reportComment($_POST['report_id']);
            $app['session']->getFlashBag()->add('warning', 'Le commentaire a bien été signalé');
            return $app->redirect($app["url_generator"]->generate("chapter", [
                "id" => $id
            ]));
        }
        return $app['twig']->render('home/chapter.html.twig', array('chapter' => $chapter, 'comments' =>$commentsSorted, 'commentForm' => $commentFormView));
    }
    
    public function loginAction(Application $app, Request $request)
    {
        return $app['twig']->render('home/login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
    }

    public function signInAction(Application $app, Request $request)
    {
        //Création d'une instance du service de gestion des utilisateurs
        $userManagementService = new UserManagementService();
        $userManagementService->setApplication($app);

        //On créé un nouvel utilisateur et son formulaire associé
        $user = new User();
        $userForm = $app['form.factory']->create(UserSigninType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            //A la soumission et validation du formulaire
            //On créé un nouvel utilisateur (avec le role user) et on redirige vers la page d'accueil de l'application
            $userManagementService->registerNewUser($user,false);
            return $app->redirect($app["url_generator"]->generate("home"));
        }
        return $app['twig']->render('home/signin.html.twig', array(
            'title' => 'Inscription',
            'userForm' => $userForm->createView()));
    }
}
