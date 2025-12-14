<?php

class PaybackManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        $query = $this->db->prepare(
            'SELECT p.*, ef.firstName AS from_firstName, et.firstName AS to_firstName, e.title AS expense_title
             FROM payback p
             LEFT JOIN users ef ON p.from_user = ef.id
             LEFT JOIN users et ON p.to_user = et.id
             LEFT JOIN expenses e ON p.expenses_id = e.id'
        );
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $paybacks = [];
        foreach ($result as $item) {
            $payback = new Payback(
                $item['expenses_id'],
                $item['from_user'],
                $item['to_user'],
                $item['price'],
                (bool)$item['payed'],
                $item['id']
            );

            if (isset($item['from_firstName']) && method_exists($payback, 'setFromUserFirstName')) {
                $payback->setFromUserFirstName($item['from_firstName']);
            }
            if (isset($item['to_firstName']) && method_exists($payback, 'setToUserFirstName')) {
                $payback->setToUserFirstName($item['to_firstName']);
            }
            if (isset($item['expense_title']) && method_exists($payback, 'setExpenseTitle')) {
                $payback->setExpenseTitle($item['expense_title']);
            }

            $paybacks[] = $payback;
        }

        return $paybacks;
    }

    public function findById(int $id): ?Payback
    {
        $query = $this->db->prepare('SELECT * FROM payback WHERE id = :id');
        $query->execute(['id' => $id]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            return new Payback(
                $item['expenses_id'],
                $item['from_user'],
                $item['to_user'],
                $item['price'],
                (bool)$item['payed'],
                $item['id']
            );
        }

        return null;
    }

    public function findByExpense(int $expenseId): array
    {
        $query = $this->db->prepare('SELECT * FROM payback WHERE expenses_id = :expenses_id');
        $query->execute(['expenses_id' => $expenseId]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $paybacks = [];
        foreach ($result as $item) {
            $paybacks[] = new Payback(
                $item['expenses_id'],
                $item['from_user'],
                $item['to_user'],
                $item['price'],
                (bool)$item['payed'],
                $item['id']
            );
        }

        return $paybacks;
    }

    public function create(Payback $payback): void
    {
        $query = $this->db->prepare(
            'INSERT INTO payback (expenses_id, from_user, to_user, price, payed)
             VALUES (:expenses_id, :from_user, :to_user, :price, :payed)'
        );

        $query->execute([
            'expenses_id'=> $payback->getExpensesId(),
            'from_user'=> $payback->getFromUser(),
            'to_user'=> $payback->getToUser(),
            'price'=> $payback->getPrice(),
            'payed'=> (int) $payback->isPayed()
        ]);
    }

    public function update(Payback $payback): void
    {
        $query = $this->db->prepare(
            'UPDATE payback
             SET expenses_id = :expenses_id,
                 from_user = :from_user,
                 to_user = :to_user,
                 price = :price,
                 payed = :payed
             WHERE id = :id'
        );

        $query->execute([
            'id'=> $payback->getId(),
            'expenses_id'=> $payback->getExpensesId(),
            'from_user'=> $payback->getFromUser(),
            'to_user'=> $payback->getToUser(),
            'price'=> $payback->getPrice(),
            'payed'=> (int) $payback->isPayed()
        ]);
    }

    public function delete(Payback $payback): void
    {
        $query = $this->db->prepare('DELETE FROM payback WHERE id = :id');
        $query->execute([
            'id' => $payback->getId()
        ]);
    }
}
