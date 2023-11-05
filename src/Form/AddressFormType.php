<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Exemple: Chez moi',
                ],
            ])
            ->add('ad', TextType::class, [
                    'label' => 'Adresse',
                    'attr' => [
                        'placeholder' => 'Exemple: 4 Rue de Paris',
                    ],
                ]
            )
            ->add('pc', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'XXXXX',
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5,
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Exemple: Paris',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
