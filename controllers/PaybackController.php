<?php

class PaybackController extends AbstractController
{
    public function create() :void
    {
        // Only logged users can create paybacks
        if (!isset($_SESSION['id'])) {
            $this->redirect('index.php?route=login');
        }

        // Follow project style: check POST fields with isset and not empty
        if (isset($_POST['expenses_id']) || isset($_POST['to_user']) || isset($_POST['price'])) {
            if (isset($_POST['expenses_id'], $_POST['to_user'], $_POST['price']) && !empty($_POST['expenses_id']) && !empty($_POST['to_user']) && $_POST['price'] !== '') {
                $expensesId = (int) $_POST['expenses_id'];
                // The form selects the user who must PAY (debtor). Store that in fromUser.
                $fromUser = (int) $_POST['to_user'];
                // The session user is the recipient (creditor / to_user)
                $toUser = (int) ($_SESSION['id'] ?? 0);
                $price = (float) $_POST['price'];

                if ($toUser <= 0) {
                    $this->redirect('index.php?route=login');
                }

                    if ($price <= 0) {
                        $this->render('member/newPayback.html.twig', ['wrongAmount' => ['Montant invalide']]);
                    return;
                }

                $payback = new Payback($expensesId, $fromUser, $toUser, $price, false);
                $paybackManager = new PaybackManager();
                $paybackManager->create($payback);

                $this->redirect('index.php');
                return;
            }

                $this->render('member/newPayback.html.twig', ['missingField' => ['Il manque un champ']]);
            return;
        }

        // GET: render form
        $expenseMan = new ExpenseManager();
        $userMan = new UserManager();
        $expenses = $expenseMan->findAll();
        $users = $userMan->findAll();

            $this->render('member/newPayback.html.twig', ['expenses' => $expenses, 'users' => $users]);
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

    public function markAsPaid(int $id) : void
    {
        // Must be logged in
        if (!isset($_SESSION['id'])) {
            $this->redirect('index.php?route=login');
        }

        $paybackMan = new PaybackManager();
        $payback = $paybackMan->findById($id);

        if (!$payback) {
            $this->redirect('index.php');
        }

        $currentUser = (int) ($_SESSION['id'] ?? 0);

        // Only the beneficiary (to_user) can confirm payment received
        if ($payback->getToUser() !== $currentUser) {
            $this->redirect('index.php');
        }

        // Mark as paid then remove the record as requested
        $payback->setPayed(true);
        $paybackMan->update($payback);
        $paybackMan->delete($payback);

        $this->redirect('index.php');
    }
}
