<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    

    public function configureFields(string $pageName): iterable
    {
        return [

            yield IdField::new('id'),
            yield TextField::new('commentaire'),
            yield  AssociationField::new('theme'),
            yield  AssociationField::new('users'),
            yield  DateTimeField::new('created_at'),


        ];
    }  
}
