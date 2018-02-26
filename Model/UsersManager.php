
<?php
require_once('Cool/DBManager.php');

class UsersManager
{
    public function register($firstName, $lastName, $username, $email, $password, $passwordRepeat)
    {
        $isFormValid = true;
        $errors = [];
        if (strlen($username) < 4) {
            $errors['username'] = "Username too short";
            $isFormValid = false;
        } 
        /*if (!empty($usernameExisting)) {
            $username_error = 'Username already taken, choose another one.';
            $isFormValid = false;
        }*/ 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email !";
            $isFormValid = false;
        } 
        /*elseif (!empty($existingEmail)) {
            $email_error = "Email already in use on our site";
            $isFormValid = false;
        } */
        if (strlen($password) < 8) {
            $errors['password'] = 'Password too short';
            $isFormValid = false;
        } elseif ($password != $passwordRepeat) {
            $errors['password'] = "Passwords do not match";
            $isFormValid = false;
        }
        return $errors;
    }

    public function login()
    {

    }
}