<?php

class Expense
{
    public function __construct(
        private string $title, 
        private float $amount,
        private int $categoryId,
        private int $creator,
        private ?int $id = null,
        private ?string $categoryName = null,
        private ?string $creatorName = null
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getCreator(): int
    {
        return $this->creator;
    }

    public function setCreator(int $creator): void
    {
        $this->creator = $creator;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(?string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    public function getCreatorName(): ?string
    {
        return $this->creatorName;
    }

    public function setCreatorName(?string $creatorName): void
    {
        $this->creatorName = $creatorName;
    }
}
