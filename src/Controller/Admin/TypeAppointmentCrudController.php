<?php

namespace App\Controller\Admin;

use App\Entity\TypeAppointment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeAppointmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeAppointment::class;
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
