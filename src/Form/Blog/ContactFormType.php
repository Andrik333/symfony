<?php

namespace App\Form\Blog;

use App\Entity\Blog\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                ['attr' => ['maxlength' => 50], 'required' => true, 'label' => 'Имя'])
            ->add('email', EmailType::class,
                ['attr' => ['maxlength' => 70], 'required' => true, 'label' => 'Email'])
            ->add('subject', TextType::class,
                ['attr' => ['maxlength' => 100], 'required' => true, 'label' => 'Тема'])
            ->add('body', TextareaType::class,
                ['attr' => ['maxlength' => 500], 'required' => true, 'label' => 'Сообщение'])
            ->add('save', SubmitType::class,
                ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
