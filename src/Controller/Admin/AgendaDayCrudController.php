<?php

namespace App\Controller\Admin;

use App\Entity\AgendaDay;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AgendaDayCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AgendaDay::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
