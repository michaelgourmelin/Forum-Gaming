<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class CategoriesCrudController extends AbstractCrudController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('delete');
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

        yield IntegerField::new('category_order')->formatValue(fn ($value, $entity) => (string) $value);
    }
}
