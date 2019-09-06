<?php

namespace App\Form;

use App\Form\ApplicationType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getAttribute("Ancien mot de passe", "votre ancien mot de passe "))
            ->add('newPassword', PasswordType::class, $this->getAttribute("Nouveau mot de passe", "nouveau mot de passe "))
            ->add('confirmPassword', PasswordType::class, $this->getAttribute("Confirmation du nouveau mot de passe", "confirmation du nouveau mot de passe "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
