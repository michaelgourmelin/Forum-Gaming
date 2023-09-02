<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ThemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            yield  IdField::new('id'),
            yield  AssociationField::new('categories')->autocomplete(),
            yield  TextField::new('name'),
            yield  DateTimeField::new('created_at'),
            yield  AssociationField::new('users')->autocomplete(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_MANAGER_ADMIN'); // Adjust the permission as needed
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Check if the user has the required ROLE_ADMIN role
        if (!$this->isGranted('ROLE_MANAGER_ADMIN')) {
            throw new AccessDeniedException();
        }

        // Handle the deletion of related entities (themes and comments) manually
        $themes = $entityInstance->getComments();
        foreach ($themes as $theme) {
            $entityManager->remove($theme);
        }

        $entityManager->remove($entityInstance);
        $entityManager->flush();
    }

}
