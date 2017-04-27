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
        $plainPassword = $user->getPassword();
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        if(!$admin)
        {
            $user->setRole('ROLE_USER');
        }
        // find the default encoder
        $encoder = $this->app['security.encoder.bcrypt'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $this->app['dao.user']->save($user);
        $this->app['session']->getFlashBag()->add('success', 'L\'utilisateur a été créé avec succès' );
    }

}