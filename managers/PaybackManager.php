<?php

class PaybackManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM payback');
        $query->execute();
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
            'expenses_id' => $payback->getExpensesId(),
            'from_user'   => $payback->getFromUser(),
            'to_user'     => $payback->getToUser(),
            'price'       => $payback->getPrice(),
            'payed'       => $payback->isPayed()
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
            'id'          => $payback->getId(),
            'expenses_id' => $payback->getExpensesId(),
            'from_user'   => $payback->getFromUser(),
            'to_user'     => $payback->getToUser(),
            'price'       => $payback->getPrice(),
            'payed'       => $payback->isPayed()
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
