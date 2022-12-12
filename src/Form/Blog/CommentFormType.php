<?php

namespace App\Form\Blog;

use App\Entity\Blog\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', TextType::class,
            ['attr' => ['maxlength' => 50], 'required' => true, 'label' => 'Имя'])
            ->add('comment', TextType::class,
            ['attr' => ['maxlength' => 50], 'required' => true, 'label' => 'Комментарий'])
            ->add('send', SubmitType::class, ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
