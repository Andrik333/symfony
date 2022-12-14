<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Entity\Blog\Comment;
use App\Entity\Blog\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Form\Type\ModelType;

final class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('user', TextType::class,
                ['attr' => ['maxlength' => 50], 'required' => true, 'label' => 'Пользователь'])
            ->add('comment', TextType::class,
                ['attr' => ['maxlength' => 100], 'required' => true, 'label' => 'Комментарий'])
            ->add('blog', ModelType::class, 
                ['class' => Post::class,'property' => 'title', 'label' => 'Пост'])
            ->add('approved', CheckboxType::class, 
                ['label' => 'Согласован']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('user', null, ['label' => 'Пользователь'])
            ->add('comment', null, ['label' => 'Комментарий'])
            ->add('blog', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Post::class,
                    'choice_label' => 'title',
                ],
                'label' => 'Пост'
            ])
            ->add('approved', null, [
                'field_type' => CheckboxType::class,
                'label' => 'Согласован'
            ])
            ->add('created', null, ['label' => 'Дата создания'])
            ->add('updated', null, ['label' => 'Дата обновления']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('user', null, ['label' => 'Пользователь'])
            ->addIdentifier('comment', null, ['label' => 'Комментарий'])
            ->addIdentifier('blog.title', null, ['label' => 'Пост'])
            ->addIdentifier('approved', null, ['label' => 'Согласован'])
            ->addIdentifier('created', null, ['label' => 'Дата создания'])
            ->addIdentifier('updated', null, ['label' => 'Дата обновления']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('user', null, ['label' => 'Пользователь'])
            ->add('comment', null, ['label' => 'Комментарий'])
            ->add('blog.title', null, ['label' => 'Пост'])
            ->add('approved', null, ['label' => 'Согласован'])
            ->add('created', null, ['label' => 'Дата создания'])
            ->add('updated', null, ['label' => 'Дата обновления']);
    }

    public function toString(object $object): string
    {
        return $object instanceof Comment ? $object->getComment() : 'Comment';
    }
}