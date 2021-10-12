<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('email', EmailType::class)
            ->add('pseudo', TextType::class)
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
                            'max' => 4096,
                        ]),
                    ]
                ],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les Conditions Générales d\'Utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous avez pas le choix... Déso pas déso.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
