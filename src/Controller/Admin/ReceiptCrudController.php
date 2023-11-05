<?php

namespace App\Controller\Admin;

use App\Entity\Receipt;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReceiptCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Receipt::class;
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
