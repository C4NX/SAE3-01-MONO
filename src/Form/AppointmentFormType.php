<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Animal;
use App\Entity\TypeAppointment;
use App\Entity\Veto;
use App\Repository\AddressRepository;
use App\Repository\AnimalRepository;
use App\Repository\TypeAppointmentRepository;
use App\Repository\VetoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $curYear = (int) date('Y');
        $nextYearsRange = range($curYear, $curYear + 10);

        $builder
            ->add('date', DateTimeType::class, [
                'required' => true,
                'label' => 'Date et Heure',
                'years' => $nextYearsRange,
            ])
            ->add('isUrgent', CheckboxType::class, [
                'label' => 'Le rendez-vous est t\'il urgent (Attention, ne le faites que si le rendez-vous ne nécessite une arrivée immédiate d\'un vétérinaire.)',
                'required' => false,
            ])
            ->add('vet', EntityType::class, [
                'required' => true,
                'label' => 'Vétérinaire',
                'class' => Veto::class,
                'choice_label' => function (Veto $veto) {
                    return $veto->getDisplayName();
                },
                'query_builder' => function (VetoRepository $vetoRepository) {
                    return $vetoRepository->createQueryBuilder('v')
                        ->where('v.agenda IS NOT NULL')
                        ->orderBy('v.lastName', 'ASC');
                },
            ])
            ->add('animal', EntityType::class, [
                'required' => true,
                'label' => 'Votre Animal',
                'class' => Animal::class,
                'choice_label' => function (Animal $animal) {
                    return $animal->getDisplayName();
                },
                'query_builder' => function (AnimalRepository $animalRepository) use ($options) {
                    return $animalRepository->createQueryBuilder('a')
                        ->where('a.ClientAnimal = :client')
                        ->setParameter('client', $options['client']);
                },
            ])
            ->add('type', EntityType::class, [
                'required' => true,
                'label' => 'Type + Durée',
                'class' => TypeAppointment::class,
                'choice_label' => function (TypeAppointment $typeAppointment) {
                    $durationInMin = $typeAppointment->getDuration();
                    $timeStr = sprintf('%02dh%02d', floor($durationInMin / 60), $durationInMin % 60);

                    return "{$typeAppointment->getLibTypeApp()} ($timeStr)";
                },
                'query_builder' => function (TypeAppointmentRepository $typeAppointmentRepository) {
                    return $typeAppointmentRepository->createQueryBuilder('ta')
                        ->orderBy('ta.duration', 'ASC');
                },
            ])
            ->add('address', EntityType::class, [
                'required' => true,
                'label' => 'Adresse',
                'class' => Address::class,
                'choice_label' => function (Address $address) {
                    return $address->getDisplayName();
                },
                'query_builder' => function (AddressRepository $addressRepository) use ($options) {
                    return $addressRepository->createQueryBuilder('a')
                        ->where('a.client = :client')
                        ->setParameter('client', $options['client']);
                },
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Vos Notes',
                'attr' => [
                    'placeholder' => 'Symptome ou autres informations...',
                ],
                'required' => false,
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
        $resolver->setRequired('client');
    }
}
