<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 27/04/2017
 * Time: 15:29
 */

namespace WriterBlog\Services;


use Silex\Application;
use WriterBlog\Domain\User;

class UserManagementService
{
    private $app;

    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    public function registerNewUser(User $user, $admin)
    {
        //Fonction instanciant et paramétrant un nouvel utilisateur
        $plainPassword = $user->getPassword();
        //Génération et assignation du sel
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        //Si l'utilisateur n'est pas créé par l'administrateur
        if(!$admin)
        {
            //On assigne le rôle d'utilisateur
            $user->setRole('ROLE_USER');
        }
        //On récupère l'encodeur bcrypt et on crypte le mot de passe avec le sel
        $encoder = $this->app['security.encoder.bcrypt'];
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        //On sauvegarde l'utilisateur et on créé un message de succès en session
        $this->app['dao.user']->save($user);
        $this->app['session']->getFlashBag()->add('success', 'L\'utilisateur a été créé avec succès' );
    }

}