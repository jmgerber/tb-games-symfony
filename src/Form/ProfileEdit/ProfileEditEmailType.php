<?php

namespace App\Form\ProfileEdit;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileEditEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['value' => ''],
                'label' => 'Nouvelle adresse email',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une adresse email',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'max' => 4096
                    ]),
                    new Assert\Email()
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'button--highlighted button--small']
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'button--red button--small cancel-button']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
