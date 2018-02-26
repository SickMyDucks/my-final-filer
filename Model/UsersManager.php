
<?php
require_once('Cool/DBManager.php');

class UsersManager
{
    public function register($username, $firstName, $email, $password, $passwordRepeat)
    {
        $isFormValid = true;
        $errors = [];
        if (strlen($_POST['username']) < 4) {
            $errors['username'] = "Username too short";
            $isFormValid = false;
        } 
        /*if (!empty($usernameExisting)) {
            $username_error = 'Username already taken, choose another one.';
            $isFormValid = false;
        }*/ 
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email !";
            $isFormValid = false;
        } 
        /*elseif (!empty($existingEmail)) {
            $email_error = "Email already in use on our site";
            $isFormValid = false;
        } */
        if (strlen($_POST['password']) < 8) {
            $errors['password'] = 'Password too short';
            $isFormValid = false;
        } elseif ($_POST['password'] != $_POST['password-repeat']) {
            $errors['password'] = "Passwords do not match";
            $isFormValid = false;
        }
        return $errors;
    }

    public function login()
    {

    }
}