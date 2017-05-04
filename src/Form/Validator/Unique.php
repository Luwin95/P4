<?php
namespace WriterBlog\Form\Validator;

class Unique extends \Symfony\Component\Validator\Constraint
{
    public $notUniqueMessage = '%string% est déjà utilisé. Veuillez rentrer un autre nom d\'utilisateur.';
    public $entity;
    public $field;

    public function validatedBy()
    {
        return 'validator.unique';
    }
}