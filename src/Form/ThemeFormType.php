<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Theme;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ThemeFormType extends AbstractType
{

    /**
     * add theme form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', options: [

                'attr' => [

                    'id' => 'labelo'
                ],

                'label_attr' => [


                    'for' => 'labelo',

                ],
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un thème',

                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Votre texte ne peut pas faire moins de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                        'maxMessage' => 'Votre texte ne peut pas faire plus de {{ limit }} caractères',
                        
                    ]),
                ],
              

                'label' => 'Sujet'
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'label' => 'Theme',
                'group_by' => 'parent.name',
                'query_builder' => function (CategoriesRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL');
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Theme::class,
        ]);
    }
}
