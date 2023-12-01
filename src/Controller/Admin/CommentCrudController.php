<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Boolean;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }



    public function configureFields(string $pageName): iterable
    {


        yield  IdField::new('id')->hideOnForm();
        yield  TextField::new('commentaire');
        yield  DateTimeField::new('created_at');
        yield  BooleanField::new('isDelete', 'Is Delete');
        if ($pageName === Crud::PAGE_INDEX) {
            yield  AssociationField::new('users')->autocomplete();
            yield  AssociationField::new('theme')->autocomplete();
        }
        if ($pageName === Crud::PAGE_EDIT) {

            yield  ChoiceField::new('isDelete', 'Is Delete')
                ->setChoices([
                    'Yes' => true,
                    'No' => false,
                ]);
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
