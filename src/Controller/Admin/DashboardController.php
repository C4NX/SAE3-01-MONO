<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Agenda;
use App\Entity\AgendaDay;
use App\Entity\Animal;
use App\Entity\AnimalRecord;
use App\Entity\Appointment;
use App\Entity\CategoryAnimal;
use App\Entity\Receipt;
use App\Entity\Thread;
use App\Entity\ThreadMessage;
use App\Entity\TypeAppointment;
use App\Entity\Unavailability;
use App\Entity\User;
use App\Entity\Vacation;
use App\Entity\Vaccine;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Take\'A\'Vet Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Threads');
        yield MenuItem::linkToCrud('Threads', 'fa fa-database', Thread::class);
        yield MenuItem::linkToCrud('Messages', 'fa fa-database', ThreadMessage::class);
        yield MenuItem::section('Animal');
        yield MenuItem::linkToCrud('Category', 'fa fa-database', CategoryAnimal::class);
        yield MenuItem::linkToCrud('Animal', 'fa fa-database', Animal::class);
        yield MenuItem::linkToCrud('Record', 'fa fa-database', AnimalRecord::class);
        yield MenuItem::linkToCrud('Vaccin', 'fa fa-database', Vaccine::class);
        yield MenuItem::section('Agenda');
        yield MenuItem::linkToCrud('Agenda', 'fa fa-database', Agenda::class);
        yield MenuItem::linkToCrud('Unvailbilities', 'fa fa-database', Unavailability::class);
        yield MenuItem::linkToCrud('Jour', 'fa fa-database', AgendaDay::class);
        yield MenuItem::linkToCrud('Vacance', 'fa fa-database', Vacation::class);
        yield MenuItem::linkToCrud('Type RDV', 'fa fa-database', TypeAppointment::class);
        yield MenuItem::linkToCrud('RDV', 'fa fa-database', Appointment::class);
        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('All Users', 'fa fa-database', User::class);
        yield MenuItem::linkToCrud('Address', 'fa fa-database', Address::class);
        yield MenuItem::linkToCrud('Receipt', 'fa fa-database', Receipt::class);
        yield MenuItem::section('Admin');

        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $avatarUrl = ($user instanceof User) ? ('uploads/avatars/'.$user->getProfilePicPath()) : null;

        return parent::configureUserMenu($user)
            ->setAvatarUrl($avatarUrl)
            ->addMenuItems([
                MenuItem::linkToRoute('Mon profil', 'fa fa-id-card', 'app_me'),
            ]);
    }
}
