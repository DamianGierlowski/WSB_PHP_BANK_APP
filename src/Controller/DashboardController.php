<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(AccountRepository $accountRepository): Response
    {

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'accounts' => $accountRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }
}
