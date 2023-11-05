<?php

namespace App\Form;

use App\Entity\Unavailability;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnavailabilityRepeatedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $weekDays = ['Lundi' => 'Monday', 'Mardi' => 'Tuesday', 'Mercredi' => 'Wednesday', 'Jeudi' => 'Thursday', 'Vendredi' => 'Friday', 'Samedi' => 'Saturday', 'Dimanche' => 'Sunday'];

        $builder
            ->add('lib', TextType::class, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('startDay', ChoiceType::class, [
                'label' => 'Jour de l\'absence',
                'required' => true,
                'mapped' => false,
                'choices' => $weekDays,
            ])
            ->add('startDayTime', TimeType::class, [
                'label' => 'Heure de début',
                'required' => true,
                'mapped' => false,
                'data' => new \DateTime(),
            ])
            ->add('endDay', ChoiceType::class, [
                'label' => 'Jour de la fin de l\'absence',
                'required' => true,
                'mapped' => false,
                'choices' => $weekDays,
            ])
            ->add('endDayTime', TimeType::class, [
                'label' => 'Heure de fin',
                'mapped' => false,
                'required' => true,
                'data' => new \DateTime('23:59'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unavailability::class,
        ]);
    }
}
