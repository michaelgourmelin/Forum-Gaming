<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;

class UsersPicturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('imageFile', VichImageType::class, [
            
            'required' => false,
            'allow_delete' => true,
            'download_uri' => true,
            'image_uri' => true,
            'constraints' => [
                new File([
                    'maxSize' => '8M',  // Set the maximum file size (adjust as needed)
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],  // Allowed image formats
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG, PNG).',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
