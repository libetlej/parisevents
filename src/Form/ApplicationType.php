<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Configurer le label et le placeholder d'un champ de formulaire
     *
     * @param $label
     * @param $placeholder
     * @param array $options
     * @return array
     */
    public function getAttribute($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}
