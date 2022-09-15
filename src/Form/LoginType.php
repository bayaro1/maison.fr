<?php

namespace App\Form;

use App\Entity\User;
use App\Security\AppAuthenticator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['view2FA'])
        {
            $builder
                ->add('email', HiddenType::class, [
                    'attr' => [
                        'value' => $options['lastUsername']
                    ]
                ])
                ->add('password', HiddenType::class, [
                    'attr' => [
                        'value' => $options['lastPassword']
                    ]
                ])
                ->add('show_email', TextType::class, [
                    'label' => 'adresse email',
                    'disabled' => true,
                    'attr' => [
                        'value' => $options['lastUsername']
                    ]
                ])
                ->add('show_password', PasswordType::class, [
                    'label' => 'mot de passe',
                    'disabled' => true,
                    'always_empty' => false,
                    'attr' => [
                        'value' => $options['lastPassword']
                    ]
                ])
                ->add('2fa', TextType::class, [
                    'label' => 'code 2FA',
                    'attr' => [
                        'value' => $options['lastCode2FA']
                    ]
                ])
                ;
        }
        else
        {
            $builder
                ->add('email', TextType::class, [
                    'label' => 'adresse email',
                    'attr' => [
                        'value' => $options['lastUsername']
                    ]
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'mot de passe',
                    'always_empty' => false,
                    'attr' => [
                        'value' => $options['lastPassword']
                    ]
                ])
                ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
            'view2FA' => false,
            'lastUsername' => '',
            'lastPassword' => '',
            'lastCode2FA' => ''
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
