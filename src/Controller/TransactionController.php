<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
class TransactionController extends AbstractController
{

    #[Route('/{id}/expenses', name: 'app_transaction_history_expenses')]
    public function index(Account $account): Response
    {
//        dd($account);
        return $this->render('transaction/index.html.twig', [
            'transactions_recipient' => $account->getRecipientTransactions(),
            'transactions_source' => $account->getSourceTransactions()
        ]);
    }



    #[Route('{id}/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Account $account, TransactionRepository $transactionRepository, AccountRepository $accountRepository): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            if (0 === $transaction->getAmmount()) {
                $this->addFlash('Error', 'Amount must be higher than 0');

                return $this->render('transaction/new.html.twig', [
                    'transaction' => $transaction,
                    'form' => $form,
                ]);
            }

            if ($transaction->getAmmount() > $account->getBalance()) {
                $this->addFlash('Error', 'Insufficient funds in the account');

                return $this->render('transaction/new.html.twig', [
                    'transaction' => $transaction,
                    'form' => $form,
                ]);
            }
            $recipientNumber = $form->get('recipientNumber')->getData();
            $recipient = $accountRepository->findOneBy(['number' => $recipientNumber]);
            if (null === $recipient) {
                $this->addFlash('Error', 'Recipient account dont exists in system');

                return $this->render('transaction/new.html.twig', [
                    'transaction' => $transaction,
                    'form' => $form,
                ]);
            }

            $account->subBalance($transaction->getAmmount());

            $transaction->setSource($account);
            $transaction->setTransfered(false);
            $transaction->setRecipient($recipient);
            $accountRepository->save($account);
            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }
}
