<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\AnimalRecord;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalRecordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight')
            ->add('height')
            ->add('otherInfos')
            ->add('healthInfos')
            ->add('Animal', EntityType::class,
                [
                    'class' => Animal::class,
                    'required' => true,
                    'choice_label' => function (Animal $animal) {
                        return $animal->getDisplayName();
                    },
                ])
            ->add('dateRecord')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnimalRecord::class,
        ]);
    }
}
