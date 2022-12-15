<?php

namespace App\Form\Moex;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, ['label' => false, 'required' => true,
                // 'constraints' => [
                //     new File([
                //         'mimeTypes' => ['text/csv', 'application/csv', 'text/x-comma-separated-values', 'text/x-csv', 'text/plain'],
                //         'mimeTypesMessage' => 'Для загрузки доступны только файлы .csv',
                //     ])
                // ]
            ])
            ->add('save', SubmitType::class,
                ['label' => 'Загрузить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
