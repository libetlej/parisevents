<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * Configurer le label et le placeholder d'un champ de formulaire
     *
     * @param $label
     * @param $placeholder
     * @return array
     */
    private function getAttribute($label, $placeholder) {
         return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getAttribute('Titre', "Titre de l'événement"))
            ->add('description', TextareaType::class, $this->getAttribute('Description', "Description de l'événement"))
            ->add('price', MoneyType::class, $this->getAttribute('Tarif', "Tarif de l'événement"))
            ->add('namePlace', TextType::class, $this->getAttribute('Nom du lieu', 'Nom du lieu'))
            ->add('address', TextType::class, $this->getAttribute('Adresse', "Adresse de l'événement"))
            ->add('phone', NumberType::class, $this->getAttribute('Infoline', "Numero de téléphone"))
            ->add('email', EmailType::class, $this->getAttribute('Email', "Adresse email"))
            ->add('website', UrlType::class, $this->getAttribute("Site Web","Site Web"))
            ->add('startDate', DateType::class, $this->getAttribute("Début de l'événement", ""))
            ->add('endDate', DateType::class, $this->getAttribute("Fin de l'événement", ""))
            ->add('image')
            ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
