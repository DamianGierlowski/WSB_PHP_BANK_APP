<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sourceTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $source = null;

    #[ORM\ManyToOne(inversedBy: 'recipientTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $recipient = null;

    #[ORM\Column]
    private ?float $ammount = null;

    #[ORM\Column]
    private ?bool $transfered = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?Account
    {
        return $this->source;
    }

    public function setSource(?Account $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getRecipient(): ?Account
    {
        return $this->recipient;
    }

    public function setRecipient(?Account $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getAmmount(): ?float
    {
        return $this->ammount;
    }

    public function setAmmount(float $ammount): self
    {
        $this->ammount = $ammount;

        return $this;
    }

    public function isTransfered(): ?bool
    {
        return $this->transfered;
    }

    public function setTransfered(bool $transfered): self
    {
        $this->transfered = $transfered;

        return $this;
    }


}
