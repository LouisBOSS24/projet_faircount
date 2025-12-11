<?php

class AuthController extends AbstractController
{
    public function home() : void
    {
        $this->render('home/home.html.twig', []);
    }

    public function transport() :void
    {
        if (!isset($_SESSION['email'])){
            $this->redirect('index.php?route=login');
        }
        else{
            $this->render('member/transport.html.twig', []);
        }
    }

    public function housing() :void
    {
        if (!isset($_SESSION['email'])){
            $this->redirect('index.php?route=login');
        }
        else{
            $this->render('member/housing.html.twig', []);
        }
    }

    public function food() :void
    {
        if (!isset($_SESSION['email'])){
            $this->redirect('index.php?route=login');
        }
        else{
            $this->render('member/food.html.twig', []);
        }
    }

    public function outing() :void
    {
        if (!isset($_SESSION['email'])){
            $this->redirect('index.php?route=login');
        }
        else{
            $this->render('member/outing.html.twig', []);
        }
    }

    public function login() : void
    {
        if(isset($_POST['email']) && !empty($_POST['email'])
        && isset($_POST['password']) && !empty($_POST['password'])) {
            
            
            $userMan = new UserManager;
            $user = $userMan->findByEmail($_POST['email']);
            if($_POST['email'] === $user->getEmail()){
                if (password_verify($_POST['password'],$user->getPassword())){
                    $_SESSION["firstName"] = $user->getFirstName();
                    $_SESSION["lastName"] = $user->getLastName();
                    $_SESSION["email"] = $user->getEmail();
                    $_SESSION["password"] = $user->getPassword();
                    $_SESSION["userRole"] = $user->getRole();
                    $this->render('member/profile.html.twig', []);
                }
                else{
                    $wrongPassword = ["Mauvais mot de passe"];
                    $this->render('auth/login.html.twig', ["wrongPassword"=>$wrongPassword]);
                }
            }
            else{
                $wrongEmail = ["Mauvais email"];
                $this->render('auth/login.html.twig', ["wrongEmail"=>$wrongEmail]);
            }
            
            
        }
        elseif(isset($_POST['email']) && !empty($_POST['email'])
        || isset($_POST['password']) && !empty($_POST['password'])) {
            $missingField = ["Il manque un champ"];
            $this->render('auth/login.html.twig', ["missingField"=>$missingField]);
        }
        else {
            $this->render('auth/login.html.twig', []);
        }
    }
    
    public function logout() : void
    {
        session_destroy();
        $this->redirect('index.php');
    }

    public function register() : void
    {
        if(isset($_POST['firstName']) && !empty($_POST['firstName'])
        && isset($_POST['lastName']) && !empty($_POST['lastName'])
        && isset($_POST['email']) && !empty($_POST['email'])
        && isset($_POST['password']) && !empty($_POST['password'])
        && isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])) {
            
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
                $this->render('auth/register.html.twig', ["alrdyUse"=>$alrdyUse]);
            }
            if ($_POST['password'] === $_POST['confirmPassword'] && $emailUsed != true){
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $user = new User($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
                $user->setPassword($hashedPassword);
                $userMan->create($user);
                $this->redirect("index.php?route=login");
            }
            elseif ($emailUsed != true){
                $notSamePassword = ["Le mot de passe est différent"];
                $this->render('auth/register.html.twig', ["notSamePassword"=>$notSamePassword]);
            }
        }
        elseif(isset($_POST['firstName']) && !empty($_POST['firstName'])
        || isset($_POST['lastName']) && !empty($_POST['lastName'])
        || isset($_POST['email']) && !empty($_POST['email'])
        || isset($_POST['password']) && !empty($_POST['password'])
        || isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])) {
            $missingField = ["Il manque un champ"];
            $this->render('auth/register.html.twig', ["missingField"=>$missingField]);
        }
        else {
            $this->render('auth/register.html.twig', []);
        }
    }

    public function notFound() : void
    {
        $this->render('error/notFound.html.twig', []);
    }
}