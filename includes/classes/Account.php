<?php

    class Account {

        private $con;
        private $errorArray = [];

        public function __construct($con) {
            $this->con = $con;
        }

        public function login($username, $password) {
            $password = hash("sha512", $password);

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
            $query->bindParam(":username", $username);
            $query->bindParam(":password", $password);

            $query->execute();

            if($query->rowCount() == 1) {
                return true;
            } else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }
        }

        public function register($firstname, $lastname, $username, $email, $email2, $password, $password2) {
            $this->validateFirstName($firstname);
            $this->validateLastName($lastname);
            $this->validateUsername($username);
            $this->validateEmails($email, $email2);
            $this->validatePasswords($password, $password2);

            if(empty($this->errorArray)) {
                return $this->insertUserDetails($firstname, $lastname, $username, $email, $password);
            } else {
                return false;
            }
        }

        public function insertUserDetails($firstname, $lastname, $username, $email, $password) {
            $password = hash("sha512", $password);
            $profilePic = "assets/images/profilePictures/default.png";

            $query = $this->con->prepare("INSERT INTO users(firstName, lastName, username, email, password, profilePic)
                                            VALUES(:firstname, :lastname, :username, :email, :password, :profilePic)");

            $query->bindParam(":firstname", $firstname);
            $query->bindParam(":lastname", $lastname);
            $query->bindParam(":username", $username);
            $query->bindParam(":email", $email);
            $query->bindParam(":password", $password);
            $query->bindParam(":profilePic", $profilePic);

            return $query->execute(); // return true
        }

        private function validateFirstName($firstname) {
            if(strlen($firstname) > 25 || strlen($firstname) < 2) {
                array_push($this->errorArray, Constants::$firstNameCharacters);
            }
        }

        private function validateLastName($lastname) {
            if(strlen($lastname) > 25 || strlen($lastname) < 2) {
                array_push($this->errorArray, Constants::$lastNameCharacters);
            }
        }

        private function validateUsername($username) {
            if(strlen($username) > 25 || strlen($username) < 5) {
                array_push($this->errorArray, Constants::$usernameCharacters);
                return;
            }

            $query = $this->con->prepare("SELECT username FROM users WHERE username=:username");
            $query->bindParam(":username", $username);
            $query->execute();

            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$usernameTaken);
            }
        }

        private function validateEmails($email, $email2) {
            if($email != $email2) {
                array_push($this->errorArray, Constants::$emailsDoNotMatch);
                return;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }

            $query = $this->con->prepare("SELECT email FROM users WHERE email=:email");
            $query->bindParam(":email", $email);
            $query->execute();

            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
            }
        }

        private function validatePasswords($password, $password2) {
            if($password != $password2) {
                array_push($this->errorArray, Constants::$passwordsDoNotMatch);
                return;
            }

            if(preg_match("/[^A-Za-z0-9]/", $password)) {
                array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
                return;
            }

            if(strlen($password) > 30 || strlen($password) < 5) {
                array_push($this->errorArray, Constants::$passwordLength);
            }
        }

        public function getError($error) {
            if(in_array($error, $this->errorArray)) {
                return "<span class='errorMessage'>$error</span>";
            }
        }
    }

?>