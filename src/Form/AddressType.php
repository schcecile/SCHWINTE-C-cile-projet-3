<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $radio = [
            'Monsieur' => 'Monsieur',
            'Madame' => 'Madame',
        ];

        $builder
            ->add('title', ChoiceType::class, [
                'choices' => $radio,
                'expanded' => true,
                'label' => 'Civilité',
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
            ])
            ->add('mobile', TextType::class, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Client' => 'Client',
                    'Fournisseur' => 'Fournisseur',
                    'Collaborateur' => 'Collaborateur',
                    'Prestataire' => 'Prestataire',
                ] ,
                'label' => 'Catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
