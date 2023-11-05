<?php

namespace App\Form;

use App\Entity\Vacation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $curYear = (int) date('Y');

        $builder
            ->add('libVacation', TextType::class, [
                'required' => true,
                'label' => 'Nom des vacances :',
            ])
            ->add('dateStart', DateType::class, [
                'required' => true,
                'label' => 'Date de commencement :',
                'years' => range($curYear, $curYear + 10),
            ])
            ->add('dateEnd', DateType::class, [
                'required' => true,
                'label' => 'Date de fin :',
                'years' => range($curYear, $curYear + 10),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vacation::class,
        ]);
    }
}
