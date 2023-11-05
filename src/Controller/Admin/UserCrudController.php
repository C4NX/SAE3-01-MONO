<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private ParameterBagInterface $params;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, ParameterBagInterface $params)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->params = $params;
    }

    public function updateEntity(EntityManagerInterface $manager, $entity): void
    {
        if ($entity instanceof Client) {
            $clearPassword = $this->getContext()->getRequest()->get('Client')['password'];

            if (!empty($clearPassword)) {
                $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $clearPassword));
            }
        }
        parent::updateEntity($manager, $entity);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Client) {
            $clearPassword = $this->getContext()->getRequest()->get('Client')['password'];

            if (!empty($clearPassword)) {
                $entityInstance->setPassword($this->userPasswordHasher->hashPassword($entityInstance, $clearPassword));
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            EmailField::new('email'),
            TextField::new('password')
                ->onlyOnForms()
                ->setFormType(PasswordType::class)
                ->setRequired(false)
                ->setEmptyData('')
                ->setCustomOption('autocomplete', false),
            TextField::new('lastName', 'Nom'),
            TextField::new('firstName', 'Prénom'),
            TextField::new('tel', 'Telephone'),
            ImageField::new('profilePicPath', 'Avatar')
                ->setBasePath('uploads/avatars/')
                ->setUploadDir($this->getParameter('avatar_directory_rel'))
                ->setRequired(false),
        ];
    }
}
