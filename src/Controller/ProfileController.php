<?php

namespace App\Controller;

use App\Model\AddToListModel;
use App\Model\SearchManager;

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

        var_dump($data);
        // die();

        return $this->twig->render('Home/profile.html.twig');
    }

    // public function createList()
    // {
    // }
}
