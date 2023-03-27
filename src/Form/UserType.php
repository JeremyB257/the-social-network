<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('bornAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'years' => range($y = date('Y'), $y - 120),
                'input'  => 'datetime_immutable',
            ])
            ->add('biography', null, [
                'label' => 'Biographie',
            ])
            ->add('avatarFile', FileType::class, [
                'mapped' => false,
                'label' => 'Avatar',
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
