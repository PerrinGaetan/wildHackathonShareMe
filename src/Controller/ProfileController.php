<?php

namespace App\Controller;

use App\Model\AddToListModel;
use App\Model\Connection;
use App\Model\SearchManager;
use PDO;

class ProfileController extends AbstractController
{
    public function displayListById()
    {
        $data = array_map('trim', $_POST);
        $data = array_map('htmlentities', $_POST);
        $addMyList = new AddToListModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['artist'] !== "") {
            $data[] = $_SESSION['userId'];
            $addMyList->addToMyList($data);
        }

        if (!empty($_GET['id'])) {
            $_GET['id'] = intval(trim(htmlentities($_GET['id'])));
            $api = new ApiController();
            $listUserModel = new SearchManager();
            $lists = $listUserModel->getListUser($_GET['id']);
            $informations = [];
            foreach ($lists as $list) {
                $informations[] = $api->index($list);
            }
            return $this->twig->render('Home/profile.html.twig', ['results' => $informations]);
        }

        return $this->twig->render('Home/profile.html.twig');
    }

    public function cleanData(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * errorsInForm
     *
     * @param  array $data
     * @return array
     */
    public function errorsInForm(array $data): array
    {
        $errors = [];

        foreach ($data as $key => $information) {
            switch ($key) {
                case 'nickname':
                    $key = "pseudo";
                    break;
                case 'passwordUser':
                    $key = "mot de passe";
                    break;
                case 'userMail':
                    $key = "email";
                    break;
                default:
                    break;
            }
            if (empty($information)) {
                $errors[$key] = "Le champ $key est requis";
            } elseif ($key === "email") {
                if (filter_var($information, FILTER_VALIDATE_EMAIL) === false) {
                    $errors[$key] = "Veuillez saisir une adresse mail valide";
                }
            }
        }
        return $errors;
    }

    /**
     * isConnected
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return isset($_SESSION['username']);
    }

    /**
     * isRegistered
     *
     * @param  array $data
     * @return array|false
     */
    public function isRegistered(array $data)
    {
        $connectionDB = new Connection();
        $pdo = $connectionDB->getPdoConnection();
        $query =
            "SELECT `nickname`, `password`
            FROM `user`
            WHERE  `nickname` = :nickname";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':nickname', $data['nickname'], \PDO::PARAM_STR);
        $statement->execute();
        $isRegistered = $statement->fetch(\PDO::FETCH_ASSOC);
        return $isRegistered;
    }

    /**
     * saveUser
     *
     * @param  array $data
     * @return void
     */
    public function saveUser(array $data)
    {
        $connectionDB = new Connection();
        $pdo = $connectionDB->getPdoConnection();
        $passwordUser = $this->cleanData($data['passwordUser']);
        $passwordUser = password_hash($passwordUser, PASSWORD_DEFAULT);
        $query =
            "INSERT INTO `rgb_team_wild`.`user` (`nickname`, `password`, `mail`)
            VALUES (:nickname, :passwordUser, :mail);";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':nickname', $data['nickname'], \PDO::PARAM_STR);
        $statement->bindValue(':passwordUser', $passwordUser, \PDO::PARAM_STR);
        $statement->bindValue(':mail', $data['userMail'], \PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * getUserId
     *
     * @return int|false
     */
    public function getUserId()
    {
        $connectionDB = new Connection();
        $pdo = $connectionDB->getPdoConnection();
        $statement = $pdo->prepare("SELECT * from user where nickname = :username");
        $statement->bindValue(":username", $_SESSION['username'], PDO::PARAM_STR);
        $statement->execute();
        $statement = $statement->fetch();
        return intval($statement['id']);
    }
}
