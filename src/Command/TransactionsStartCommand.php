<?php

namespace App\Command;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'transactions:start',
    description: 'Add a short description for your command',
)]
class TransactionsStartCommand extends Command
{

    public function __construct(
        private TransactionRepository $transactionRepository,
        private EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $transactions = $this->transactionRepository->findBy(['transfered' => false]);

        /** @var Transaction $transaction */
        foreach ($transactions as $transaction)
        {
            $recipient = $transaction->getRecipient();
            $amount = $transaction->getAmmount();

            $recipient->addBalance($amount);

            $transaction->setTransfered(true);
            $this->entityManager->persist($transaction);
            $this->entityManager->persist($recipient);

            $this->entityManager->flush();
        }


        $io->success('Transactions done');

        return Command::SUCCESS;
    }
}
