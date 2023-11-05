<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\CategoryAnimal;
use App\Repository\CategoryAnimalRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name'),
            TextField::new('note'),
            TextField::new('race'),
            DateField::new('birthday'),
            TextField::new('gender'),
            BooleanField::new('isDomestic'),
            AssociationField::new('CategoryAnimal','Category')
                ->setFormType(CategoryAnimal::class)
                ->formatValue(function (?string $value, Animal $entity) {
                    return $entity->getCategoryAnimal()?->getName();
                })
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('query_builder', function (CategoryAnimalRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                })->hideOnForm()

        ];
    }

}
