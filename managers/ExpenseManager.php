<?php

class ExpenseManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM expenses');
        $parameters = [];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $expenses = [];

        foreach ($result as $item)
        {
            $expense = new Expense(
                $item['title'],
                $item['amount'],
                $item['category_id'],
                $item['creator'],
                $item['id']
            );
            $expenses[] = $expense;
        }

        return $expenses;
    }

    public function findById(int $id) : ?Expense
    {
        $query = $this->db->prepare('SELECT * FROM expenses WHERE id = :id');
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if ($item)
        {
            return new Expense(
                $item['title'],
                $item['amount'],
                $item['category_id'],
                $item['creator'],
                $item['id']
            );
        }

        return null;
    }

    public function findByCategory(int $categoryId) : array
    {
        $query = $this->db->prepare('SELECT e.*, c.name AS category_name FROM expenses e JOIN category c ON e.category_id = c.id WHERE c.id = :category_id');


        $parameters = [
            
        ];

        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $expenses = [];
        foreach ($result as $item)
        {
            $expense = new Expense(
            $item['title'],
            $item['amount'],
            $item['category_id'],
            $item['creator'],
            $item['id']
        );


        // Optionnel : si tu veux stocker le nom de la catégorie
        if (method_exists($expense, 'setCategoryName')) {
            $expense->setCategoryName($item['category_name']);
        }


        $expenses[] = $expense;
        }


        return $expenses;
    }

    public function create(Expense $expense) : void
    {
        $query = $this->db->prepare(
            'INSERT INTO expenses (title, amount, category_id, creator)
             VALUES (:title, :amount, :category_id, :creator)'
        );

        $parameters = [
            'title' => $expense->getTitle(),
            'amount' => $expense->getAmount(),
            'category_id' => $expense->getCategoryId(),
            'creator' => $expense->getCreator()
        ];

        $query->execute($parameters);
    }

    public function delete(Expense $expense) : void
    {
        $query = $this->db->prepare('DELETE FROM expenses WHERE id = :id');
        $parameters = [
            'id' => $expense->getId()
        ];
        $query->execute($parameters);
    }
}
