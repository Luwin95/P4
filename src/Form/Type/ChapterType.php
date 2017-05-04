<?php

namespace WriterBlog\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class ChapterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'constraints' => array(new Assert\NotBlank(array('message'=>'Le titre du chapitre ne peut être vide.')))
        ));
        $builder->add('content', TextareaType::class, array(
            'constraints' => array(new Assert\NotBlank(array('message'=>'Le contenu du chapitre ne peut être vide.')))
        ));
        $builder->add('publishment', CheckboxType::class, array(
            'required' => false,));
    }

    public function getName()
    {
        return 'chapter';
    }
}