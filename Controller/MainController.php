<?php

require_once('Cool/BaseController.php');

class MainController extends BaseController
{
    public function homeAction()
    {
        session_start();
        $data = ['user' => $_SESSION];
        return $this->render('home.html.twig', $data);
    }

    public function registerAction()
    {
        $data = [];
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
            $data = ['errors' => $errors];
        } else {
            $data['errors']['form'] = "Please fill in all the fields.";
        }
        return $this->render('register.html.twig', $data);
    }

    public function loginAction()
    {
        require_once('Model/UsersManager.php');
        session_start();
        if (isset($_SESSION['username'])) {
            session_destroy();
        }
        $data = [];
        if (isset($_POST['username']) && isset($_POST['password']))
        {
            $username = htmlentities($_POST['username']);
            $password = htmlentities($_POST['password']);
            $manager = new UsersManager();
            $errors = $manager->login($username, $password);
            $data = [
                'errors' => $errors,
                'user'   => $_SESSION,
            ];
        }
        return $this->render('login.html.twig', $data);
    }

    public function logoutAction()
    {
        session_start();
        file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : ' . $_SESSION['username'] . ' logged out' . "\n", FILE_APPEND);
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
