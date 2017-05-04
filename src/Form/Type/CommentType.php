<?php

namespace WriterBlog\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class, array(
            'constraints' => array(new Assert\NotBlank(array('message'=>'Le commentaire ne peut être vide.')))
        ));
    }

    public function getName()
    {
        return 'comment';
    }
}

