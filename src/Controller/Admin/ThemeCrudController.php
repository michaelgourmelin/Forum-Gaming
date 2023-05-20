<?php

namespace App\Controller\Admin;

use App\Controller\ThemeController;
use App\Entity\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ThemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }
  
}
