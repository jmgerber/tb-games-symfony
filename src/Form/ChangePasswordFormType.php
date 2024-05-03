<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, NotBlank, NotCompromisedPassword, PasswordStrength};

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'row_attr' => ['class' => 'form-group'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit faire au minimum {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new PasswordStrength(['minScore' => 1]),
                        new NotCompromisedPassword(),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'row_attr' => ['class' => 'form-group'],
                    'label' => 'Répéter le mot de passe',
                ],
                'invalid_message' => 'Les mots de passe entrés doivent être identiques.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'form_reset_change_password'],
        ]);
    }
}
