<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use App\Form\ApplicationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getAttribute('Titre', "Titre de l'événement"))
            ->add('description', TextareaType::class, $this->getAttribute('Description', "Description de l'événement"))
            ->add('price', MoneyType::class, $this->getAttribute('Tarif', "Tarif de l'événement", ['required' => false]))
            ->add('namePlace', TextType::class, $this->getAttribute('Nom du lieu', "Nom du lieu de l'événement"))
            ->add('address', TextType::class, $this->getAttribute('Adresse', "Adresse de l'événement"))
            ->add('phone', NumberType::class, $this->getAttribute('Infoline', "Numero de téléphone", ['required' => false]))
            ->add('email', EmailType::class, $this->getAttribute('Email', "Adresse email", ['required' => false]))
            ->add('website', UrlType::class, $this->getAttribute("Site Web","Site Web", ['required' => false]))
            ->add('startDate', DateType::class, $this->getAttribute("Début de l'événement", "", ["widget" => "single_text"]))
            ->add('endDate', DateType::class, $this->getAttribute("Fin de l'événement", "", ["widget" => "single_text"]))
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'mimeTypesMessage' => "Votre image n'est pas valide."
                    ])
                ],
            ])
            //->add('createdAt')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categorie',
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous n'avez pas séléctionné de catégorie"
                    ])
                ],
                'placeholder' => '',
                'required' => true,
                'expanded' => false,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
