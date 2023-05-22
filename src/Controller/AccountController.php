<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Util\IbanGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account')]
#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new( AccountRepository $accountRepository): Response
    {
        $account = new Account();
        $account->setOwner($this->getUser());
        $account->setBalance(0);
        $account->setNumber(IbanGenerator::generator());

            $accountRepository->save($account, true);
            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
    }

}
