<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentFormType extends AbstractType
{

    /**
     * add comment form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', options:[
        
             
            'constraints' => [
            new NotBlank([
                'message' => 'Merci de saisir un commentaire',

            ]),
            new Length([
                'min' => 5,
                'minMessage' => 'Votre texte ne peut pas faire moins de {{ limit }} caractères',
                // max length allowed by Symfony for security reasons
                'max' => 800,
                'maxMessage' => 'Votre texte ne peut pas faire plus de {{ limit }} caractères',
                
            ])
        ],
    ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
