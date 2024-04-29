<?php

namespace App\Form;

use App\Entity\Games;
use App\Entity\Riddle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RiddleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'label' => 'Question : ',
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('answer', TextType::class, [
                'label' => 'Réponse : ',
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('pictureFile', FileType::class, [
                'label' => 'Illustration de l\'énigme',
                'row_attr' => ['class' => 'form-group'],
                'mapped' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Riddle::class,
        ]);
    }
}
