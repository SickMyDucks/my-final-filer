
<?php
require_once 'Cool/DBManager.php';

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

        if ($isFormValid)
        {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $pdo->prepare("INSERT INTO users (id, firstName, lastName, username, email, hashedPassword) VALUES (NULL, :firstName, :lastName, :username, :email, :hashedPassword)");
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hashedPassword', $hashedPassword);
    
            $stmt->execute();
    
            mkdir('uploads/'.$username);
            header('Location: ?action=login');
        }

        return $errors;
    }

    public function login($username, $password)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->query("SELECT * FROM users WHERE username = '$username'");
        $user = $result->fetch();
        $hash = $user['hashedPassword'];
        if (password_verify($password, $hash)) {
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ?action=upload');
            exit();
        } else {
            return 'Username or password is invalid.';
        }
    }
}