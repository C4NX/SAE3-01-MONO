<?php

namespace App\Form;

use App\Entity\Thread;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lib', TextType::class, [
                'label' => 'Question demandée (*)',
                'required' => true,
                'attr' => [
                    'id' => 'question',
                    'placeholder' => 'Écrivez une question...',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Précisons à ajouter :',
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'id' => 'precisions',
                    'placeholder' => 'Si vous avez quelque chose à ajouter...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thread::class,
            'attr' => [
                'class' => 'flex-grow-1',
            ],
        ]);
    }
}
