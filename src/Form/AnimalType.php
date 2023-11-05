<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\CategoryAnimal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('note', TextareaType::class, [
                'required' => false,
                'label' => 'Notes',
                'attr' => [
                    'placeholder' => '...',
                ],
            ])
            ->add('birthday', DateType::class, [
                'years' => range(1989, date('Y')),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'label' => 'Date de naissance (approximation)',
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Mâle' => 'M',
                    'Femelle' => 'F',
                    'Non Spécifié' => 'N',
                ],
                'label' => 'Genre biologique',
            ])
            ->add('CategoryAnimal', EntityType::class, [
                'class' => CategoryAnimal::class,
                'choice_label' => function (CategoryAnimal $categoryAnimal) {
                    return $categoryAnimal->getName();
                },
            ])
            ->add('photo', FileType::class, [
                'data_class' => null,
                'mapped' => false,
                'label' => 'Photo',
                'constraints' => [
                    new File([
                        'maxSize' => '7168k', /* ~7mb */
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                ],
            ])
            ->add('race', TextType::class, [
                'required' => false,
                'label' => 'Race',
            ])
            ->add('isDomestic', CheckboxType::class, [
                'label' => "S'agit-il d'un animal domestique non issu d'une production animale ?",
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
