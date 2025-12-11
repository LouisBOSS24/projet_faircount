<?php

class Router
{
    private AuthController $ac;
    private UserController $uc;
    public function __construct()
    {
        $this->ac = new AuthController();
        $this->uc = new UserController();
    }

    public function handleRequest() : void
    {
        if(!empty($_GET['route'])) {
            if($_GET['route'] === 'login') {
                $this->ac->login();
            }
            else if($_GET['route'] === 'register') {
                $this->ac->register();
            }
            else if($_GET['route'] === 'logout') {
                $this->ac->logout();
            }
            else if($_GET['route'] === 'transport') {
                $this->ac->transport();
            }
            else if($_GET['route'] === 'housing') {
                $this->ac->housing();
            }
            else if($_GET['route'] === 'food') {
                $this->ac->food();
            }
            else if($_GET['route'] === 'outing') {
                $this->ac->outing();
            }
            else if($_GET['route'] === 'profile') {
                $this->uc->profile();
            }
            else if($_GET['route'] === 'create') {
                $this->uc->create();
            }
            else if($_GET['route'] === 'update') {
                $this->uc->update($_GET['id']);
            }
            else if($_GET['route'] === 'delete') {
                $this->uc->delete($_GET['id']);
            }
            else if($_GET['route'] === 'list') {
                $this->uc->list();
            }
            else if($_GET['route'] === 'show') {
                $this->uc->show($_GET['id']);
            }
            else
            {
                $this->ac->notFound();
            }
        }
        else
        {
            $this->ac->home();
        }
    }
}
