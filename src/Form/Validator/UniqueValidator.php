<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 02/05/2017
 * Time: 11:03
 */
namespace WriterBlog\Form\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use WriterBlog\DAO\UserDAO;

class UniqueValidator extends ConstraintValidator
{
    private $userDAO;

    public function validate($value, Constraint $constraint)
    {
        $userCheck = $this->userDAO->findOneByUsername($value);
        $id =$this->context->getRoot()->getData()->getId();

        if ($userCheck && $userCheck->getId()!=$id)
        {
            $this->context->addViolation($constraint->notUniqueMessage, array('%string%' => $value));
            return false;
        }else{
            return true;
        }
    }

    public function setUserDAO(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }
}