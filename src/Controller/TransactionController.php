<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
class TransactionController extends AbstractController
{

    #[Route('{id}/expenses', name: 'app_transaction_history_expenses')]
    public function index(Account $account): Response
    {
//        dd($account);
        return $this->render('transaction/index.html.twig', [
            'transactions_recipient' => $account->getRecipientTransactions(),
            'transactions_source' => $account->getSourceTransactions()
        ]);
    }



    #[Route('{id}/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Account $account, TransactionRepository $transactionRepository): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($transaction->getAmmount() === 0) {
                $this->addFlash('Error', 'Wartosc musi byc wieksza o 0');

                return $this->renderForm('transaction/new.html.twig', [
                    'transaction' => $transaction,
                    'form' => $form,
                ]);
            }

            if ($transaction->getAmmount() > $account->getBalance()) {
                $this->addFlash('Error', 'Niewystarczajace srodki na koncie');

                return $this->renderForm('transaction/new.html.twig', [
                    'transaction' => $transaction,
                    'form' => $form,
                ]);
            }
            $transaction->setSource($account);
            $transaction->setTransfered(false);

            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }
}
