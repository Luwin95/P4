<?php

namespace WriterBlog\Domain;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use WriterBlog\Form\Validator\Unique;

class User implements UserInterface
{
    /**
     * User id.
     *
     * @var integer
     */
    private $id;

    /**
     * User name.
     *
     * @var string
     */
    private $username;

    /**
     * User password.
     *
     * @var string
     */
    private $password;

    /**
     * Salt that was originally used to encode the password.
     *
     * @var string
     */
    private $salt;

    /**
     * Role.
     * Values : ROLE_USER or ROLE_ADMIN.
     *
     * @var string
     */
    private $role;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // Nothing to do here
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Assert\NotBlank(array('message' => 'Ce champ ne peut être vide.')));
        $metadata->addPropertyConstraint('username',  new Assert\Length(array('min' => 4, 'minMessage'=>'Le nom d\'utilisateur doit contenir au minimum 4 caractères')));
        $metadata->addPropertyConstraint('password', new Assert\NotBlank(array('message' => 'Ce champ ne peut être vide.')));
        $metadata->addPropertyConstraint('password',  new Assert\Length(array('min' => 6, 'minMessage'=>'Le mot de passe doit contenir au minimum 6 caractères')));
        $metadata->addPropertyConstraint('username', new Unique(array('field' => 'username', 'entity' => $metadata->getReflectionClass()->getName())));
        /*$metadata->addConstraint(new UniqueEntity(array(
            'fields'  => 'username',
            'message' => 'Cet utilisateur existe déjà. Veuillez entrer un autre nom d\'utilisateur',
        )));*/
    }
}