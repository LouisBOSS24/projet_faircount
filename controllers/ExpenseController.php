<?php

class ExpenseController extends AbstractController
{
    public function create() : void
    {
        if (!isset($_SESSION['id'])){
            $this->redirect('index.php?route=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // ligne générée par Github Copilot, Prompt : "pourquoi plus rien ne s'affiche j'ai une page blanche..."
            if (isset($_POST['title'], $_POST['amount'], $_POST['category']) && !empty($_POST['title']) && !empty($_POST['amount']) && !empty($_POST['category'])) {
                $title = htmlspecialchars($_POST['title']);
                $amount = (float) $_POST['amount'];
                $categoryId = (int) $_POST['category'];
                $creator = (int) ($_SESSION['id'] ?? 0);

                if ($creator <= 0) {
                    $this->redirect('index.php?route=login');
                }
                if ($amount <= 0) {
                    $this->render('member/newExpense.html.twig', ['wrongAmount' => ['Montant invalide']]);
                    return;
                }

                $expense = new Expense(
                    $title,
                    $amount,
                    $categoryId,
                    $creator
                );

                $expenseManager = new ExpenseManager();
                $expenseManager->create($expense);

                $this->redirect('index.php');
                return;
            }

            $this->render('member/newExpense.html.twig', ['missingField' => ['Il manque un champ']]);
            return;
        }

        $this->render('member/newExpense.html.twig', []);
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
