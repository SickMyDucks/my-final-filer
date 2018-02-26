<?php

require_once('Cool/BaseController.php');

class MainController extends BaseController
{
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }

    public function registerAction()
    {
        $errors = ['none'];
        require_once('Model/UsersManager.php');
        if(isset($_POST['username']) && isset($_POST['first-name'])
        && isset($_POST['last-name']) && isset($_POST['email'])
        && isset($_POST['password']) && isset($_POST['password-repeat'])
        && isset($_POST['submit'])){
            $username = htmlentities($_POST['username']);
            $firstName = htmlentities($_POST['first-name']);
            $lastName = htmlentities($_POST['last-name']);
            $email = htmlentities($_POST['email']);
            $password = htmlentities($_POST['password']);
            $passwordRepeat = htmlentities($_POST['password-repeat']);
            $manager = new UsersManager();
            $errors = $manager-> register($firstName, $lastName, $username, $email, $password, $passwordRepeat);
            $logs = ['errors' => $errors];
        }
        return $this->render('register.html.twig', $logs);
    }
}
