<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getAttribute("Prénom", "votre prénom"))
            ->add('lastName', TextType::class, $this->getAttribute("Nom", "votre nom de famille"))
            ->add('email', EmailType::class, $this->getAttribute("Email", "votre email"))
            ->add('password', PasswordType::class, $this->getAttribute("Mot de passe", "mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getAttribute(
                "Confirmation du mot de passe",
                "confirmation du mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
