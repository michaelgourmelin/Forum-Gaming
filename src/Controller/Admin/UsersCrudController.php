<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;





class UsersCrudController extends AbstractCrudController
{
    private $passwordHasher;
    private $security;
    // private $usersRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SecurityBundleSecurity $security,UsersRepository $usersRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        // $this->usersRepository = $usersRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {


        $passwordField = TextField::new('Password')
            ->setFormType(\Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();
     


            $fields = [
                IdField::new('id')->hideOnForm(),
                EmailField::new('email', 'Email'),
                TextField::new('firstname', 'Firstname'),

                ChoiceField::new('roles', 'Roles')
                    ->allowMultipleChoices()
                    ->setChoices([

                        'ROLE_MANAGER_ADMIN' => 'ROLE_MANAGER_ADMIN',                        
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_USER' => 'ROLE_USER',
                    ])
                    ->setRequired(true),
            ];
        

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = $passwordField;
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setEntityPermission('ROLE_MANAGER_ADMIN');
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users && $entityInstance->getPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPassword()
            );

            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
