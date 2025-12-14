<?php

class ExpenseController extends AbstractController
{
    public function create() : void
{
    if (!isset($_SESSION['email'])){
            $this->redirect('index.php?route=login');
    }

    if (isset($_POST['title'], $_POST['amount'], $_POST['category']) && !empty($_POST['title']) && !empty($_POST['amount']) && !empty($_POST['category'])) {

            $title = htmlspecialchars($_POST['title']);
            $amount = (float) $_POST['amount'];
            $categoryId = (int) $_POST['category'];
            $creator = $_SESSION['id'];

            $expense = new Expense(
                $title,
                $amount,
                $categoryId,
                $creator
            );

            $expenseManager = new ExpenseManager();
            $expenseManager->create($expense);

            $this->redirect('index.php');
            var_dump($_POST);
            die;
    }

    else {
        $this->render('member/newExpense.html.twig', ['missingField' => ['Il manque un champ']]);
    }
    
}

    public function delete(int $id) : void
    {
        if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'ADMIN') {
            $this->redirect('index.php?route=login');
        }
        else {
            $this->redirect("index.php?route=list");
        }
    }
}
