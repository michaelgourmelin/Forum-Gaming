<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * user registration form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'input',
                    'name' => 'email',
                    'id' => 'inputEmail',


                ],

                'label' => 'Email'
            ])

            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'input',
                    'name' => 'firstname',
                    'id' => 'inputFirstname'
                ],

                'label_attr' => [


                    'for' => 'inputFirstname',

                ],

                "label" => 'Pseudo'


            ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'input',

                ],

                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',

                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Votre mot de passe ne doit faire plus de{{ limit }} caractères',

                    ]),
                ],

                'label' => 'Mot de passe'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
