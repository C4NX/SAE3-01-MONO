<?php

namespace App\Controller\Admin;

use App\Entity\Vaccine;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VaccineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vaccine::class;
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
