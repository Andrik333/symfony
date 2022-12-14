<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Entity\Blog\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Основное', ['class' => 'col-md-9'])
                ->add('title', TextType::class,
                    ['attr' => ['maxlength' => 150], 'required' => true, 'label' => 'Заголовок'])
                ->add('autor', TextType::class,
                    ['attr' => ['maxlength' => 100], 'required' => true, 'label' => 'Автор'])
                ->add('blog', TextareaType::class,
                    ['required' => true, 'label' => 'Содержание'])
                ->add('image', TextType::class,
                    ['attr' => ['maxlength' => 40], 'required' => true, 'label' => 'Изображение'])
            ->end()
            ->with('Прочее', ['class' => 'col-md-3'])
                ->add('tags', TextType::class)
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('title', null, ['label' => 'Заголовок'])
            ->add('created', null, ['label' => 'Автор'])
            ->add('tags', null, ['label' => 'Дата создания'])
            ->add('slug', null, ['label' => 'Дата обновления']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('title', null, ['label' => 'Заголовок'])
            ->addIdentifier('autor', null, ['label' => 'Автор'])
            ->addIdentifier('created', null, ['label' => 'Дата создания'])
            ->addIdentifier('updated', null, ['label' => 'Дата обновления']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('title', null, ['label' => 'Заголовок'])
            ->add('autor', null, ['label' => 'Автор'])
            ->add('tags', null, ['label' => 'Теги'])
            ->add('slug', null, ['label' => 'SEO-префикс'])
            ->add('blog', null, ['label' => 'Содержание'])
            ->add('image', null, ['label' => 'Изображение'])
            ->add('created', null, ['label' => 'Дата создания'])
            ->add('updated', null, ['label' => 'Дата обновления']);
    }
}