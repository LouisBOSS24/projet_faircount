<?php

class PaybackController extends AbstractController
{
    public function create() :void
    {
        if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'ADMIN') {
            $this->redirect('index.php?route=login');
        }
        else {
            if(isset($_POST['firstName']) && !empty($_POST['firstName'])
            && isset($_POST['lastName']) && !empty($_POST['lastName'])
            && isset($_POST['email']) && !empty($_POST['email'])
            && isset($_POST['password']) && !empty($_POST['password'])
            && isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])
            && isset($_POST['role']) && !empty($_POST['role'])) {
                
                $emailUsed = false;
                $userMan = new UserManager;
                $users = $userMan->findAll();
                foreach($users as $user){
                    if($user->getEmail() === $_POST['email']){
                        $emailUsed = true; 
                    }
                }
                if($emailUsed === true){
                    $alrdyUse = ["L'email est déjà utilisé"];
                    $this->render('admin/users/create.html.twig', ["alrdyUse"=>$alrdyUse]);
                }
                if ($_POST['password'] === $_POST['confirmPassword'] && $emailUsed != true){
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $user = new User($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
                    $user->setPassword($hashedPassword);
                    $userMan->create($user);
                    $this->redirect("index.php?route=list");
                }
                elseif ($emailUsed != true){
                    $notSamePassword = ["Le mot de passe est différent"];
                    $this->render('admin/users/create.html.twig', ["notSamePassword"=>$notSamePassword]);
                }
            }
            elseif(isset($_POST['firstName']) && !empty($_POST['firstName'])
            || isset($_POST['lastName']) && !empty($_POST['lastName'])
            || isset($_POST['email']) && !empty($_POST['email'])
            || isset($_POST['password']) && !empty($_POST['password'])
            || isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])
            || isset($_POST['role']) && !empty($_POST['role'])) {
                $missingField = ["Il manque un champ"];
                $this->render('admin/users/create.html.twig', ["missingField"=>$missingField]);
            }
            else {
                $this->render('admin/users/create.html.twig', []);
            }
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
