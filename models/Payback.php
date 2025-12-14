<?php

class Payback
{
    public function __construct(
        private int $expensesId,
        private int $fromUser,
        private int $toUser,
        private float $price,
        private bool $payed = false,
        private ?int $id = null,
        private ?string $fromUserFirstName = null,
        private ?string $toUserFirstName = null,
        private ?string $expenseTitle = null
    ) {
    }

    public function getExpensesId(): int
    {
        return $this->expensesId;
    }

    public function setExpensesId(int $expensesId): void
    {
        $this->expensesId = $expensesId;
    }

    public function getFromUser(): int
    {
        return $this->fromUser;
    }

    public function setFromUser(int $fromUser): void
    {
        $this->fromUser = $fromUser;
    }

    public function getToUser(): int
    {
        return $this->toUser;
    }

    public function setToUser(int $toUser): void
    {
        $this->toUser = $toUser;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function isPayed(): bool
    {
        return $this->payed;
    }

    public function setPayed(bool $payed): void
    {
        $this->payed = $payed;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    
    public function getFromUserFirstName(): ?string
    {
        return $this->fromUserFirstName;
    }

    public function setFromUserFirstName(?string $name): void
    {
        $this->fromUserFirstName = $name;
    }

    public function getToUserFirstName(): ?string
    {
        return $this->toUserFirstName;
    }

    public function setToUserFirstName(?string $name): void
    {
        $this->toUserFirstName = $name;
    }

    public function getExpenseTitle(): ?string
    {
        return $this->expenseTitle;
    }

    public function setExpenseTitle(?string $title): void
    {
        $this->expenseTitle = $title;
    }
}
