<?php

namespace App\Form;

use App\Entity\Vaccine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VaccineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Exemple: Maladie de Carré, hépatite, leptospirose, rage,  parvovirose...',
                ],
            ])
            ->add('dateNext', DateType::class, [
                    'label' => 'Date du prochain vaccin',
                    'years' => range(date('Y') - 20, date('Y') + 20),
                    'attr' => [
                        'placeholder' => 'Exemple: 01/01/2024',
                    ],
                ]
            )
            ->add('dateCurrent', DateType::class, [
                    'label' => 'Date actuelle du vaccin',
                    'years' => range(date('Y') - 20, date('Y') + 20),
                    'attr' => [
                        'placeholder' => 'Date à laquelle le vaccin a été fait',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vaccine::class,
        ]);
    }
}
