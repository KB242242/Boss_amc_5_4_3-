<?php

namespace App\Form\system;


use App\Entity\system\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('pseudo')
            ->add('initiales')
            // ->add('password')
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices'  => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true, // affichera des cases à cocher
                'attr' => ['class' => 'form-check'], // utile pour styliser
            ])
            // ->add('ipContrainte')
            // ->add('nbEchecConn')
            ->add('blackList')
            // ->add('dateConn')
            // ->add('context')
            // ->add('retContext')
            // ->add('rem')
            ->add('valid')
            // ->add('created_at', null, [
            //     'widget' => 'single_text',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
