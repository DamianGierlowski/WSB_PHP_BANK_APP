<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column]
    private ?float $balance = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'source', targetEntity: Transaction::class)]
    private Collection $sourceTransactions;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Transaction::class)]
    private Collection $recipientTransactions;

    public function __construct()
    {
        $this->sourceTransactions = new ArrayCollection();
        $this->recipientTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getSourceTransactions(): Collection
    {
        return $this->sourceTransactions;
    }

    public function addSourceTransaction(Transaction $sourceTransaction): self
    {
        if (!$this->sourceTransactions->contains($sourceTransaction)) {
            $this->sourceTransactions->add($sourceTransaction);
            $sourceTransaction->setSource($this);
        }

        return $this;
    }

    public function removeSourceTransaction(Transaction $sourceTransaction): self
    {
        if ($this->sourceTransactions->removeElement($sourceTransaction)) {
            // set the owning side to null (unless already changed)
            if ($sourceTransaction->getSource() === $this) {
                $sourceTransaction->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getRecipientTransactions(): Collection
    {
        return $this->recipientTransactions;
    }

    public function addRecipientTransaction(Transaction $recipientTransaction): self
    {
        if (!$this->recipientTransactions->contains($recipientTransaction)) {
            $this->recipientTransactions->add($recipientTransaction);
            $recipientTransaction->setRecipient($this);
        }

        return $this;
    }

    public function removeRecipientTransaction(Transaction $recipientTransaction): self
    {
        if ($this->recipientTransactions->removeElement($recipientTransaction)) {
            // set the owning side to null (unless already changed)
            if ($recipientTransaction->getRecipient() === $this) {
                $recipientTransaction->setRecipient(null);
            }
        }

        return $this;
    }

    public function addBalance(float $value): self
    {
        $this->balance += $value;
        return $this;
    }

    public function subBalance(float $value): self
    {
        $this->balance -= $value;
        return $this;
    }
}
