<?php

namespace App\Form;

use App\Entity\Games;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    CollectionType,
    FileType,
    IntegerType,
    RangeType,
    SubmitType,
    TextareaType,
    TextType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('time', IntegerType::class, [
                'label' => 'Temps maximum',
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Difficulté',
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('pictureFile', FileType::class, [
                'label' => 'Illustration',
                'row_attr' => ['class' => 'form-group'],
                'mapped' => true,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('riddle', CollectionType::class, [
                'entry_type' => RiddleType::class,
                'entry_options' => [
                    'label_attr' => ['class' => 'riddle_main_label'],
                ],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => 'Enigmes',
                'label_attr' => ['class' => 'riddles_label'],
                'row_attr' => ['class' => 'riddles_container'],
                'attr' => [
                    'data-controller' => 'form-collection',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'highlighted-button'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Games::class,
        ]);
    }
}
