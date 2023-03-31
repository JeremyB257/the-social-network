<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom',
            ])
            ->add('username', null, [
                'label' => 'Pseudo',
            ])
            ->add('email')
            ->add('bornAt', BirthdayType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'input'  => 'datetime_immutable',
            ])
            ->add('biography', null, [
                'label' => 'Biographie',
            ])
            ->add('avatarFile', FileType::class, [
                'mapped' => false,
                'label' => 'Avatar',
                'constraints' => [
                    new File(maxSize: '2048k', mimeTypes: ['image/jpeg', 'image/jpg', 'image/png']),
                ],
            ])
            // ->add('avatar')
            // ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
