<?php
require_once('src/modules/users/Users.php');
require_once('src/modules/users/User.php');
require_once('src/class/Controller.php');

class UsersController extends Controller
{
    static public function creer($userData) {
        if (User::valider($userData)) {
            $user = new User($userData);
            if ($user->creer()) {
                echo "user creer avec success";
            } else {
                echo "user existe deja";
            }
        } else {
            echo "data invalide pour creer user";
        }
    }
}
