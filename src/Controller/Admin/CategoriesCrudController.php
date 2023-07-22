<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class CategoriesCrudController extends AbstractCrudController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


  
    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('slug');
        if ($pageName === Crud::PAGE_EDIT) {
            $categoryRepository = $this->entityManager->getRepository(Categories::class);
            $categoriesWithoutParent = $categoryRepository->findBy(['parent' => null]);

            yield AssociationField::new('parent')
                ->formatValue(function ($value, $entity) {
                    if ($value instanceof Categories) {
                        return $value->getName();
                    }
                    return $value;
                })
                ->setFormTypeOptions([
                    'choices' => $categoriesWithoutParent,
                    'choice_label' => 'name',
                    'required' => false,
                ]);
        } else {
            yield AssociationField::new('parent');
        }
        if ($pageName === Crud::PAGE_NEW) {
            $categoryRepository = $this->entityManager->getRepository(Categories::class);
            $categoriesWithoutParent = $categoryRepository->findBy(['parent' => null]);

            yield AssociationField::new('parent')
                ->formatValue(function ($value, $entity) {
                    if ($value instanceof Categories) {
                        return $value->getName();
                    }
                    return $value;
                })
                ->setFormTypeOptions([
                    'choices' => $categoriesWithoutParent,
                    'choice_label' => 'name',
                    'required' => false,
                ]);
        } 
        

        yield IntegerField::new('category_order')->formatValue(fn ($value, $entity) => (string) $value);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN'); // Adjust the permission as needed
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Check if the user has the required ROLE_ADMIN role
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        // Handle the deletion of related entities (themes and comments) manually
        $themes = $entityInstance->getThemes();
        foreach ($themes as $theme) {
            $entityManager->remove($theme);
        }

        $entityManager->remove($entityInstance);
        $entityManager->flush();
    }

}
